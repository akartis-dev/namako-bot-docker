<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\HelpDownloadSplit;

use App\Services\Facebook\MessageService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class HelpDownloadSplitHandler
{
    public function __construct(private MessageService $messageService)
    {
    }

    public function __invoke(HelpDownloadSplit $helpDownloadSplit)
    {
        $facebookId = $helpDownloadSplit->getId();

        $this->messageService->sendMessageInUser($facebookId,
            "Tsy afaka miotra ny 25Mo ny zavatra alefa amin'ny Facebook, ka mila zarazaraina ilay izy mba ahafahana mandefa azy, dia manasa anao ijery an'ito vidéo ito sy aka an'ito application ito mba ahafahanao manambatra ireo fichier maro ho iray mba ampiasana azy."
        );

        $this->messageService->sendMediaFacebookUrl($facebookId, "https://web.facebook.com/namakoBot/videos/668879697522409");
        $applicationPath = sprintf("%s/storage/zarchiver.apk", dirname(__DIR__, 2));
        $this->messageService->sendFileMessage($facebookId, $applicationPath, false);
    }
}
