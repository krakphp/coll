<?php

namespace Krak\Coll\Set;

interface ConstSet extends \IteratorAggregate, \Countable {
    public function get($value);
    public function has($value);
}
