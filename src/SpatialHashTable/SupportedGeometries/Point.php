<?php
/**
 * Created by PhpStorm.
 * User: josue
 * Date: 3/30/17
 * Time: 8:17 PM
 */

namespace SpatialHashTable\SupportedGeometries;


class Point
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var double
     */
    public $x;

    /**
     * @var double
     */
    public $y;

    public static $EPS = 1E-12;


    public function __construct($_x, $_y, $id = null)
    {
        $this->x = doubleval($_x);
        $this->y = doubleval($_y);

        $this->id = intval($id);
    }

    // AB x AC
    public static function cross(Point &$A, Point &$B, Point &$C) {
        return ($C->y - $A->y) * ($B->x - $A->x) - ($B->y - $A->y) * ($C->x - $A->x);
    }

    // checking if A, B, C are counter-clockwise (same as getting the norm of the cross product of AB and AC)
    public static function ccw(Point &$A, Point &$B, Point &$C) {
        return  self::cross($A, $B, $C) > 0;
    }


    /**
     * Checks if C is inside the rectangle whose opposite vertices are A and B
     * @param Point $A
     * @param Point $B
     * @param Point $C
     * @return bool
     */
    public static function inRectangle(Point &$A, Point &$B, Point &$C) {
        $lx = min($A->x, $B->x);
        $ly = min($A->y, $B->y);

        $ux = max($A->x, $B->x);
        $uy = max($A->y, $B->y);

        if ($C->x >= $lx && $C->x <= $ux && $C->y >= $ly && $C->y <= $uy) return true;
    }


    /**
     * @param Point $A
     * @param Point $B
     * @param Point $C
     * @return bool True if C lies on segment AB
     */
    public static function onSegment(Point &$A, Point &$B, Point &$C) {
        return abs(self::cross($A, $B, $C)) <= Point::$EPS && self::inRectangle($A, $B, $C);
    }

    /**
     * Returns true if segment AB intersects with segment CD
     * @param Point $A
     * @param Point $B
     * @param Point $C
     * @param Point $D
     * @return bool
     */
    public static function intersect(Point &$A, Point &$B, Point &$C, Point &$D) {

        if (self::onSegment($A, $B, $D)) return true;
        if (self::onSegment($A, $B, $C)) return true;
        if (self::onSegment($C, $D, $A)) return true;
        if (self::onSegment($C, $D, $B)) return true;

        return (Point::ccw($A,$C,$D) != Point::ccw($B,$C,$D)) && (Point::ccw($A,$B,$C) != Point::ccw($A,$B,$D));
    }

    public static function dist(Point &$A, Point &$B) {
        return hypot($A->x - $B->x, $A->y - $B->y);
    }

    public static function distToLine(Point &$A, Point &$B, Point &$P) {
        $distAB = self::dist($A, $B);
        if ($distAB == 0) return self::dist($A, $P);
        return abs(self::cross($A, $B, $P))/$distAB;
    }

    public static function distToSegment(Point &$A, Point &$B, Point &$P) {
        $da = self::dist($A, $P);
        $db = self::dist($B, $P);
        $mini = min($da, $db);
        $h = self::distToLine($A, $B, $P);

        $b = self::dist($A, $B);
        $maxHypot2 = $b*$b + $h*$h;
        if ($da*$da > $maxHypot2 || $db*$db > $maxHypot2) {
            return $mini;
        }

        return min($mini, $h);
    }
}