<?php

namespace Metarisc\LexicalParser\Parser;

use Metarisc\LexicalParser\Renderer\RendererInterface;

interface ParserInterface
{
    public function parseAndRender(string $json) : string;

    public function setRenderer(RendererInterface $renderer) : void;
}
