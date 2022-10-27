<?php
/**
 * @author <akartis-dev>
 */

namespace App\Controller\Superadmin\Api;


use App\Services\Application\UserMessageService;
use App\Services\Facebook\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/message")]
class MessagesApiController extends AbstractController
{
    public function __construct(
        private UserMessageService $userMessageService
    )
    {
    }

    #[Route("/customer", methods: ['POST'])]
    public function getMessageByCustomer(Request $request)
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $result = $this->userMessageService->findCustomerMessage($data['id']);

        return $this->json($result);
    }

    #[Route("/reply", methods: ['POST'])]
    public function replyCustomer(Request $request)
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->userMessageService->saveAndSendMessageApi($data['id'], $data['content']);

        return $this->json(['send successful']);
    }
}
