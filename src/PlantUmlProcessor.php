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

        list($result->classes, $xmiIdIndex) = self::buildClasses($classes);
        $result->relations = self::buildRelations($relations, $xmiIdIndex);

        return $result;
    }

    public static function serialize($schema): string
    {
        return '';
    }

    /**
     * @param \SimpleXMLElement[] $classes
     * @return array [ClassMetadata[], array]. First elements is a list of ClassMetadata objects, second 
     * is an associative array with the xmi.id as key and the index of the class in the list as value
     */
    private static function buildClasses($classesXml){
        $classes = [];
        $xmiIdIndex = [];

        $i = 0;
        foreach ($classesXml as $classXml) {
            $classMetadata = ClassMetadata::makeFromXmlElement($classXml);
            $classes[] = $classMetadata; 

            $xmiIdIndex[$classXml->attributes()->{"xmi.id"}->__toString()] = $i;
            $i++;
        }
        return [$classes, $xmiIdIndex];
    }

    /**
     * @param \SimpleXMLElement[] $relations
     * @return Relation[]
     */
    private static function buildRelations($relationsXml, $classIds){
        // print_r($relationsXml);
        $result = [];
        foreach ($relationsXml as $relationXml) {
            $relation = new Relation();
            $result[] = $relation;
        }
        return $result;
    }
}
