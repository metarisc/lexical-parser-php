<?php

namespace Metarisc\LexicalParser\Nodes\Styles;

enum ListFormat : string
{
    case BULLET   = 'bullet';
    case NUMBERED = 'numbered';
    case CHECK    = 'check';

    public static function fromString(string $value) : ?self
    {
        return match (mb_strtolower($value)) {
            'bullet' => self::BULLET,
            'number', 'numbered' => self::NUMBERED,
            'check' => self::CHECK,
            default => null,
        };
    }
}
