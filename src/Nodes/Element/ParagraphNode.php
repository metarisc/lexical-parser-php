<?php

namespace App\Nodes\Element;

use App\Nodes\Styles\Style;
use App\Renderrer\RenderrerInterface;

class ParagraphNode extends ElementNode
{
    private ?int $indent;
    private ?string $direction;
    private const string TYPE = 'paragraph';

    public function __construct(array $data)
    {
        $this->indent    = $data['indent'] ?? null;
        $this->direction = $data['direction'] ?? null;
        $this->style     = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitParagraph($this);
    }

    public function getIndent() : ?int
    {
        return $this->indent;
    }

    public function getDirection() : ?string
    {
        return $this->direction;
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
