<?php
/**
 * @author <Akartis>
 */

namespace App\Services\Application;


use App\Entity\Message\UserMessages;
use App\Messenger\SendMessages\SendMessages;
use App\ObjectManager\EntityObjectManager;
use App\Repository\CustomerRepository;
use App\Services\Facebook\FacebookService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserMessageService
{
    public function __construct(
        private EntityObjectManager $em,
        private MessageBusInterface $messageBus,
        private FacebookService $facebookService,
        private SerializerInterface $serializer
    )
    {
    }

    /**
     * Save and send message from dashboard
     *
     * @param UserMessages $messages
     */
    public function saveAndSendMessage(UserMessages $message): void
    {
        $message->setSender(null);
        $this->em->save($message);

        $this->messageBus->dispatch(new SendMessages($message->getId()));
    }

    /**
     * Save message from customer
     */
    public function saveMessageFromCustomer(int $customerId, string $message): void
    {
        $customer = $this->facebookService->getUserInfo($customerId);

        if ($customer) {
            /** @var CustomerRepository $repository */
            $repository = $this->em->getEm()->getRepository("App:Customer");
            $customerEntity = $repository->findOneBy(['facebookId' => $customer->getFacebookId()]);

            if (!$customerEntity) {
                $customerEntity = $this->em->saveUnique($customer);
            }

            $userMessage = (new UserMessages())
                ->setContent($message)
                ->setSender($customerEntity);

            $this->em->save($userMessage);
        }
    }

    /**
     * Find customer message by facebook id
     * @param int $facebookId
     */
    public function findCustomerMessage(int $facebookId)
    {
        $repository = $this->em->getEm()->getRepository("App:Customer");
        $customer = $repository->findOneBy(['facebookId' => $facebookId]);

        if (!$customer) {
            return [];
        }

        $userMessage = $this->em->getEm()->getRepository("App:Message\UserMessages")
            ->findByCustomer($customer);

        return json_decode(
            $this->serializer->serialize($userMessage, 'json', ['groups' => "message:get"]),
            true, 512, JSON_THROW_ON_ERROR);
    }

    public function saveAndSendMessageApi(int $facebookId, string $content)
    {
        $customer = $this->em->getEm()->getRepository("App:Customer")
            ->findOneBy(['facebookId' => $facebookId]);

        if($customer){
            $userMessage = (new UserMessages())
                ->setContent($content)
                ->setSender(null)
                ->addCustomer($customer)
            ;

            $this->saveAndSendMessage($userMessage);
        }

    }
}
