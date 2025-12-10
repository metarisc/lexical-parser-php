<?php

namespace App\Nodes\Styles;

enum Alignment : string
{
    case CENTER  = 'center';
    case LEFT    = 'left';
    case RIGHT   = 'right';
    case JUSTIFY = 'justify';

    public static function fromString(string $value) : ?self
    {
        return match (mb_strtolower($value)) {
            'center' => self::CENTER,
            'left' => self::LEFT,
            'right' => self::RIGHT,
            'justify' => self::JUSTIFY,
            default => null,
        };
    }
}
