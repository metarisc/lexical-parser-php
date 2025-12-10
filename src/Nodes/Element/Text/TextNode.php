<?php

namespace App\Nodes\Element\Text;

use App\Nodes\Styles\Style;
use App\Nodes\NodeInterface;
use App\Renderrer\RenderrerInterface;

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

    public function accept(RenderrerInterface $visitor) : string
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
