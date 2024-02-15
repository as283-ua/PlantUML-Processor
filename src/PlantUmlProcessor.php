<?php

namespace As283\PlantUmlProcessor;

use As283\PlantUmlProcessor\Model\Schema;
use As283\PlantUmlProcessor\Logic\Sanitizer;

function parse(string $schema): Schema | null
{
    // pass string to plantuml jar through stdin and capture stdout of xml. stderr is discarded
    $command = "echo " . escapeshellarg($schema) . " | java -jar " . __DIR__ . "/../tools/plantuml-1.2024.0.jar -xmi:star -p 2>/dev/null";

    $output = [];
    $exit_status = 0;
    exec($command, $output, $exit_status);

    $outputStr = array_reduce($output, function($carry, $item) {
        return $carry . $item."\n";
    }, "");

    if ($exit_status != 0) {
        return null;
    }

    $outputStr = Sanitizer::sanitize($outputStr);

    // echo $outputStr;

    $xml = simplexml_load_string($outputStr);

    // print_r($xml->content->Model->ownedElement);
    
    /**
     * @var SimpleXMLElement $data
     */
    $data = $xml->content->Model->ownedElement;
    $classes = $data->Class;
    $relations = $data->Association;

    foreach ($classes as $class) {
        print_r($class);
    }
    echo "\n";

    foreach ($relations as $relation) {
        print_r($relation);
    }

    return new Schema();
}

function serialize($schema): string
{
    return '';
}