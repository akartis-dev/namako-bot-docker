<?php
/**
 * @author <Akartis>
 */

namespace App\Messenger\SendMessages;


class SendMessages
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
