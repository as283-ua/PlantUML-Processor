<?php

namespace As283\PlantUmlProcessor\Model;

class Relation
{
    /**
     * @var array{0: string, 1: Multiplicity} First element is the name of the class 
     * where the relation starts, second element is the multiplicity. The multiplicity 
     * refers to a relationship of "has". Class "foo" has x of other
     * @example ["User", Multiplicity::One] Class "User" is related to only one of the other opposite class, Role for example
     */
    public $from;

    /**
     * @var array{0: string, 1: Multiplicity} First element is the name of the class 
     * where the relation ends, second element is the multiplicity. The multiplicity 
     * refers to a relationship of "has". Class "foo" has x of other
     * @example ["Role", Multiplicity::Any] Class "Role" is related to any number of the opposite class, User for example
     */
    public $to;
    public RelationType $type;

    public function __construct()
    {
    }

    /**
     * @param \SimpleXMLElement $xmlRelation
     * @param array[string]int $xmiIdIndex
     * @return Relation
     */
    public static function makeFromXmlElement($xmlRelation, $xmiIdClassName)
    {
        $relation = new Relation();

        $left = $xmlRelation->{"Association.connection"}->AssociationEnd[0];
        $right = $xmlRelation->{"Association.connection"}->AssociationEnd[1];

        $relation->from = [
            $xmiIdClassName[$left->attributes()->type->__toString()],
            Multiplicity::fromString($left->attributes()->name?->__toString())
        ];

        $relation->to = [
            $xmiIdClassName[$right->attributes()->type->__toString()],
            Multiplicity::fromString($right->attributes()->name?->__toString())
        ];

        $relation->type = RelationType::Association;

        return $relation;
    }

    public function __toString()
    {
        return $this->from[0] . " \"" . $this->from[1]->toString() . "\" " . $this->type->toString() . " \"" . $this->to[1]->toString() . "\" " . $this->to[0] . "\n";
    }
}
