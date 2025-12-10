<?php

namespace Metarisc\LexicalParser\Nodes\Element\Table;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class TableCellNode extends ElementNode
{
    private const TYPE    = 'table-cell';
    private ?int $colSpan = null;
    private ?int $rowSpan = null;

    public function __construct(array $data = [])
    {
        $this->colSpan = $data['colSpan'] ?? null;
        $this->rowSpan = $data['rowSpan'] ?? null;
        $this->style   = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitTableCell($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }

    public function getColSpan() : ?int
    {
        return $this->colSpan;
    }

    public function getRowSpan() : ?int
    {
        return $this->rowSpan;
    }
}
