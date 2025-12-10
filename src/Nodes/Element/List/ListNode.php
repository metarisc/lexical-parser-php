<?php

namespace Metarisc\LexicalParser\Nodes\Element\List;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class ListNode extends ElementNode
{
    private const TYPE = 'list';
    private ?string $listType;

    public function __construct(array $data)
    {
        $this->listType = $data['listType'] ?? null;
        $this->style    = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
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
