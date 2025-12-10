<?php

namespace Metarisc\LexicalParser\Nodes\Element\Table;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class TableNode extends ElementNode
{
    private const TYPE = 'table';

    public function __construct(array $data = [])
    {
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitTable($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
