<?php

declare(strict_types=1);

namespace App\List;

use App\Enum\ToolNameEnum;

final class ToolsList
{
    /**
     * @return ToolNameEnum[]
     */
    public function get(): array
    {
        return [
            ToolNameEnum::getPortfolio,
            ToolNameEnum::getAccounts,
            ToolNameEnum::getAssetFundamentals,
        ];
    }
}
