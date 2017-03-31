<?php
/**
 * Created by PhpStorm.
 * User: josue
 * Date: 3/30/17
 * Time: 9:43 PM
 */
require_once "src/BiHashTable.php";
require_once "src/SupportedGeometries/Edge.php";
require_once "src/SupportedGeometries/Point.php";

use SupportedGeometries\Edge;
use SupportedGeometries\Point;


$b = new BiHashTable(1);

$b->addElement(new Edge(new Point(0.5, 0.5), new Point(2.5, 0.5), 100));
$b->addElement(new Edge(new Point(2.5, 0.5), new Point(0.5, 0.5), 200));

// horizontal
$b->outputHashTable();

echo "\n";


$b = new BiHashTable(1);

$b->addElement(new Edge(new Point( 0.5, 0.5), new Point(0.5, 2.5), 100));
$b->addElement(new Edge(new Point(0.5, 2.5), new Point(0.5, 0.5), 200));

// vertical
$b->outputHashTable();

echo "\n";


$b = new BiHashTable(1);

$b->addElement(new Edge(new Point( 0.5, 0.5), new Point(1.5, 1), 100));
$b->addElement(new Edge(new Point( 0.5, 0.75), new Point(1.25, 1.5), 200));

// tricky one
$b->outputHashTable();

echo "\n";


$b = new BiHashTable(1);

$b->addElement(new Edge(new Point(1.5, 1), new Point( 0.5, 0.5), 100));
$b->addElement(new Edge(new Point(1.25, 1.5), new Point( 0.5, 0.75), 200));
$b->addElement(new Edge(new Point(1.75, 1.75), new Point( 1.75, 1.25), 300));
$b->addElement(new Point(0.5, 0.5, 400));

// inverted tricky one
$b->outputHashTable();

echo "\n";

var_dump($b->getAllElementsInCircle(new Point(0,0)));

var_dump($b->getAllElementsInCircle(new Point(2,0)));

var_dump($b->getAllElementsInCircle(new Point(2,1)));

var_dump($b->getAllElementsInCircle(new Point(2,2)));


