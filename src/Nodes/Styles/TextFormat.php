<?php

namespace App\Nodes\Styles;

enum TextFormat : string
{
    case BOLD          = 'bold';
    case ITALIC        = 'italic';
    case UNDERLINE     = 'underline';
    case STRIKETHROUGH = 'strikethrough';

    /**
     * Décode les flags binaires du format texte Lexical.
     *
     * Lexical utilise des bitwise flags :
     * - 1 (0b0001) = Bold
     * - 2 (0b0010) = Italic
     * - 4 (0b0100) = Strikethrough
     * - 8 (0b1000) = Underline
     *
     * Exemple : 9 = 1 + 8 = Bold + Underline
     *
     * @param int $flags Le nombre représentant les formats combinés
     *
     * @return TextFormat[] Tableau des formats de texte actifs
     */
    public static function parseTextFormatFlags(int $flags) : array
    {
        $formats = [];

        // Vérifier chaque bit
        if ($flags & 1) {
            $formats[] = TextFormat::BOLD;
        }
        if ($flags & 2) {
            $formats[] = TextFormat::ITALIC;
        }
        if ($flags & 4) {
            $formats[] = TextFormat::STRIKETHROUGH;
        }
        if ($flags & 8) {
            $formats[] = TextFormat::UNDERLINE;
        }

        return $formats;
    }
}
