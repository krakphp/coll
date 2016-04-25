<?php

namespace Krak\Coll\Set;

use SplObjectStorage;

/** Set implemention with an array */
class ArraySet extends AbstractSet
{
    private $values;

    public function __construct($values = []) {
        $this->values = $values;
    }

    public function has($value) {
        return array_key_exists($value, $this->values);
    }

    public function count() {
        return count($this->values);
    }

    public function add($value) {
        $this->values[$value] = null;
    }

    public function remove($value) {
        unset($this->values[$value]);
    }

    public function getIterator() {
        foreach ($this->values as $key => $value) {
            yield $key;
        }
    }
}
