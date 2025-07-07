<?php

declare(strict_types=1);

namespace App\Service\TextContentSerializer;

interface TextContentSerializerServiceInterface
{
    public function serialize(mixed $content): string;
}
