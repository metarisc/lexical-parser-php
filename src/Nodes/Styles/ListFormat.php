<?php

namespace App\Nodes\Styles;

enum ListFormat : string
{
    case BULLET   = 'bullet';
    case NUMBERED = 'numbered';
    case CHECKBOX = 'checkbox';

    public static function fromString(string $value) : ?self
    {
        return match (mb_strtolower($value)) {
            'bullet' => self::BULLET,
            'numbered' => self::NUMBERED,
            'checkbox' => self::CHECKBOX,
            default => null,
        };
    }
}
