<?php

namespace App\Parser;

use App\Renderrer\RenderrerInterface;

interface ParserInterface
{
    public function parseAndRender(string $json) : string;

    public function setRenderrer(RenderrerInterface $renderrer) : void;
}
