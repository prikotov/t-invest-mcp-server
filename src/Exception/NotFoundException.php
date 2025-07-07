<?php

declare(strict_types=1);

namespace App\Exception;

use LogicException;

final class NotFoundException extends LogicException implements NotFoundExceptionInterface
{
}
