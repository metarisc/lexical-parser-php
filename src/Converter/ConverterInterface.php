<?php

namespace App\Converter;

interface ConverterInterface
{
    public function convert(string $json_ast, array $config) : string;
}
