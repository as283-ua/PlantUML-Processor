<?php

namespace As283\PlantUmlProcessor\Model;

class Schema 
{
    /**
     * @var Relation[]
     */
    public $relations;

    /**
     * @var ClassMetadata[]
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

        foreach ($schema->classes as $class) {
            $i = 0;
            foreach ($schema->relations as $relation) {
                if($relation->from[0] === $class->name && $relation->to[0] === $class->name){
                    $class->relationIndexes[$i] = Origin::SelfAssociation;
                } else if($relation->from[0] === $class->name){
                    $class->relationIndexes[$i] = Origin::From;
                } else if($relation->to[0] === $class->name){
                    $class->relationIndexes[$i] = Origin::To;
                }
                
                $i++;
            }
        }
        return $schema;
    }
}