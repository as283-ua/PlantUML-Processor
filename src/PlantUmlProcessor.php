<?php

namespace As283\PlantUmlProcessor;

use As283\PlantUmlProcessor\Model\Schema;

function parse(string $schema): Schema | null
{
    $command = "echo " . $schema . " | java -jar " . __DIR__ . "/../tools/plantuml-1.2024.0.jar -xmi:star -p";

    $output = [];
    $exit_status = 0;
    exec($command, $output, $exit_status);

    $outputStr = array_reduce($output, function($carry, $item) {
        return $carry . $item."\n";
    }, "");

    if ($exit_status != 0) {
        return null;
    }

    $xml = simplexml_load_string($outputStr);
    print_r($xml);
    return new Schema();
}

function serialize($schema): string
{
    return '';
}