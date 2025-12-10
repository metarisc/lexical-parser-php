<?php

namespace App\Nodes;

use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class LineBreakNode extends ElementNode
{
    private const TYPE = 'line_break';

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitLineBreak($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
