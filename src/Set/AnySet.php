<?php

namespace Krak\Coll\Set;

use SplObjectStorage;
use function iter\chain;

/** Set implemention that can store any values */
class AnySet extends AbstractSet
{
    private $obj;
    private $arr;
    private $hash;

    public function __construct(Set $obj = null, Set $arr = null, Set $hash = null) {
        $this->obj = $obj ?: new ObjectSet();
        $this->arr = $arr ?: new ArraySet();
        $this->hash = $hash ?: new HashSet();
    }

    private function getSet($value) {
        if (is_object($value)) {
            return $this->obj;
        }
        else if (is_array($value) || is_resource($value)) {
            return $this->hash;
        }

        return $this->arr;
    }

    public function has($value) {
        return $this->getSet($value)->has($value);
    }

    public function count() {
        return count($this->obj) + count($this->arr) + count($this->hash);
    }

    public function add($value) {
        return $this->getSet($value)->add($value);
    }

    public function remove($value) {
        return $this->getSet($value)->remove($value);
    }

    public function getIterator() {
        return chain($this->obj, $this->arr, $this->hash);
    }

    public function __clone() {
        $this->obj = clone $this->obj;
        $this->arr = clone $this->arr;
        $this->hash = clone $this->hash;
    }
}
