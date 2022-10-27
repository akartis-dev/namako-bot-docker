<?php
/**
 * @author <akartis-dev>
 */

namespace App\Entity\Models;


class FileDataDto
{
    public function __construct(
        private string $facebookId,
        private string $url,
        private string $type,
        private ?int $quality = null
    )
    {
    }

    public static function fromJSON(int $facebookId, string $data): FileDataDto
    {
        $decoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return new FileDataDto($facebookId, $decoded['url'], $decoded['type'], $decoded['quality'] ?? null);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * @return string
     */
    public function getFacebookId(): string
    {
        return $this->facebookId;
    }
}
