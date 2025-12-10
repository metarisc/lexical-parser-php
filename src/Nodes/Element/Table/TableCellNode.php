<?php

namespace App\Nodes\Element\Table;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

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

    public function accept(RenderrerInterface $visitor) : string
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
