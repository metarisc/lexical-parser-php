<?php

namespace App\Nodes\Element;

use App\Nodes\Styles\Style;
use App\Renderrer\RenderrerInterface;

class HeadingNode extends ElementNode
{
    private const string TYPE = 'heading';
    private ?string $tag      = null;

    public function __construct(array $data = [])
    {
        $this->tag   = $data['tag'] ?? 'h1';
        $this->style = new Style($data, self::TYPE);
    }

    public function accept(RenderrerInterface $visitor) : string
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
