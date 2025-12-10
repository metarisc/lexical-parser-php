<?php

namespace App\Parser;

use App\Nodes\RootNode;
use App\Nodes\LineBreakNode;
use App\Nodes\NodeInterface;
use App\Nodes\Element\ImageNode;
use App\Nodes\Element\HeadingNode;
use App\Nodes\Element\List\ListNode;
use App\Nodes\Element\ParagraphNode;
use App\Nodes\Element\Text\LinkNode;
use App\Nodes\Element\Text\TextNode;
use App\Renderrer\RenderrerInterface;
use App\Nodes\Element\Table\TableNode;
use App\Nodes\Element\List\ListItemNode;
use App\Nodes\Element\Table\TableRowNode;
use App\Nodes\Element\Table\TableCellNode;

/**
 * Parser pour convertir du JSON Lexical en arbre de Nodes PHP
 * et les parcourir avec un Visitor.
 */
final class LexicalParser implements ParserInterface
{
    private RenderrerInterface $renderrer;

    public function setRenderrer(RenderrerInterface $renderrer) : void
    {
        $this->renderrer = $renderrer;
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
        $content  = $rootNode->accept($this->renderrer);

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
            default => throw new \InvalidArgumentException("Type de node non supporté : {$type}"),
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
