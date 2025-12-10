<?php

namespace Metarisc\LexicalParser\Converter;

interface ConverterInterface
{
    public function convert(string $json_ast, array $config) : string;
}
