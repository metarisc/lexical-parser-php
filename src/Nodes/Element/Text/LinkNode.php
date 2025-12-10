<?php

namespace App\Nodes\Element\Text;

use App\Nodes\Styles\Style;
use App\Nodes\Element\ElementNode;
use App\Renderrer\RenderrerInterface;

class LinkNode extends ElementNode
{
    private const string TYPE = 'link';
    private ?string $url;
    private ?string $title;

    public function __construct(array $data)
    {
        $this->url   = $data['url'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
    {
        return $visitor->visitLink($this);
    }

    public function getUrl() : ?string
    {
        return $this->url;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
