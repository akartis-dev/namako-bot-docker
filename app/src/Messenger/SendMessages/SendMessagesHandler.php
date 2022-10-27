<?php
/**
 * @author <Akartis>
 */

namespace App\Messenger\SendMessages;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\Message\UserMessagesRepository;
use App\Services\Facebook\MessageService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendMessagesHandler
{
    public function __construct(
        private MessageService $messageService,
        private UserMessagesRepository $userMessagesRepository,
        private CustomerRepository $customerRepository
    )
    {
    }

    public function __invoke(SendMessages $message): bool|null
    {
        $userMessage = $this->userMessagesRepository->findOneBy(['id' => $message->getId()]);

        if (!$userMessage) {
            return null;
        }

        $content = $userMessage->getContent();
        $customers = $userMessage->getCustomer();

        if($userMessage->isAllCustomer()){
            $customers = $this->customerRepository->findAll();
        }

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            $this->messageService->sendMessageInUser($customer->getFacebookId(), $content);
        }

        return true;
    }
}
