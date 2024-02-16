<?php

namespace As283\PlantUmlProcessor\Model;

class Relation{
    /**
     * @var array{0: string, 1: Cardinality} First element is the name of the class where the relation starts, second element is the cardinality
     * @example ["User", Cardinality::One] Class "User" is related to only one of the other opposite class, Role for example
     */
    public $from;

    /**
     * @var array{0: string, 1: Cardinality} First element is the name of the class where the relation ends, second element is the cardinality
     * @example ["Role", Cardinality::Any] Class "Role" is related to any number of the opposite class, User for example
     */
    public $to;
    public RelationType $type;
}