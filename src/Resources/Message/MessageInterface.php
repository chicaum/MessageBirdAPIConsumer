<?php

declare(strict_types=1);

namespace App\Resources\Message;

interface MessageInterface
{
    const UDH_HEADER_IMMUTABLE = '050003';

    public function prepareMessage(): array ;
}
