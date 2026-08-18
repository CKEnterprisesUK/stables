<?php

namespace App\Enums;

enum UserRole: string
{
    case Sponsor = 'sponsor';
    case Admin = 'admin';
}
