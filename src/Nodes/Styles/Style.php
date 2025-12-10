<?php

namespace Metarisc\LexicalParser\Nodes\Styles;

class Style
{
    public ?Alignment $alignment   = null;
    /** @var ?TextFormat[] */
    public ?array $textFormats     = null;
    public ?ListFormat $listFormat = null;
    public string $color           = '';
    public string $backgroundColor = '';

    public function __construct(array $data = [], string $typeNode = '')
    {
        // Pour les paragraphes/headings, 'format' = alignment (left, center, right, justify)
        // Pour les text nodes, 'format' = bitwise flags (bold, italic, etc.)
        if (isset($data['format'])) {
            if ('text' === $typeNode) {
                // Pour les TextNode, format est un nombre (bitwise flags)
                $this->textFormats = TextFormat::parseTextFormatFlags((int) $data['format']);
            } else {
                // Pour les autres nodes (paragraph, heading), format est une chaîne (alignment)
                $this->alignment = Alignment::fromString($data['format']);
            }
        }

        // Support explicite de textFormat (au cas où c'est utilisé)
        if (isset($data['textFormat'])) {
            $this->textFormats = TextFormat::parseTextFormatFlags((int) $data['textFormat']);
        }

        if ('list' === $typeNode && isset($data['listType'])) {
            $this->listFormat = ListFormat::fromString($data['listType']);
        }

        if (isset($data['backgroundColor'])) {
            $this->backgroundColor = $data['backgroundColor'];
        }

        if (isset($data['textStyle'])) {
            $rawStyles = $data['textStyle'];
        }
        if (isset($data['style'])) {
            $rawStyles = $data['style'];
        }
        if (!empty($rawStyles)) {
            $styles = $this->parseStyleProperty($rawStyles);
            foreach ($styles as $item) {
                foreach ($item as $key => $value) {
                    if ('color' === $key) {
                        $this->color = $value;
                    } elseif ('background-color' === $key) {
                        $this->backgroundColor = $value;
                    }
                }
            }
        }
    }

    private function parseStyleProperty(string $value) : array
    {
        // Example: "color: #00000; background: #ffffff" -> ['color: #00000', 'background: #ffffff']
        $map = array_map('trim', explode(';', $value));

        // Example: ['color: #00000', 'background: #ffffff'] -> ['color' => '#00000', 'background' => '#ffffff']
        $parsed = array_map(function ($item) {
            $parts = explode(':', $item, 2);
            if (2 === \count($parts)) {
                return [trim($parts[0]) => trim($parts[1])];
            }

            return [];
        }, $map);

        return $parsed;
    }
}
