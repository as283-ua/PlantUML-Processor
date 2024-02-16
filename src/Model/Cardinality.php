<?php

namespace As283\PlantUmlProcessor\Model;

enum Cardinality {
    case ZeroOrOne;
    case One;
    case AtLeastOne;
    case Any;
}