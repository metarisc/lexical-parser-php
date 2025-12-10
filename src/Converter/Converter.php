<?php

namespace App\Converter;

use App\Parser\LexicalParser;
use App\Parser\ParserInterface;
use App\Renderrer\OdtRenderrer;

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
            'odt' => $renderrer   = new OdtRenderrer(),
            default => $renderrer = null,
        };

        if (!$renderrer) {
            throw new \InvalidArgumentException('Unsupported output format: '.$config['output_format']);
        }

        $this->parser->setRenderrer($renderrer);

        $content = $this->parser->parseAndRender($json_ast);

        return $content;
    }
}
