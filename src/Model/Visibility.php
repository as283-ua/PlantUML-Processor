<?php

namespace As283\PlantUmlProcessor\Model;

enum Visibility {
    case Public;
    case Protected;
    case Private;
    case Package;
}