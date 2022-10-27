<?php

namespace App\Messenger\SearchVideo;

class SearchVideo
{
    public function __construct(
        private int $id,
        private string $q,
        private string $term
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @return string
     */
    public function getTerm(): string
    {
        return $this->term;
    }
}
