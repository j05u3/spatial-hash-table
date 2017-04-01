
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


var_dump($b->getAllElementsInCircle(new Point(0,0)));
/**
array(3) {
  [100]=>
  object(SpatialHashTable\SupportedGeometries\Edge)#3 (3) {
    ["id"]=>
    int(100)
    ["p1"]=>
    object(SpatialHashTable\SupportedGeometries\Point)#10 (3) {
      ["id"]=>
      int(0)
      ["x"]=>
      float(-1.5)
      ["y"]=>
      float(-1)
    }
    ["p2"]=>
    object(SpatialHashTable\SupportedGeometries\Point)#5 (3) {
      ["id"]=>
      int(0)
      ["x"]=>
      float(-0.5)
      ["y"]=>
      float(-0.5)
    }
  }
  [200]=>
  object(SpatialHashTable\SupportedGeometries\Edge)#9 (3) {
    ["id"]=>
    int(200)
    ["p1"]=>
    object(SpatialHashTable\SupportedGeometries\Point)#8 (3) {
      ["id"]=>
      int(0)
      ["x"]=>
      float(1.25)
      ["y"]=>
      float(1.5)
    }
    ["p2"]=>
    object(SpatialHashTable\SupportedGeometries\Point)#4 (3) {
      ["id"]=>
      int(0)
      ["x"]=>
      float(0.5)
      ["y"]=>
      float(0.75)
    }
  }
  [400]=>
  object(SpatialHashTable\SupportedGeometries\Point)#12 (3) {
    ["id"]=>
    int(400)
    ["x"]=>
    float(0.5)
    ["y"]=>
    float(0.5)
  }
}

**/

```