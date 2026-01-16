<?php

namespace Metarisc\LexicalParser;

use PhpZip\ZipFile as Zip;

/**
 * Classe pour créer et manipuler des fichiers ODT (OpenDocument Text)
 * Permet d'ajouter du contenu XML et de générer un fichier ODT complet.
 */
final class Odt extends Zip
{
    private \DOMDocument $xml;

    public function __construct()
    {
        $this->xml               = new \DOMDocument();
        $this->xml->formatOutput = true;
    }

    /**
     * Ajoute un nœud XML au document ODT
     * Le XML doit être un fragment valide (paragraphe, titre, table, etc.).
     *
     * @param string $nodeXml Le XML du nœud à ajouter (ex: <text:p>...</text:p>)
     *
     * @throws \DOMException Si le XML est invalide
     */
    public function addNodeToXml(string $nodeXml) : void
    {
        // Créer un document temporaire pour parser le fragment avec les namespaces
        $wrappedXml = '<?xml version="1.0" encoding="UTF-8"?>'.
            '<root>'.
            $nodeXml.
            '</root>';

        $tempDoc = new \DOMDocument('1.0', 'UTF-8');
        if (!@$tempDoc->loadXML($wrappedXml)) {
            throw new \DOMException('Le XML fourni est invalide');
        }

        // Importer et ajouter tous les enfants du wrapper
        $root = $tempDoc->documentElement;
        foreach ($root->childNodes as $node) {
            $importedNode = $this->xml->importNode($node, true);
            $this->xml->appendChild($importedNode);
        }
    }

    /**
     * Retourne le contenu du content.xml actuel.
     */
    public function getContentXml() : string
    {
        return str_replace('<?xml version="1.0"?>', '', $this->xml->saveXML());
    }

    /**
     * Ajoute les styles automatiques au document XML.
     *
     * @param array $styles Tableau de styles automatiques
     */
    public function addAutomaticStyles(array $styles) : void
    {
        $styleXml = '<office:automatic-styles>';
        foreach ($styles as $styleData) {
            // Construire le XML du style
            $styleXml .= '<style:style style:name="'.$styleData['name'].'" style:family="'.$styleData['family'].'"';

            // Ajouter le parent style si défini (pour les headings)
            if (isset($styleData['parent']) && !empty($styleData['parent'])) {
                $styleXml .= ' style:parent-style-name="'.$styleData['parent'].'"';
            }

            $styleXml .= '>';

            // Déterminer le type de propriétés selon la famille
            if ('text' === $styleData['family']) {
                $styleXml .= '<style:text-properties';
                foreach ($styleData['properties'] as $prop => $value) {
                    $styleXml .= ' '.$prop.'="'.htmlspecialchars($value, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'"';
                }
                $styleXml .= '/>';
            } elseif ('paragraph' === $styleData['family']) {
                // Ajouter les propriétés de paragraphe
                $styleXml .= '<style:paragraph-properties';
                foreach ($styleData['properties'] as $prop => $value) {
                    $styleXml .= ' '.$prop.'="'.htmlspecialchars($value, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'"';
                }
                $styleXml .= '/>';
                
                // Ajouter les propriétés de texte si elles existent (pour les headings)
                if (isset($styleData['textProperties']) && !empty($styleData['textProperties'])) {
                    $styleXml .= '<style:text-properties';
                    foreach ($styleData['textProperties'] as $prop => $value) {
                        $styleXml .= ' '.$prop.'="'.htmlspecialchars($value, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'"';
                    }
                    $styleXml .= '/>';
                }
            } elseif ('table-cell' === $styleData['family']) {
                // Ajouter les propriétés de cellule de tableau
                $styleXml .= '<style:table-cell-properties';
                foreach ($styleData['properties'] as $prop => $value) {
                    $styleXml .= ' '.$prop.'="'.htmlspecialchars($value, \ENT_XML1 | \ENT_QUOTES, 'UTF-8').'"';
                }
                $styleXml .= '/>';
            }

            $styleXml .= '</style:style>';

            // Ajouter le style au document via la méthode existante
        }
        $styleXml .= '</office:automatic-styles>';
        $this->addNodeToXml($styleXml);
    }
}
