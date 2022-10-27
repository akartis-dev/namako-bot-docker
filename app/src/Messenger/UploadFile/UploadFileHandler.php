<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\UploadFile;

use App\Services\Youtube\YoutubeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadFileHandler
{
    public function __construct(
        private YoutubeService $youtubeService
    )
    {
    }

    public function __invoke(UploadFile $uploadFile)
    {
        $this->youtubeService->downloadMp4($uploadFile->getFileDataDto());
    }
}
