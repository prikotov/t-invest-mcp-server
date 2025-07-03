<?php

declare(strict_types=1);

namespace App\Tests\Unit\Component\OperationsService\ValueObject;

use App\Component\OperationsService\ValueObject\QuotationVo;
use PHPUnit\Framework\TestCase;

class QuotationVoTest extends TestCase
{
    public function testCreateFromArrayWithNullData(): void
    {
        $vo = QuotationVo::createFromArray(null);
        $this->assertSame(0.0, $vo->getValue());
        $this->assertSame(0, $vo->getUnits());
        $this->assertSame(0, $vo->getNano());
    }

    public function testCreateFromArrayWithValidData(): void
    {
        $vo = QuotationVo::createFromArray(['units' => 2, 'nano' => 500000000]);
        $this->assertSame(2.5, $vo->getValue());
        $this->assertSame(2, $vo->getUnits());
        $this->assertSame(500000000, $vo->getNano());
    }
}
