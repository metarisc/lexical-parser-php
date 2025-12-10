<?php

namespace Metarisc\LexicalParser\Nodes\Element;

use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\NodeInterface;
use Metarisc\LexicalParser\Renderer\RendererInterface;

/**
 * Classe de base abstraite pour tous les nodes Lexical
 * Stocke les données brutes du JSON et fournit des accesseurs.
 */
abstract class ElementNode implements NodeInterface
{
    protected array $children = [];
    protected Style $style;

    /**
     * Accepte un visitor (pattern Visitor).
     */
    abstract public function accept(RendererInterface $visitor) : string;

    /**
     * Retourne les enfants du node.
     *
     * @return NodeInterface[]
     */
    public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * Définit les enfants du node.
     *
     * @param NodeInterface[] $children
     */
    public function setChildren(array $children) : void
    {
        $this->children = $children;
    }

    /**
     * Ajoute un enfant au node.
     */
    public function addChild(NodeInterface $child) : void
    {
        $this->children[] = $child;
    }

    /**
     * Vérifie si le node a des enfants.
     */
    public function hasChildren() : bool
    {
        return \count($this->children) > 0;
    }

    /**
     * Retourne le style du node.
     */
    public function getStyle() : Style
    {
        return $this->style;
    }
}
