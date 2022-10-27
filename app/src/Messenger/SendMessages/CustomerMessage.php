<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\SendMessages;


class CustomerMessage
{
    public function __construct(private int $id, private string $message)
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
    public function getMessage(): string
    {
        return $this->message;
    }
}
