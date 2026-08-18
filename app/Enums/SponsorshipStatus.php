<?php

namespace App\Enums;

enum SponsorshipStatus: string
{
    case Active = 'active';
    case Gift = 'gift';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
