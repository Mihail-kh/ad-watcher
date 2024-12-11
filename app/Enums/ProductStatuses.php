<?php

namespace App\Enums;

enum ProductStatuses: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
