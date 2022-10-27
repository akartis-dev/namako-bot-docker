<?php

namespace App\Controller;

use App\ObjectManager\EntityObjectManager;
use App\Services\Bot\BotService;
use App\Services\Facebook\FacebookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author <Akartis>
 */
class PagesController extends AbstractController
{
    #[Route('/bot', name: "bot.index", methods: ['POST', 'GET'])]
    public function bot(Request $request, BotService $botService)
    {
        // Facebook callback url
        if (
            ($request->getMethod() === Request::METHOD_GET) &&
            $request->query->get('hub_verify_token') === $_ENV['FACEBOOK_VERIFY_TOKEN']
        ) {
            return (new Response())
                ->setContent($request->query->get('hub_challenge'))
                ->setStatusCode(Response::HTTP_OK);
        }

        if ($request->getMethod() === Request::METHOD_POST) {
            $response = new Response();
            $botService->handleMessage($request, $response);

            return $response;
        }
    }

    #[Route("/test-user", name: "test.user", methods: ["GET"])]
    public function testUserInfo(FacebookService $facebookService, EntityObjectManager $em)
    {
        $customer = $facebookService->getUserInfo("3770151903104015");
        //Demo
        $em->saveUnique($customer);
        dd($customer);
    }
}
