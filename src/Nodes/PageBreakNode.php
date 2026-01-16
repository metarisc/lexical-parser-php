<?php

namespace Metarisc\LexicalParser\Nodes;

use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class PageBreakNode extends ElementNode
{
    private const TYPE = 'page_break';

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitPageBreak($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
