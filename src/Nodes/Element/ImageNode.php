<?php

namespace Metarisc\LexicalParser\Nodes\Element;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Renderer\RendererInterface;

class ImageNode extends ElementNode
{
    private const string TYPE = 'image';
    private ?string $src;
    private ?string $altText;
    private ?int $width;
    private ?int $height;

    public function __construct(array $data)
    {
        $this->src     = $data['src'] ?? null;
        $this->altText = $data['altText'] ?? null;
        $this->width   = $data['width'] ?? null;
        $this->height  = $data['height'] ?? null;
        $this->style   = new Style($data, self::TYPE);
    }

    public function accept(RendererInterface $visitor) : string
    {
        return $visitor->visitImage($this);
    }

    public function getSrc() : ?string
    {
        return $this->src;
    }

    public function getAltText() : ?string
    {
        return $this->altText;
    }

    public function getWidth() : ?int
    {
        return $this->width;
    }

    public function getHeight() : ?int
    {
        return $this->height;
    }

    public function getType() : string
    {
        return self::TYPE;
    }
}
