<?php

namespace App\Enums;

enum UserRole: string
{
    case Sponsor = 'sponsor';
    case SuperAdmin = 'super_admin';
    case SponsorshipAdmin = 'sponsorship_admin';
    case UpdateAdmin = 'update_admin';

    /**
     * Check if this role is any kind of admin role.
     */
    public function isAdmin(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::SponsorshipAdmin,
            self::UpdateAdmin,
        ]);
    }

    /**
     * Get a human-readable label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Sponsor => 'Sponsor',
            self::SuperAdmin => 'Super Admin',
            self::SponsorshipAdmin => 'Sponsorship Admin',
            self::UpdateAdmin => 'Update Admin',
        };
    }

    /**
     * Get all admin roles (excluding sponsor).
     *
     * @return array<self>
     */
    public static function adminRoles(): array
    {
        return [
            self::SuperAdmin,
            self::SponsorshipAdmin,
            self::UpdateAdmin,
        ];
    }
}
