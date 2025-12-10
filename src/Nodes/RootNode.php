<?php

namespace App\Nodes;

use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class RootNode extends ElementNode
{
    private const TYPE = 'root';

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitRoot($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
