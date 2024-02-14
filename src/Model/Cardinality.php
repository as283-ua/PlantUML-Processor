<?php

namespace As283\PlantUmlProcessor\Model;

enum Cardinality {
    case OneToOne;
    case OneToMany;
    case ManyToOne;
    case ManyToMany;
}