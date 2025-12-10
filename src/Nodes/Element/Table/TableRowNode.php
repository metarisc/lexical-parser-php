<?php

namespace Metarisc\LexicalParser\Nodes\Element\Table;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class TableRowNode extends ElementNode
{
    private const TYPE = 'table-row';

    public function __construct(array $data = [])
    {
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitTableRow($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
