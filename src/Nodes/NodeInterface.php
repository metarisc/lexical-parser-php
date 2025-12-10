<?php

namespace App\Nodes;

use App\Renderrer\RenderrerInterface;

interface NodeInterface
{
    /**
     * Accepte un visitor (pattern Visitor).
     */
    public function accept(RenderrerInterface $visitor) : string;

    /**
     * Retourne le type du node.
     */
    public function getType() : string;
}
