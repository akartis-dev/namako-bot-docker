<?php

namespace App\Messenger\UploadVideo;

class ProcessUpload
{
    public function __construct(
        private int $id,
        private string $url
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
    public function getUrl(): string
    {
        return $this->url;
    }
}
