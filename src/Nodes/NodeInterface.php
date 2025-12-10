<?php

namespace Metarisc\LexicalParser\Nodes;

use Metarisc\LexicalParser\Renderer\RendererInterface;

interface NodeInterface
{
    /**
     * Accepte un visitor (pattern Visitor).
     */
    public function accept(RendererInterface $visitor) : string;

    /**
     * Retourne le type du node.
     */
    public function getType() : string;
}
