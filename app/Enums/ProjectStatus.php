<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Upcoming = 'upcoming';
    case Ongoing = 'ongoing';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Upcoming => 'Upcoming Project',
            self::Ongoing => 'Ongoing Project',
            self::Completed => 'Completed Project',
        };
    }
}
