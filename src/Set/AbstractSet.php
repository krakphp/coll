<?php

namespace Krak\Coll\Set;

abstract class AbstractSet implements Set
{
    public function get($value) {
        return $this->has($value) ? $value : null;
    }

    public function join(ConstSet $s2, $factory = null) {
        return join($this, $s2, $factory);
    }
    public function union(ConstSet $s2) {
        return union($this, $s2);
    }
    public function intersect(ConstSet $s2) {
        return intersect($this, $s2);
    }
    public function difference(ConstSet $s2) {
        return difference($this, $s2);
    }
    public function isSubset(ConstSet $s2) {
        return is_subset($this, $s2);
    }
    public function equals(ConstSet $s2) {
        return equals($this, $s2);
    }
    public function fill($values) {
        return fill($this, $values);
    }

    public static function create($values = []) {
        return fill(new static(), $values);
    }
}
