<?php

namespace App\Nodes\Element\List;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class ListItemNode extends ElementNode
{
    public const TYPE = 'list-item';
    public ?string $isChecked;

    public function __construct(array $data)
    {
        $this->isChecked = $data['checked'] ?? null;
        $this->style     = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitListItem($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }

    public function isChecked() : ?string
    {
        return $this->isChecked;
    }
}
