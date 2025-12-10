<?php

namespace Metarisc\LexicalParser\Renderer;

use Metarisc\LexicalParser\Odt;
use Metarisc\LexicalParser\Nodes\RootNode;
use Metarisc\LexicalParser\Nodes\Styles\Style;
use Metarisc\LexicalParser\Nodes\LineBreakNode;
use Metarisc\LexicalParser\Nodes\NodeInterface;
use Metarisc\LexicalParser\Nodes\Element\ImageNode;
use Metarisc\LexicalParser\Nodes\Styles\TextFormat;
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
 * Visitor pour convertir les nodes Lexical en fragments XML ODT
 * Génère uniquement le XML des éléments individuels (sans enveloppe document).
 */
final class OdtRenderer implements RendererInterface
{
    private Odt $odt;
    /** @var array<string, array<string, mixed>>
     * Avec Oasis on est obligés de définir des styles automatiques pour appliquer des styles de texte (couleur, gras, italique, etc.)
     */
    private array $automaticStyles = [];

    /** @var int Compteur pour générer des noms de style uniques */
    private int $styleCounter = 0;

    public function __construct()
    {
        $this->odt = new Odt();
    }

    /**
     * Récupère les définitions de styles automatiques générées.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getAutomaticStyles() : array
    {
        return $this->automaticStyles;
    }

    public function visitRoot(RootNode $node) : string
    {
        $content = '';
        // Pour le root, on parcourt juste les enfants sans ajouter de balise
        foreach ($node->getChildren() as $child) {
            // Visiter uniquement ces nodes au niveau racine par rapport aux enfants.
            // Ce sont ces nodes qui vont les gérer et inclure leurs enfants (text, link, etc.)
            if (
                $child instanceof ParagraphNode
                || $child instanceof HeadingNode
                || $child instanceof ListNode
                || $child instanceof TableNode
            ) {
                $content .= $child->accept($this);
            }
        }
        $xml = '<office:body><office:text>'.$content.'</office:text></office:body>';
        $this->odt->addAutomaticStyles($this->automaticStyles);
        $this->odt->addNodeToXml($xml);

        return $this->odt->getContentXml();
    }

    public function visitParagraph(ParagraphNode $node) : string
    {
        // Construire le contenu du paragraphe en visitant les enfants
        $content = $this->traverseChildren($node->getChildren());

        // Récupérer le style du paragraphe
        $style = $node->getStyle();

        // Si le paragraphe a un alignement, créer un style et l'appliquer
        if ($this->hasParagraphStyle($style)) {
            $styleName = $this->generateParagraphStyle($style);

            return '<text:p text:style-name="'.$styleName.'">'.$content.'</text:p>';
        }

        // Créer le paragraphe avec son contenu
        $paragraph = '<text:p>'.$content.'</text:p>';

        return $paragraph;
    }

    public function visitHeading(HeadingNode $node) : string
    {
        $tag = $node->getTag() ?? 'h1';

        // Extraire le niveau du heading (h1 -> 1, h2 -> 2, etc.)
        $level = (int) preg_replace('/[^0-9]/', '', $tag);
        if ($level < 1) {
            $level = 1;
        }
        if ($level > 6) {
            $level = 6;
        }

        // Construire le contenu du heading en visitant les enfants
        $content = $this->traverseChildren($node->getChildren());

        // Récupérer le style du heading
        $style = $node->getStyle();

        // Si le heading a un alignement, créer un style et l'appliquer
        if ($this->hasParagraphStyle($style)) {
            $styleName = $this->generateParagraphStyle($style, 'heading', $level);

            return '<text:h text:outline-level="'.$level.'" text:style-name="'.$styleName.'">'.$content.'</text:h>';
        }

        // Créer le heading avec son niveau
        $heading = '<text:h text:outline-level="'.$level.'">'.$content.'</text:h>';

        return $heading;
    }

    public function visitText(TextNode $node) : string
    {
        $text = $node->getText() ?? '';

        // Échapper le texte pour XML
        $escapedText = htmlspecialchars($text, \ENT_XML1 | \ENT_QUOTES, 'UTF-8');

        // Récupérer le style du texte
        $style = $node->getStyle();

        // Si le texte a des formats ou des couleurs, créer un style et l'appliquer
        if ($this->hasTextStyle($style)) {
            $styleName = $this->generateTextStyle($style);

            return '<text:span text:style-name="'.$styleName.'">'.$escapedText.'</text:span>';
        }

        // Texte simple sans formatage
        return $escapedText;
    }

    public function visitLink(LinkNode $node) : string
    {
        $url = $node->getUrl() ?? '';

        // Construire le contenu du lien en visitant les enfants
        $content = '';
        foreach ($node->getChildren() as $child) {
            $content .= $child->accept($this);
        }

        // Créer le lien avec son URL et son contenu
        $link = '<text:a xlink:href="'.htmlspecialchars($url, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'">'.$content.'</text:a>';

        return $link;
    }

    public function visitList(ListNode $node) : string
    {
        // Construire les items de la list en visitant les enfants
        $content = $this->traverseChildren($node->getChildren());

        // Créer la list avec ses items
        $list = '<text:list>'.$content.'</text:list>';

        return $list;
    }

    public function visitListItem(ListItemNode $node) : string
    {
        // Construire le contenu du listItem en visitant les enfants
        $content = '';
        foreach ($node->getChildren() as $child) {
            if ($child instanceof TextNode) {
                $content .= '<text:p>'.htmlspecialchars($child->getText() ?? '', \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'</text:p>';
            } else {
                $content .= $child->accept($this);
            }
        }

        // Créer le listItem avec son contenu
        $listItem = '<text:list-item>'.$content.'</text:list-item>';

        return $listItem;
    }

    public function visitImage(ImageNode $node) : string
    {
        $src    = $node->getSrc() ?? '';
        $alt    = $node->getAltText() ?? '';
        $width  = $node->getWidth() ?? 0;
        $height = $node->getHeight() ?? 0;

        $image = '<draw:frame text:anchor="aschar" svg:width="'.$width * 10 .'" svg:height="'.$height * 10 .'">
                    <draw:image xlink:href="'.htmlspecialchars($src, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'"/>
                </draw:frame>';

        return $image;
    }

    public function visitTable(TableNode $node) : string
    {
        $content = $this->traverseChildren($node->getChildren());
        $count   = 1;

        /** @var TableRowNode $row */
        foreach ($node->getChildren() as $row) {
            $cellCount = \count($row->getChildren());
            if ($cellCount > $count) {
                $count = $cellCount;
            }
        }

