<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\SendMessages;

use App\Services\Application\UserMessageService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CustomerMessageHandler
{
    public function __construct(private UserMessageService $userMessageService)
    {
    }

    public function __invoke(CustomerMessage $customerMessage)
    {
        $this->userMessageService->saveMessageFromCustomer($customerMessage->getId(), $customerMessage->getMessage());
    }
}
