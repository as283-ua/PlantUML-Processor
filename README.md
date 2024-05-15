# PlantUML-Processor

Parse and serialize PlantUML in PHP.

This package has been developed for internal use in the [PlantUML command for Artisan](https://github.com/as283-ua/Artisan-PlantUML)

[![License](https://img.shields.io/badge/license-GPL-blue.svg)](LICENSE)
[![Latest Version](https://img.shields.io/github/v/release/as283-ua/PlantUML-Processor.svg)](https://github.com/as283-ua/PlantUML-Processor/releases)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://www.php.net/)
[![Java 1.8](https://img.shields.io/badge/Java-1.8-orange.svg)](https://www.oracle.com/java/technologies/javase-jdk8-downloads.html)

## Overview

This library provides an interface for processing PlantUML class diagrams with an in memory representation.

The purpose is to abstract class diagrams into more easily usable object-oriented types.

### Schema

Contains an associative array of classes (using their name as the key) and an array of relations.

### ClassMetadata

Representation of a class. Has a name, an array of fields and an associative array of related classes (using their name as the key and a list of indexes in a $schema->relations).

### Relation

Representation of a relation between classes. Has a field `from` and `to`. Both are arrays that are used as tuples, where the first element is the name of a class in the diagram, and the second element is of type `enum Multiplicity`, indicating how many of the other class this class is related to (for `from` it indicates if it has one or zero, one, any or at least one of the class in `to`). It also has a field `type` of type `enum RelationType`.

### Field

Representation of a single field in a class. Has name, type (object of type `enum Type` or null), visibility (object of type `enum Visibility` or null) and the modifiers unique, nullable and primary (bool).

## Installation

In your project:

```bash
$ composer require as283/plant-uml-processor
```
