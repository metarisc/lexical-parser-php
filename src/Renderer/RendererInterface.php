<?php

namespace Metarisc\LexicalParser\Renderer;

use Metarisc\LexicalParser\Nodes\RootNode;
use Metarisc\LexicalParser\Nodes\LineBreakNode;
use Metarisc\LexicalParser\Nodes\PageBreakNode;
use Metarisc\LexicalParser\Nodes\Element\ImageNode;
use Metarisc\LexicalParser\Nodes\Element\HeadingNode;
use Metarisc\LexicalParser\Nodes\Element\List\ListNode;
use Metarisc\LexicalParser\Nodes\Element\ParagraphNode;
use Metarisc\LexicalParser\Nodes\Element\Text\LinkNode;
use Metarisc\LexicalParser\Nodes\Element\Text\TextNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableNode;
use Metarisc\LexicalParser\Nodes\Element\List\ListItemNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableRowNode;
use Metarisc\LexicalParser\Nodes\Element\Table\TableCellNode;

/**
 * Interface pour le pattern Visitor
 * Permet de parcourir et traiter l'arbre de nodes Lexical.
 */
interface RendererInterface
{
    public function visitRoot(RootNode $node) : string;

    public function visitParagraph(ParagraphNode $node) : string;

    public function visitHeading(HeadingNode $node) : string;

    public function visitText(TextNode $node) : string;

    public function visitLink(LinkNode $node) : string;

    public function visitList(ListNode $node) : string;

    public function visitListItem(ListItemNode $node) : string;

    public function visitTable(TableNode $node) : string;

    public function visitTableRow(TableRowNode $node) : string;

    public function visitTableCell(TableCellNode $node) : string;

    public function visitImage(ImageNode $node) : string;

    public function visitLineBreak(LineBreakNode $node) : string;

    public function visitPageBreak(PageBreakNode $node) : string;
}
