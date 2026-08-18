<?php

namespace App\Enums;

enum GiftSponsorshipStatus: string
{
    case Purchased = 'purchased';
    case Redeemed = 'redeemed';
    case Expired = 'expired';
}
