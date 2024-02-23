<?php

namespace As283\PlantUmlProcessor\Model;

class ClassMetadata
{
    public string $name;
    /**
     * @var Field[]
     */
    public $fields;
    /**
     * @var Method[]
     */
    public $methods;

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
     * @return ClassMetadata
     */
    public static function makeFromXmlElement($xmlClass)
    {
        $classMetadata = new ClassMetadata();

        // TODO. implement this

        $classMetadata->name = $xmlClass->attributes()->name;

        $classMetadata->fields = [];
        $fields = $xmlClass->{"Classifier.feature"}->Attribute;

        foreach ($fields as $field) {
            print_r($field);
            $classMetadata->fields[] = Field::makeFromXmlElement($field);
        }

        //todo: implement for methods
        $methods = $xmlClass->{"Classifier.feature"}->Operation;
        $classMetadata->methods = [];

        
        return $classMetadata;
    }
}