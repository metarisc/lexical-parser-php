<?php

namespace Metarisc\LexicalParser\Nodes\Element\List;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class ListItemNode extends ElementNode
{
    public const TYPE = 'list-item';
    public ?string $isChecked;

    public function __construct(array $data)
    {
        $this->isChecked = $data['checked'] ?? null;
        $this->style     = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
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
