<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\HelpDownloadSplit;


class HelpDownloadSplit
{
    public function __construct(private int $id)
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
