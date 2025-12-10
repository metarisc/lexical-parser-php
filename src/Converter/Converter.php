<?php

namespace Metarisc\LexicalParser\Converter;

use Metarisc\LexicalParser\Parser\LexicalParser;
use Metarisc\LexicalParser\Renderer\OdtRenderer;
use Metarisc\LexicalParser\Parser\ParserInterface;

class Converter implements ConverterInterface
{
    private ParserInterface $parser;

    public function __construct()
    {
        $this->parser = new LexicalParser();
    }

    public function convert(string $json_ast, array $config) : string
    {
        match ($config['output_format']) {
            'odt' => $renderer   = new OdtRenderer(),
            default => $renderer = null,
        };

        if (!$renderer) {
            throw new \InvalidArgumentException('Unsupported output format: '.$config['output_format']);
        }

        $this->parser->setRenderer($renderer);

        $content = $this->parser->parseAndRender($json_ast);

        return $content;
    }
}
