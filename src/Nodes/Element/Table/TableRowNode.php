<?php

namespace App\Nodes\Element\Table;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class TableRowNode extends ElementNode
{
    private const TYPE = 'table-row';

    public function __construct(array $data = [])
    {
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitTableRow($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
