<?php

namespace App\Nodes\Element\Table;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class TableNode extends ElementNode
{
    private const TYPE = 'table';

    public function __construct(array $data = [])
    {
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitTable($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
