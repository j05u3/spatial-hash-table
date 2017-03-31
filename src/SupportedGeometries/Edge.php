<?php
/**
 * Created by PhpStorm.
 * User: josue
 * Date: 3/30/17
 * Time: 8:13 PM
 */

namespace SpatialHashTable\SupportedGeometries;

class Edge
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Point
     */
    public $p1;

    /**
     * @var Point
     */
    public $p2;

    public function __construct(Point $_p1, Point $_p2, $id)
    {
        $this->p1 = $_p1;
        $this->p2 = $_p2;
        $this->id = intval($id);
    }
}