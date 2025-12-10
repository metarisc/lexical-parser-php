<?php

require __DIR__.'/../vendor/autoload.php';

use PhpZip\ZipFile;
use Metarisc\LexicalParser\Converter\Converter;

// Ensure the file path is correct
const odtPath = __DIR__.'/../tests/template.odt';
const astPath = __DIR__.'/../docs/lexical_ast_pv.json';
const rawPath = __DIR__.'/../tests/raw.xml';

if (!file_exists(odtPath)) {
    throw new RuntimeException('File not found: '.odtPath);
}
if (!file_exists(astPath)) {
    throw new RuntimeException('File not found: '.astPath);
}
if (!file_exists(rawPath)) {
    throw new RuntimeException('File not found: '.rawPath);
}

// Load the ODT, AST, and raw XML files
$odt      = (new ZipFile())->openFile(odtPath);
$ast_json = file_get_contents(astPath);
$raw_xml  = file_get_contents(rawPath);

$config   = ['output_format' => 'odt'];

$converter   = new Converter();
$content     = $converter->convert($ast_json, $config);

// Injecter le contenu dans raw.xml
$raw_xml = str_replace(
    'replace_string',
    $content,
    $raw_xml
);

// Get the content of the ODT file
$content = $odt->getEntryContents('content.xml');
if (false === $content) {
    throw new RuntimeException('Failed to read content.xml from ODT file.');
}

$odt->addFromString('content.xml', $raw_xml);

$datetime = (new DateTime())->format(\DATE_ATOM);
$odt->saveAsFile(__DIR__.'/../tests/output'.$datetime.'.odt')->close();
