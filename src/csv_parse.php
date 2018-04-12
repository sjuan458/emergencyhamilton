<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);

$inputFilename    = $_REQUEST['inputFile'];
$outputFilename   = $_REQUEST['outputFile'];

// Open csv to read
$inputFile  = fopen($inputFilename, 'rt');

// Get the headers of the file
$headers = fgetcsv($inputFile);

// Create a new dom document with pretty formatting
$doc  = new DomDocument();
$doc->formatOutput   = true;

// Add a root node to the document
$root = $doc->createElement('rows');
$root = $doc->appendChild($root);

// Loop through each row creating a <row> node with the correct data
while (($row = fgetcsv($inputFile)) !== FALSE)
{
    $container = $doc->createElement('row');
    foreach($headers as $i => $header)
    {
        $child = $doc->createElement($header);
        $child = $container->appendChild($child);
        $value = $doc->createTextNode($row[$i]);
        $value = $child->appendChild($value);
    }

    $root->appendChild($container);
}

$strxml = $doc->saveXML();
$handle = fopen($outputFilename, "w");
fwrite($handle, $strxml);
fclose($handle);
?>