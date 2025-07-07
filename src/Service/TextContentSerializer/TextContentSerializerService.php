<?php

declare(strict_types=1);

namespace App\Service\TextContentSerializer;

use JsonException;
use Override;

final class TextContentSerializerService implements TextContentSerializerServiceInterface
{
    /**
     * @throws JsonException
     */
    #[Override]
    public function serialize(mixed $content): string
    {
        $encoded = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $payload = json_decode($encoded, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($payload)) {
            return $encoded;
        }
        $payload = $this->filterNullValues($payload);
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function filterNullValues(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->filterNullValues($value);
                if ($payload[$key] === []) {
                    unset($payload[$key]);
                }
            } elseif ($value === null) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }
}
