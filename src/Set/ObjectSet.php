<?php

namespace Krak\Coll\Set;

use SplObjectStorage;

/** Set implemention with an array */
class ObjectSet extends AbstractSet
{
    private $storage;

    public function __construct(SplObjectStorage $storage = null) {
        $this->storage = $storage ?: new SplObjectStorage();
    }

    public function has($value) {
        return $this->storage->contains($value);
    }

    public function get($value) {
        return $this->has($value)
            ? $value
            : null;
    }

    public function count() {
        return $this->storage->count();
    }

    public function add($value) {
        $this->storage->attach($value);
    }

    public function remove($value) {
        $this->storage->detach($value);
    }

    public function getIterator() {
        return $this->storage;
    }

    public function __clone() {
        $this->storage = clone $this->storage;
    }
}
