<?php
/**
 * @author <Akartis>
 * (c) akartis-dev <sitrakaleon23@gmail.com>
 * Do it with love
 */

namespace App\Services\Youtube;


use App\Entity\Customer;
use App\Entity\ErrorDetail;
use App\Entity\Models\FileDataDto;
use App\ObjectManager\EntityObjectManager;
use App\Services\Application\ActionHistoryService;
use App\Services\Bot\CustomerServices;
use App\Services\Constants;
use App\Services\Facebook\FacebookService;
use App\Services\Facebook\MessageService;
use App\Services\FileHandler\FileHandlerService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class YoutubeService
{
    private string $mp3Storage;
    private string $mp4Storage;

    public function __construct(
        private MessageService $messageService,
        private FacebookService $facebookService,
        private EntityObjectManager $em,
        private CustomerServices $customerServices,
        private ActionHistoryService $actionHistoryService,
        private FileHandlerService $fileHandlerService
    )
    {
        $this->mp3Storage = sprintf("%s%s", dirname(__DIR__, 2), Constants::MP3_STORAGE);
        $this->mp4Storage = sprintf("%s%s", dirname(__DIR__, 2), Constants::MP4_STORAGE);
    }

    /**
     * @return string
     */
    public function getMp3Storage(): string
    {
        return $this->mp3Storage;
    }

    /**
     * Handle search and send message to user bot result
     *
     * @param int $id
     * @param string|null $q
     */
    public function handleMp3Search(int $id, ?string $q = ""): void
    {
        /** @var Customer $customer */
        $customer = $this->facebookService->getUserInfo($id);
        $this->em->saveUnique($customer);

        $data = (string)$this->getResultData($q);

        $this->messageService->sendYoutubeResultMessage($id, $data);
    }

    /**
     * Search q term in nodejs service
     * Search in youtube
     *
     * @param string|null $q
     */
    private function getResultData(?string $q = "", bool $isVideo = false)
    {
        $url = sprintf("%s?q=%s&type=%s", $_ENV['YOUTUBE_API'], $q, $isVideo ? "mp4" : "mp3");
        $client = new Client();
        try {
            return $client->request('GET', $url)->getBody();
        } catch (\Exception $e) {
            return "[]";
        }
    }

    /**
     * Download mp3 file
     *
     * @param int $id id of conversation
     * @param string $url mp3 url
     */
    public function downloadMp3(int $id, string $url)
    {
        $customer = $this->customerServices->getCustomerById($id);
        $lastHistory = $this->actionHistoryService->getLastCustomerHistory($customer);

        if (
            $lastHistory &&
            $lastHistory->getUrl() === $url &&
            ($lastHistory->getStatus() === Constants::STATUS_DONE || $lastHistory->getStatus() === Constants::STATUS_INIT)
        ) {

            return false;
        }

        $history = $this->actionHistoryService->createAndSaveNewHistory("", $customer, $url);
        $this->messageService->sendMessageInUser($id, "Andraso ary fa hijery an'izay vetivety aho (1-2 minitra eo eo) ⌛⌛");

        // reset branch new push
        try {
            [$filePath, $fileName] = (new YoutubeDlp())
                ->setDownloadPath($this->mp3Storage)
                ->setExtension("mp3")
                ->setQuality(4)
                ->downloadMp3FromUrl($url);

            $this->messageService->sendMessageInUser($id, sprintf("🎶🎶🔔🔔 Vita a ;) andefa an'ilay hira aho :D \n Ny lohanteny: %s 🔔🔔🎶🎶", str_replace("_", " ", $fileName)));
            $this->messageService->sendFileMessage($id, $filePath);

            $this->actionHistoryService->success($history, $fileName);

        } catch (\Exception $e) {
            $errorDetail = (new ErrorDetail())->setDetail($e->getMessage())
                ->setOrigin("Download musics")
                ->setSearchTerm($url);
            $this->em->save($errorDetail);

            $this->actionHistoryService->errorFail($history);
            $this->messageService->sendMessageInUser(
                $id,
                "Nisy olana kely teo amin'ny fakana an'ilay hira :( :( na ilay izy mavesatra be na tsy mety nalaina mihintsy ilay izy, \n Mangataka mba soloy na andramo averina azafady");
        }
    }

    /**
     * Handle mp4 search
     *
     * @param int $id
     * @param string $q
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function handleMp4Search(int $id, string $q): void
    {
        /** @var Customer $customer */
        $customer = $this->facebookService->getUserInfo($id);
        $this->em->saveUnique($customer);

        $data = (string)$this->getResultData($q, true);
        $this->messageService->sendYoutubeResultMessage($id, $data, true);
    }

    /**
     * Proceed to download mp4 video
     *
     * @param FileDataDto $getFileDataDto
     */
    public function downloadMp4(FileDataDto $fileDataDto): bool
    {
        $customer = $this->checkLastHistory($fileDataDto->getFacebookId(), $fileDataDto->getUrl(), $fileDataDto->getQuality());

        if (!$customer) {

            return false;
        }

        $history = $this->actionHistoryService->createAndSaveNewHistory("", $customer, $fileDataDto->getUrl(), ActionHistoryService::MP4, $fileDataDto->getQuality());
        $this->messageService->sendMessageInUser($fileDataDto->getFacebookId(), "Andraso ary fa hijery an'izay vetivety aho (1-2 minitra eo eo) ⌛⌛");

        try {
            [$_, $fileName] = (new YoutubeDlp())
                ->setDownloadPath($this->mp4Storage)
                ->setExtension("mp4")
                ->setQuality($fileDataDto->getQuality())
                ->downloadMp4FromUrl($fileDataDto->getUrl());

            $filePathWithExtension = sprintf("%s.mp4", $fileName);
            $result = $this->fileHandlerService->checkFileAndSplit($filePathWithExtension, $this->mp4Storage);
            $countResult = count($result['data']);
            $fileSize = floor($result['size'] / 1000);

            if (count($result['data']) <= 1) {
                $this->messageService->sendMessageInUser(
                    $fileDataDto->getFacebookId(),
                    sprintf(
                        "🎬🎬🔔🔔 Vita a ;) andefa an'ilay horonan-tsary aho :D \n - Ny lohanteny: %s 🔔🔔🎬🎬 \n - Kalitao: %sp 🎬 \n - Lanjany: ~%sMo",
                        str_replace("_", " ", $fileName),
                        $fileDataDto->getQuality(),
                        $fileSize
                    )
                );
            } else {
                $this->messageService->sendMessageInUser(
                    $fileDataDto->getFacebookId(),
                    sprintf(
                        "🎬🎬🔔🔔 Vita a ;) nozaraina %s ilay horonan-tsary ka manasa anao anambatra azy ho iray, raha toa ka mila fanazavana ianao dia tsindrio ny 'Fanampiana' amin'ny fandraisana :D \n - Ny lohanteny: %s 🔔🔔🎬🎬, \n - Kalitao: %sp 🎬 \n - Lanjany: ~%sMo",
                        $countResult,
                        str_replace("_", " ", $fileName),
                        $fileDataDto->getQuality(),
                        $fileSize
                    )
                );
            }


            foreach ($result['data'] as $path) {
                $this->messageService->sendFileMessage($fileDataDto->getFacebookId(), $path);
            }
            $this->actionHistoryService->success($history, $fileName);
            shell_exec(sprintf("rm -Rf %s", $result['path']));
        } catch (\Exception $e) {
            $errorDetail = (new ErrorDetail())->setDetail($e->getMessage())
                ->setOrigin("Download video")
                ->setSearchTerm($fileDataDto->getUrl());
            $this->em->save($errorDetail);

            $this->actionHistoryService->errorFail($history);
            $this->messageService->sendMessageInUser(
                $fileDataDto->getFacebookId(),
                "Nisy olana kely teo amin'ny fakana an'ilay horonan-tsary :( :( na ilay izy mavesatra be na tsy mety nalaina mihintsy, \n Mangataka mba soloy na andramo averina azafady");
        }

        return true;
    }

    /**
     * Check last history to prevent re-download
     */
    private function checkLastHistory(int $facebookId, string $url, string $quality): Customer|bool
    {
        $customer = $this->customerServices->getCustomerById($facebookId);
        $lastHistory = $this->actionHistoryService->getLastCustomerHistory($customer);

        if (
            $lastHistory &&
            $lastHistory->getUrl() === $url &&
            (string)$lastHistory->getQuality() === $quality &&
            ($lastHistory->getStatus() === Constants::STATUS_DONE || $lastHistory->getStatus() === Constants::STATUS_INIT)
        ) {
            return false;
        }

        return $customer;
    }
}
