
## Installation

Using [Composer](https://getcomposer.org), just add it to your `composer.json` by running:

```
composer require josue/spatial-hash-table
```

## Usage

Example

![Alt Test case graph](docs/sample.png?raw=true "Test case")

```php
<?php

use SpatialHashTable\BiHashTable;
use SpatialHashTable\SupportedGeometries\Edge;
use SpatialHashTable\SupportedGeometries\Point;

$b = new BiHashTable(1);

$b->addElement(new Edge(new Point(-1.5, -1), new Point( -0.5, -0.5), 100));
$b->addElement(new Edge(new Point(1.25, 1.5), new Point( 0.5, 0.75), 200));
$b->addElement(new Edge(new Point(1.75, 1.75), new Point( 1.75, 1.25), 300));
$b->addElement(new Point(0.5, 0.5, 400));


echo json_encode($b->getAllElementsInCircle(new Point(0,0)));

```
And the output is: 

```json
{"100":{"id":100,"p1":{"id":0,"x":-1.5,"y":-1},"p2":{"id":0,"x":-0.5,"y":-0.5}},"200":{"id":200,"p1":{"id":0,"x":1.25,"y":1.5},"p2":{"id":0,"x":0.5,"y":0.75}},"400":{"id":400,"x":0.5,"y":0.5}}
```
