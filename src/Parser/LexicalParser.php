<?php

namespace Metarisc\LexicalParser\Parser;

use Metarisc\LexicalParser\Nodes\RootNode;
use Metarisc\LexicalParser\Nodes\LineBreakNode;
use Metarisc\LexicalParser\Nodes\NodeInterface;
use Metarisc\LexicalParser\Nodes\PageBreakNode;
use Metarisc\LexicalParser\Nodes\Element\ImageNode;
use Metarisc\LexicalParser\Nodes\Element\HeadingNode;
use Metarisc\LexicalParser\Renderer\RendererInterface;
use Metarisc\LexicalParser\Nodes\Element\List\ListNode;
use Metarisc\LexicalParser\Nodes\Element\ParagraphNode;
use Metarisc\LexicalParser\Nodes\Element\Text\LinkNode;
use Metarisc\LexicalParser\Nodes\Element\Text\TextNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableNode;
use Metarisc\LexicalParser\Nodes\Element\List\ListItemNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableRowNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableCellNode;

/**
 * Parser pour convertir du JSON Lexical en arbre de Nodes PHP
 * et les parcourir avec un Visitor.
 */
final class LexicalParser implements ParserInterface
{
    private RendererInterface $renderer;

    public function setRenderer(RendererInterface $renderer) : void
    {
        $this->renderer = $renderer;
    }

    /**
     * Parse un JSON et le parcourt avec le visitor.
     * Une fois parcouru, on retourne le résultat du rendu.
     *
     * @param string $json Le JSON Lexical
     */
    public function parseAndRender(string $json) : string
    {
        $rootNode = $this->parseJson($json);
        $content  = $rootNode->accept($this->renderer);

        return $content;
    }

    /**
     * Parse un JSON Lexical complet et retourne le RootNode.
     *
     * @param string $json Le JSON Lexical complet
     *
     * @return RootNode Le node racine
     *
     * @throws \InvalidArgumentException Si le JSON est invalide
     */
    private function parseJson(string $json) : RootNode
    {
        $data = json_decode($json, true);

        if (!isset($data['root'])) {
            throw new \InvalidArgumentException('Le JSON doit contenir un objet "root"');
        }

        $rootNode = $this->createFromArray($data['root']);

        if (!$rootNode instanceof RootNode) {
            throw new \InvalidArgumentException('Le noeud racine doit être de type "root"');
        }

        return $rootNode;
    }

    /**
     * Crée un Node à partir d'un tableau de données JSON.
     *
     * @param array $data Les données du node provenant du JSON Lexical
     *
     * @return NodeInterface Le node créé
     *
     * @throws \InvalidArgumentException Si le type de node n'est pas supporté
     */
    private function createFromArray(array $data) : NodeInterface
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('Le champ "type" est obligatoire pour créer un node');
        }

        $type = $data['type'];

        // Créer le node approprié selon le type
        $node = match ($type) {
            'root' => new RootNode(),
            'paragraph' => new ParagraphNode($data),
            'heading' => new HeadingNode($data),
            'text' => new TextNode($data),
            'link' => new LinkNode($data),
            'list' => new ListNode($data),
            'listitem' => new ListItemNode($data),
            'table' => new TableNode($data),
            'tablerow' => new TableRowNode($data),
            'tablecell' => new TableCellNode($data),
            'image' => new ImageNode($data),
            'linebreak' => new LineBreakNode(),
            'page-break' => new PageBreakNode(),
            // default => throw new \InvalidArgumentException("Type de node non supporté : {$type}"),
            default => new ParagraphNode([]),
        };

        // Traiter récursivement les enfants si présents
        if (isset($data['children']) && \is_array($data['children']) && \count($data['children']) > 0) {
            $children = [];
            foreach ($data['children'] as $childData) {
                $children[] = $this->createFromArray($childData);
            }
            $node->setChildren($children);
        }

        return $node;
    }
}
