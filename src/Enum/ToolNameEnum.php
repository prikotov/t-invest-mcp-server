<?php

declare(strict_types=1);

namespace App\Enum;

enum ToolNameEnum: string
{
    case getPortfolio = 'get_portfolio';
    case getAccounts = 'get_accounts';
    case getAssetFundamentals = 'get_asset_fundamentals';
}
