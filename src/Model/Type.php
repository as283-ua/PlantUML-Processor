<?php

namespace As283\PlantUmlProcessor\Model;

// not complete. needs a lot more type support
enum Type
{
    case string;
    case int;
    case float;
    case bool;
    case double;
    case Date;
    case DateTime;
}