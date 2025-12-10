<?php

namespace Metarisc\LexicalParser\Nodes\Element\Text;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\NodeInterface;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class TextNode implements NodeInterface
{
    private string $text;
    private const string TYPE = 'text';
    private ?Style $style     = null;

    public function __construct(array $data)
    {
        $this->text  = $data['text'] ?? '';
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitText($this);
    }

    public function getText() : ?string
    {
        return $this->text;
    }

    public function getType() : string
    {
        return self::TYPE;
    }

    public function getStyle() : ?Style
    {
        return $this->style;
    }
}
