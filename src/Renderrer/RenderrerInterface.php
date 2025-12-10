<?php

namespace App\Renderrer;

use App\Nodes\RootNode;
use App\Nodes\LineBreakNode;
use App\Nodes\Element\ImageNode;
use App\Nodes\Element\HeadingNode;
use App\Nodes\Element\List\ListNode;
use App\Nodes\Element\ParagraphNode;
use App\Nodes\Element\Text\LinkNode;
use App\Nodes\Element\Text\TextNode;
use App\Nodes\Element\Table\TableNode;
use App\Nodes\Element\List\ListItemNode;
use App\Nodes\Element\Table\TableRowNode;
use App\Nodes\Element\Table\TableCellNode;

/**
 * Interface pour le pattern Visitor
 * Permet de parcourir et traiter l'arbre de nodes Lexical.
 */
interface RenderrerInterface
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
}
