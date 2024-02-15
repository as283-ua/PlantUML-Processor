<?php

namespace As283\PlantUmlProcessor;

use As283\PlantUmlProcessor\Model\Schema;
use As283\PlantUmlProcessor\Logic\Sanitizer;
use As283\PlantUmlProcessor\Model\ClassMetadata;
use As283\PlantUmlProcessor\Model\Relation;

class PlantUmlProcessor{
    /**
     * @param string $schema String containing the plantuml schema
     * @return Schema|null Returns a Schema object if the parsing was successful, null otherwise
     */
    public static function parse(string $schema): Schema | null
    {
        /**
         * @var Schema $result
         */
        $result = null;

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

        $result = new Schema();

        $outputStr = Sanitizer::sanitize($outputStr);
        $xml = simplexml_load_string($outputStr);
        $data = $xml->content->Model->ownedElement;
        $classes = $data->Class;
        $relations = $data->Association;

        $result->classes = self::buildClasses($classes);
        $result->relations = self::buildRelations($relations, []);

        return $result;
    }

    public static function serialize($schema): string
    {
        return '';
    }

    /**
     * @param \SimpleXMLElement[] $classes
     * @return ClassMetadata[]
     */
    private static function buildClasses($classes){
        $result = [];
        foreach ($classes as $class) {
            $classMetadata = ClassMetadata::makeFromXmlElement($class);
            array_push($result, $classMetadata);
        }
        return $result;
    }

    /**
     * @param \SimpleXMLElement[] $relations
     * @return Relation[]
     */
    private static function buildRelations($relations, $classIds){
        $result = [];
        foreach ($relations as $relation) {
            $classMetadata = new Relation();
            array_push($result, $classMetadata);
        }
        return $result;
    }
}
