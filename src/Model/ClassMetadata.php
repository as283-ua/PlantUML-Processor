<?php

namespace As283\PlantUmlProcessor\Model;

use As283\PlantUmlProcessor\Exceptions\RepeatedFieldNameException;

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
     * Array of related classes with their respective indexes in schema->relations[].
     * The key is name of the related class, the value is the list of indexes for the relations in the schema.
     * @var array<string,int[]>
     */
    public $relatedClasses = [];

    public function __construct()
    {
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
     * @throws RepeatedFieldNameException
     */
    public static function makeFromXmlElement($xmlClass)
    {
        $classMetadata = new ClassMetadata();

        $classMetadata->name = $xmlClass->attributes()->name;

        $classMetadata->fields = [];
        $fields = $xmlClass->{"Classifier.feature"}->Attribute;

        $fieldNames = [];
        $i = 0;
        foreach ($fields as $field) {
            $classMetadata->fields[] = Field::makeFromXmlElement($field);
            if (array_search($classMetadata->fields[$i]->name, $fieldNames) !== false) {
                throw new RepeatedFieldNameException($classMetadata->fields[$i]->name);
            }
            $fieldNames[] = $classMetadata->fields[$i]->name;
            $i++;
        }

        $methods = $xmlClass->{"Classifier.feature"}->Operation;
        $classMetadata->methods = [];


        return $classMetadata;
    }

    public function __toString()
    {
        $result = "class " . $this->name . " {\n";
        foreach ($this->fields as $field) {
            $result .= "\t" . $field->__toString() . "\n";
        }
        $result .= "}";
        return $result;
    }
}
