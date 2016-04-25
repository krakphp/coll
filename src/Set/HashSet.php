<?php

namespace Krak\Coll\Set;

use SplObjectStorage;

/** Set implemention with an array and using md5 serialize on the values */
class HashSet extends AbstractSet
{
    private $values;

    public function __construct($values = []) {
        $this->values = $values;
    }

    private function hash($value) {
        return md5(serialize($value));
    }

    public function has($value) {
        return array_key_exists($this->hash($value), $this->values);
    }

    public function count() {
        return count($this->values);
    }

    public function add($value) {
        $this->values[$this->hash($value)] = $value;
    }

    public function remove($value) {
        unset($this->values[$this->hash($value)]);
    }

    public function getIterator() {
        foreach ($this->values as $key => $value) {
            yield $value;
        }
    }
}