        $table = '<table:table table:name="Table1" table:style-name="Table1">
        <table:table-column table:style-name="Table1.A" table:number-columns-repeated="'.$count.'" />'.$content.'</table:table>';

        return $table;
    }

    public function visitTableRow(TableRowNode $node) : string
    {
        $content  = $this->traverseChildren($node->getChildren());
        $tableRow = '<table:table-row>'.$content.'</table:table-row>';

        return $tableRow;
    }

    public function visitTableCell(TableCellNode $node) : string
    {
        $content = $this->traverseChildren($node->getChildren());

        // Construire les attributs de la cellule
        $attributes = 'office:value-type="string"';

        // Ajouter colSpan si > 1
        $colSpan = $node->getColSpan();
        if ($colSpan && $colSpan > 1) {
            $attributes .= ' table:number-columns-spanned="'.$colSpan.'"';
        }

        // Ajouter rowSpan si > 1
        $rowSpan = $node->getRowSpan();
        if ($rowSpan && $rowSpan > 1) {
            $attributes .= ' table:number-rows-spanned="'.$rowSpan.'"';
        }

        // Si la cellule est vide mais qu'elle a des fusions, on doit ajouter au moins un paragraphe
        if (empty($content)) {
            $content = '<text:p/>';
        }

        $tableCell = '<table:table-cell table:style-name="Table1.A1" '.$attributes.'>'.$content.'</table:table-cell>';

        return $tableCell;
    }

    public function visitLineBreak(LineBreakNode $node) : string
    {
        return '<text:line-break />';
    }

    /** @param array<NodeInterface> $nodes */
    private function traverseChildren(array $nodes) : string
    {
        $content = '';
        foreach ($nodes as $child) {
            $content .= $child->accept($this);
        }

        return $content;
    }

    /**
     * Vérifie si un style de texte contient des propriétés de formatage.
     */
    private function hasTextStyle(?Style $style) : bool
    {
        if (null === $style) {
            return false;
        }

        return !empty($style->textFormats)
            || !empty($style->color)
            || !empty($style->backgroundColor);
    }

    /**
     * Génère un style automatique pour du texte et retourne son nom.
     */
    private function generateTextStyle(Style $style) : string
    {
        // Créer une signature unique basée sur les propriétés du style
        $signature = $this->getStyleSignature($style);

        // Si ce style existe déjà, retourner son nom
        if (isset($this->automaticStyles[$signature])) {
            return $this->automaticStyles[$signature]['name'];
        }

        // Générer un nouveau nom de style
        $styleName = 'T'.$this->styleCounter++;

        // Construire les propriétés du style
        $properties = [];

        if (!empty($style->textFormats)) {
            foreach ($style->textFormats as $format) {
                if (TextFormat::BOLD === $format) {
                    $properties['fo:font-weight'] = 'bold';
                } elseif (TextFormat::ITALIC === $format) {
                    $properties['fo:font-style'] = 'italic';
                } elseif (TextFormat::UNDERLINE === $format) {
                    $properties['style:text-underline-style'] = 'solid';
                    $properties['style:text-underline-width'] = 'auto';
                    $properties['style:text-underline-color'] = 'font-color';
                } elseif (TextFormat::STRIKETHROUGH === $format) {
                    $properties['style:text-line-through-style'] = 'solid';
                    $properties['style:text-line-through-type']  = 'single';
                }
            }
        }

        if (!empty($style->color)) {
            $properties['fo:color'] = $style->color;
        }

        if (!empty($style->backgroundColor)) {
            $properties['fo:background-color'] = $style->backgroundColor;
        }

        // Stocker le style
        $this->automaticStyles[$signature] = [
            'name' => $styleName,
            'family' => 'text',
            'properties' => $properties,
        ];

        return $styleName;
    }

    /**
     * Crée une signature unique pour un style basée sur ses propriétés.
     */
    private function getStyleSignature(Style $style) : string
    {
        $parts = [];

        if (!empty($style->textFormats)) {
            $formats = array_map(fn ($f) => $f->value, $style->textFormats);
            sort($formats);
            $parts[] = 'f:'.implode(',', $formats);
        }

        if (!empty($style->color)) {
            $parts[] = 'c:'.$style->color;
        }

        if (!empty($style->backgroundColor)) {
            $parts[] = 'bg:'.$style->backgroundColor;
        }

        return implode('|', $parts);
    }

    /**
     * Vérifie si un style de paragraphe contient des propriétés de formatage.
     */
    private function hasParagraphStyle(?Style $style) : bool
    {
        if (null === $style) {
            return false;
        }

        return null !== $style->alignment;
    }

    /**
     * Génère un style automatique pour un paragraphe/heading et retourne son nom.
     */
    private function generateParagraphStyle(Style $style, string $type = 'paragraph', int $level = 0) : string
    {
        // Créer une signature unique basée sur les propriétés du style
        $signature = 'para:'.$type.':'.$level.':'.$this->getParagraphStyleSignature($style);

        // Si ce style existe déjà, retourner son nom
        if (isset($this->automaticStyles[$signature])) {
            return $this->automaticStyles[$signature]['name'];
        }

        // Générer un nouveau nom de style
        $styleName = 'P'.$this->styleCounter++;

        // Construire les propriétés du style
        $properties = [];

        if (null !== $style->alignment) {
            $properties['fo:text-align'] = $style->alignment->value;
        }

        // Pour les headings, utiliser "Heading" comme parent style de base
        $parentStyle = null;
        if ('heading' === $type) {
            $parentStyle = 'Heading';
        }

        // Stocker le style
        $this->automaticStyles[$signature] = [
            'name' => $styleName,
            'family' => 'paragraph',
            'parent' => $parentStyle,
            'properties' => $properties,
        ];

        return $styleName;
    }

    /**
     * Crée une signature unique pour un style de paragraphe basée sur ses propriétés.
     */
    private function getParagraphStyleSignature(Style $style) : string
    {
        $parts = [];

        if (null !== $style->alignment) {
            $parts[] = 'align:'.$style->alignment->value;
        }

        return implode('|', $parts);
    }
}
