<?php

namespace Metarisc\LexicalParser\Nodes\Element\Text;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\Element\ElementNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;

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

    public function accept(RendererInterface $visitor) : string
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
