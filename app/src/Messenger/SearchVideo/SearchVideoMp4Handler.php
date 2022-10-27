<?php

namespace App\Messenger\SearchVideo;

use App\Services\Application\AppService;
use App\Services\Youtube\YoutubeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchVideoMp4Handler
{
    public function __construct(
        private YoutubeService $youtubeService,
        private AppService $appService
    )
    {
    }

    public function __invoke(SearchVideoMp4 $searchVideo)
    {
        $this->appService->setSearchTerm($searchVideo->getTerm());
        $this->youtubeService->handleMp4Search($searchVideo->getId(), $searchVideo->getQ());
    }
}
