<?php

namespace Metarisc\LexicalParser\Nodes;

use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class LineBreakNode extends ElementNode
{
    private const TYPE = 'line_break';

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitLineBreak($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
