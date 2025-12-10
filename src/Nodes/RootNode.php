<?php

namespace Metarisc\LexicalParser\Nodes;

use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class RootNode extends ElementNode
{
    private const TYPE = 'root';

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitRoot($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
