<?php

namespace As283\PlantUmlProcessor\Model;

class Schema 
{
    /**
     * @var Relation[]
     */
    public $relations;

    /**
     * Key is the class name and value is the ClassMetadata
     * @var array<string,ClassMetadata>
     */
    public $classes;

    public function __construct(){}

    /**
     * @param ClassMetadata[] $classes
     * @param Relation[] $relations
     * @return Schema
     */
    public static function create($classes, $relations)
    {
        $schema = new Schema();
        $schema->classes = $classes;
        $schema->relations = $relations;

        foreach ($schema->classes as $classname => $class) {
            $i = 0;
            foreach ($schema->relations as $relation) {
                if($relation->from[0] === $classname){
                    $class->relationIndexes[$i] = $relation->to[0];
                } else if($relation->to[0] === $classname){
                    $class->relationIndexes[$i] = $relation->from[0];
                }
                
                $i++;
            }
        }
        return $schema;
    }
}