<?php
/**
 * @author <Akartis>
 * (c) akartis-dev <sitrakaleon23@gmail.com>
 * Do it with love
 */

namespace App\Services\Bot;


use App\Entity\Models\FileDataDto;
use App\Messenger\HelpDownloadSplit\HelpDownloadSplit;
use App\Messenger\SearchVideo\SearchVideo;
use App\Messenger\SearchVideo\SearchVideoMp4;
use App\Messenger\SendMessages\CustomerMessage;
use App\Messenger\UploadFile\UploadFile;
use App\Messenger\UploadVideo\ProcessUpload;
use App\Services\Application\UserMessageService;
use App\Services\Facebook\MessageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class BotService
{
    public function __construct(
        private MessageService $messageService,
        private MessageBusInterface $messageBus,
        private UserMessageService $userMessageService
    )
    {
    }

    /**
     * Handle message from facebook messenger
     */
    public function handleMessage(Request $request, Response $response): void
    {
        $response->setStatusCode(200);
        $jsonContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ("page" === $jsonContent['object']) {
            $entry = $jsonContent['entry'];
            if (count($entry) > 0) {
                foreach ($entry as $item) {
                    foreach ($item['messaging'] as $message) {
                        $detail = [];
                        $detail['id'] = $message['sender']['id'];
                        $detail['time'] = (new \DateTime())->setTimestamp(substr($message['timestamp'], 0, 10));

                        if (isset($message['message'])) {
                            $detail['message'] = $message['message']['text'];

                            $this->messageTreatment($detail);
                        }

                        if (isset($message['postback'])) {
                            $detail['postback'] = $message['postback'];
                            $this->handleMessageAction($detail);
                        }
                    }
                }
            }
        }
    }

    public function messageTreatment(array $detail): void
    {
        $this->messageService->sendMarkSeen($detail['id']);
        $this->keyExtract($detail);
    }

    /**
     * Handle action from selected audio
     *
     * @param array $detail
     */
    public function handleMessageAction(array $detail): void
    {
        if ($detail['postback']) {
            switch ($detail['postback']['title']) {
                case "Mp3":
                    $this->messageBus->dispatch(new ProcessUpload($detail['id'], $detail['postback']['payload']));
                    break;
                case "360p":
                case "480p":
                    $this->messageBus->dispatch(new UploadFile(FileDataDto::fromJSON($detail['id'], $detail['postback']['payload'])));
                    break;
                // Help section
                case "* Mp3":
                    $this->messageService->sendMessageInUser($detail['id'], "Raha aka hira mp3 ianao dia soraty hoe mp3 dia ny lohanteny tadiavinao, ohatra mp3 tanora masina");
                    break;
                case "* Mp4":
                    $this->messageService->sendMessageInUser($detail['id'], "Raha aka horonantsary ianao dia soraty hoe mp4 dia ny lohanteny tadiavinao, ohatra mp4 tanora masina");
                    break;
                case "* Hafatra":
                    $this->messageService->sendMessageInUser($detail['id'], "Raha handefa hafatra ho an'ny mpikarakara ianao dia soraty hoe hafatra dia ny fangatahanao, ohatra hafatra mankasitraka amin'ny tolotra ;)");
                    break;
                case "* Fanampiana":
                    $this->messageBus->dispatch(new HelpDownloadSplit($detail['id']));
                    break;
                default:
                    $this->messageService->sendMessageInUser($detail['id'], "Tsy tontosa ny fangatahanao");
            }
        }
    }

    /**
     * Extract key for search or nothing
     *
     * @param array|null $detail
     * @return false
     */
    private function keyExtract(?array $detail)
    {
        $re = '/([a-zA-z0-9]+)\s(.*)/m';

        $thanksArray = ['merci', 'misaotra', 'mankasitraka', 'm6', 'mrc', 'thanks'];
        $message = mb_strtolower(trim($detail['message']));

        foreach ($thanksArray as $text) {
            if (str_starts_with($message, $text)) {
                $this->messageService->sendMessageInUser(
                    $detail['id'],
                    "Mankasitraka indrindra 🎵🎵👌"
                );

                return true;
            }
        }

        preg_match($re, $detail['message'], $matches);

        if (empty($matches[0])) {
            $this->messageService->sendWelcomeBot($detail['id']);
            return false;
        }

        switch (mb_strtolower($matches[1])) {
            case "mp3":
                $this->messageBus->dispatch(new SearchVideo($detail['id'], $matches[2], $detail['message']));
                break;
            case "mp4":
                $this->messageBus->dispatch(new SearchVideoMp4($detail['id'], $matches[2], $detail['message']));
                break;
            case "hafatra":
                $this->messageBus->dispatch(new CustomerMessage($detail['id'], $matches[2]));
                $this->messageService->sendMessageInUser(
                    $detail['id'],
                    "Salama o 🎵🎵 , voray ny hafatra nalefanao, hahazo valiny tsy ho ela ianao, mankasitraka :)"
                );
                break;
            default:
                $this->messageService->sendWelcomeBot($detail['id']);
        }

        return true;
    }
}
