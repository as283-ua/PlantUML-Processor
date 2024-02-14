<?php

namespace As283\PlantUmlProcessor\Model;

class Relation{
    /**
     * @var array{0: ClassMetadata, 1: Cardinality}
     */
    public $from;

    /**
     * @var array{0: ClassMetadata, 1: Cardinality}
     */
    public $to;
    public RelationType $type;
}