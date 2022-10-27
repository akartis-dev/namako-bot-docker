<?php
/**
 * @author <Akartis>
 */

namespace App\Messenger\UploadVideo;

use App\Services\Youtube\YoutubeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessUploadHandler
{
    public function __construct(
        private YoutubeService $youtubeService
    )
    {
    }

    public function __invoke(ProcessUpload $processUpload)
    {
        $this->youtubeService->downloadMp3($processUpload->getId(), $processUpload->getUrl());
    }
}
