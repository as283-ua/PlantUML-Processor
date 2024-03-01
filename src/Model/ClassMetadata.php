<?php

namespace As283\PlantUmlProcessor\Model;

class ClassMetadata
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var Field[]
     */
    public $fields = [];

    /**
     * @var Method[]
     */
    public $methods = [];

    /**
     * Indexes of the relations containing this class.
     * The key is the index of the relation in the schema, the value is the name of the related class.
     * @var array<int,string>
     */
    public $relationIndexes = [];

    public function __construct(){}

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
            $classMetadata->fields[] = Field::makeFromXmlElement($field);
        }

        //todo: implement for methods
        $methods = $xmlClass->{"Classifier.feature"}->Operation;
        $classMetadata->methods = [];

        
        return $classMetadata;
    }
}