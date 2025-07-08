<?php

declare(strict_types=1);

namespace App\Service\Instruments;

use App\Component\InstrumentsService\Dto\FindInstrumentRequestDto;
use App\Component\InstrumentsService\Dto\GetInstrumentByRequestDto;
use App\Component\InstrumentsService\InstrumentsServiceComponentInterface;
use Override;

final readonly class InstrumentsService implements InstrumentsServiceInterface
{
    public function __construct(private InstrumentsServiceComponentInterface $component)
    {
    }

    #[Override]
    public function getAssetUidByTicker(string $ticker): ?string
    {
        $findResponse = $this->component->findInstrument(new FindInstrumentRequestDto($ticker));
        $uid = null;
        foreach ($findResponse->instruments as $instrument) {
            if ($instrument->ticker === $ticker) {
                $uid = $instrument->uid;
                break;
            }
        }
        if ($uid === null && $findResponse->instruments !== []) {
            $uid = $findResponse->instruments[0]->uid;
        }
        if ($uid === null) {
            return null;
        }
        $getResponse = $this->component->getInstrumentBy(
            new GetInstrumentByRequestDto($uid, 'INSTRUMENT_ID_TYPE_UID')
        );
        return $getResponse->assetUid;
    }
}
