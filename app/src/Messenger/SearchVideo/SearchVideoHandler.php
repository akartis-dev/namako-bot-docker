<?php
/**
 * @author <Akartis>
 */

namespace App\Messenger\SearchVideo;

use App\Services\Application\AppService;
use App\Services\Youtube\YoutubeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchVideoHandler
{
    public function __construct(
        private YoutubeService $youtubeService,
        private AppService $appService
    )
    {
    }

    public function __invoke(SearchVideo $searchVideo)
    {
        $this->appService->setSearchTerm($searchVideo->getTerm());
        $this->youtubeService->handleMp3Search($searchVideo->getId(), $searchVideo->getQ());
    }
}
