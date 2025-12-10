<?php

namespace App\Nodes\Element\List;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class ListNode extends ElementNode
{
    private const TYPE = 'list';
    private ?string $listType;

    public function __construct(array $data)
    {
        $this->listType = $data['listType'] ?? null;
        $this->style    = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitList($this);
    }

    public function getListType() : ?string
    {
        return $this->listType;
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
