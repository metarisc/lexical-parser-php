<?php

namespace Metarisc\LexicalParser\Nodes\Element;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class HeadingNode extends ElementNode
{
    private const string TYPE = 'heading';
    private ?string $tag      = null;

    public function __construct(array $data = [])
    {
        $this->tag   = $data['tag'] ?? 'h1';
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitHeading($this);
    }

    public function getType() : string
    {
        return self::TYPE;
    }

    public function getTag() : ?string
    {
        return $this->tag;
    }
}
