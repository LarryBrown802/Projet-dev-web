<?php

namespace App\Models;

class OfferManagementModel extends OfferModel
{
    protected int $parPage = 5;

    public function getOffersByPilot(int $pilotId): array
    {
        return array_values(array_filter(
            $this->getAllOffers(),
            fn($o) => $o['pilot_id'] === $pilotId
        ));
    }
}