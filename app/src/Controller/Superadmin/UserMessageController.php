<?php
/**
 * @author <Akartis>
 */

namespace App\Controller\Superadmin;


use App\Entity\Message\UserMessages;
use App\Form\UserMessageFormsType;
use App\Services\Application\UserMessageService;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/superadmin/messages")]
class UserMessageController extends AbstractController
{
    public function __construct(private UserMessageService $messageService, private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route("/send", name: "send:message:user")]
    public function sendCustomMessage(Request $request): Response
    {
        $newMessage = new UserMessages();
        $form = $this->createForm(UserMessageFormsType::class, $newMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserMessages $newMessage */
            $newMessage = $form->getData();

            if (!$newMessage->getIsAllCustomer() && $newMessage->getCustomer()->count() < 1) {

                $this->addFlash('danger', 'User are required');
            } else {
                $this->messageService->saveAndSendMessage($newMessage);
                $this->addFlash('success', 'Message bien envoyer');

                $url = $this->adminUrlGenerator
                    ->setRoute('send:message:user')
                    ->generateUrl();

                return $this->redirect($url);
            }
        }

        return $this->render('superadmin/messages/send_message_user.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
