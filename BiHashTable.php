<?php

use SupportedGeometries\Edge;
use SupportedGeometries\Point;

class BiHashTable
{
    /**
     * @var double
     */
    protected $len;

    protected $els = [];

    private $t = [];

    private $dx = [0, 0, 1, -1];
    private $dy = [1,-1, 0,  0];
    private $pc1 = [[0, 1], [0, 0], [1, 0], [0, 0]];
    private $pc2 = [[1, 1], [1, 0], [1, 1], [0, 1]];

    public function __construct($tileLength)
    {
        if ($tileLength <= 0) throw new Exception("Tile length must be positive");
        $this->len = doubleval($tileLength);
    }

    public function addElement($e)
    {
        if (($e instanceof Edge) || ($e instanceof Point) ) {
            if (!array_key_exists($e->id, $this->els)) {
                ($this->els)[$e->id] = $e;
            } else {
                throw new Exception("This id was already used");
            }

            if ($e instanceof Edge) {

                // start tile
                $tx = 0;
                $ty = 0;
                $this->getTileIndex($e->p1, $tx, $ty);

                $this->addIdToTile($tx, $ty, $e->id);

                $x = ($e->p1)->x;
                $y = ($e->p1)->y;

                $ex = ($e->p2)->x;
                $ey = ($e->p2)->y;


                // end tile
                $etx = 0;
                $ety = 0;
                $this->getTileIndex($e->p2, $etx, $ety);

                $compx = $x <= $ex;
                $compy = $y <= $ey;

                $difx = $ex - $x;
                $dify = $ey - $y;

                while (true) {
                    $nx = $x + (2*$compx - 1)*$this->len;
                    $py = null;
                    if ($difx != 0) {
                        $py = $y + ($this->len) / abs($difx) * $dify;
                        $xttx = 0;
                        $xtty = 0;
                        $this->getTileIndexFromXY($nx, $py, $xttx, $xtty);

                    }

                    $ny = $y + (2*$compy - 1)*$this->len;
                    $px = null;
                    if ($dify != 0) {
                        $px = $x + ($this->len) / abs($dify) * $difx;
                        $yttx = 0;
                        $ytty = 0;
                        $this->getTileIndexFromXY($px, $ny, $yttx, $ytty);
                    }

                    // manhattan distances
                    $dmx = null;
                    $dmy = null;
                    if ($py != null && ($compx == ($nx <= $ex)) ) {
                        $dmx = abs($xtty - $ty) + abs($xttx - $tx);
                    }

                    if ($px != null && ($compy == ($ny <= $ey))) {
                        $dmy = abs($ytty - $ty) + abs($yttx - $tx);
                    }

                    if ($dmx == null && $dmy == null) break;

                    $lx = $x;
                    $ly = $y;
                    if ($dmx == null || ($dmy != null && $dmy < $dmx)) {
                        $x = $px;
                        $y = $ny;
                        $dm = $dmy;
                    } else {
                        $x = $nx;
                        $y = $py;
                        $dm = $dmx;
                    }


                    if ($dm > 1) {
                        for ($j = 0; $j < 4; $j++) {
                            $mSeg = $this->getSideSegment($tx, $ty, $j);
                            $pStart = new Point($lx, $ly);
                            $pEnd = new Point($x, $y);
                            if (Point::intersect($mSeg->p1, $mSeg->p2, $pStart, $pEnd)) {
                                $this->addIdToTile($tx + ($this->dx)[$j], $ty + ($this->dy)[$j], $e->id);
                            }
                        }
                    }

                    $this->getTileIndexFromXY($x, $y, $tx, $ty);


                    // echo $x." ".$y."\n";
                    // echo $tx." ".$ty."\n";

                    $this->addIdToTile($tx, $ty, $e->id);
                }

                $dm = abs($tx - $etx) + abs($ty - $ety);
                if ($dm > 1) {
                    for ($j = 0; $j < 4; $j++) {
                        $mSeg = $this->getSideSegment($tx, $ty, $j);
                        $pStart = new Point($ex, $ey);
                        $pEnd = new Point($x, $y);
                        if (Point::intersect($mSeg->p1, $mSeg->p2, $pStart, $pEnd)) {
                            $this->addIdToTile($tx + ($this->dx)[$j], $ty + ($this->dy)[$j], $e->id);
                        }
                    }
                }

                $this->addIdToTile($etx, $ety, $e->id);

            }

        } else {
            throw new Exception("This type of element is not supported");
        }
    }

    private function getTileIndex(Point &$p, &$ox, &$oy) {
        $ox = floor((($p)->x)/$this->len);
        $oy = floor((($p)->y)/$this->len);
    }

    private function getTileIndexFromXY($nx, $ny, &$ox, &$oy) {
        $ox = floor(($nx)/$this->len);
        $oy = floor(($ny)/$this->len);
    }

    private function addIdToTile($tx, $ty, $id) {
        if (!isset(($this->t)[$tx])) ($this->t)[$tx] = [];
        if (!isset(($this->t)[$tx][$ty])) ($this->t)[$tx][$ty] = [];

        if (!array_key_exists($id, ($this->t)[$tx][$ty] )) ($this->t)[$tx][$ty][$id] = 1;
    }

    public function outputHashTable() {
        foreach ($this->t as $x => $tileRow) {
            foreach ($tileRow as $y => $tile) {
                echo $x."\t".$y." | ";
                foreach ($tile as $id => $dummy) {
                    echo $id." ";
                }
                echo "\n";
            }
        }
    }

    private function getSideSegment($tx, $ty, $side)
    {
        return new Edge(
            new Point(($tx + ($this->pc1)[$side][0] )*$this->len, ($ty + ($this->pc1)[$side][1])*$this->len),
            new Point(($tx + ($this->pc2)[$side][0] )*$this->len, ($ty + ($this->pc2)[$side][1])*$this->len),
                null);
    }

    /**
     *
     * @param Point $a
     * @return array in which the keys are the ids and the values are the elements
     */
    public function getAllElementIdsInCircle(Point $a) {
        $tx = 0; $ty = 0;
        $this->getTileIndex($a, $tx, $ty);
        $mSet = [];
        for ($i = -1 + $tx; $i < 2 + $tx; $i++) {
            for ($j = -1 + $ty; $j < 2 + $ty; $j++) {
                if (isset(($this->t)[$i]) && isset(($this->t)[$i][$j])) {
                    foreach (($this->t)[$i][$j] as $id => $dummy) {
                        $mSet[$id] = ($this->els)[$id];
                        // TODO: check distance to agree with len
                        /*if () {

                        }*/
                    }
                }
            }
        }
        return $mSet;
    }
}