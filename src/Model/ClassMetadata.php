<?php

namespace As283\PlantUmlProcessor\Model;

class ClassMetadata
{
    public string $name;
    public array $fields;
    public array $methods;

    public function __construct()
    {
        $this->fields = [];
        $this->methods = [];
    }

    public static function make($name, $fields, $methods)
    {
        $classMetadata = new ClassMetadata();

        $classMetadata->name = $name;
        $classMetadata->fields = $fields;
        $classMetadata->methods = $methods;
    }

    /**
     * @param \SimpleXMLElement $xmlClass
     */
    public static function makeFromXmlElement($xmlClass)
    {
        $classMetadata = new ClassMetadata();

        // TODO. implement this

        $classMetadata->name = $xmlClass->attributes()->name;
        $fields = $xmlClass->{"Classifier.feature"}->Attribute;
        $classMetadata->fields = null;
        $methods = $xmlClass->{"Classifier.feature"}->Operation;
        $classMetadata->methods = null;
    }
}