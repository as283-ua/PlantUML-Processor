<?php

namespace As283\PlantUmlProcessor\Model;

enum RelationType {
    case Association;
    case Aggregation;
    case Composition;
    case Inheritance;
    case Realization;
    case Dependency;
    case Use;
}