<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

final class InfrastructureException extends RuntimeException implements InfrastructureExceptionInterface
{
}
