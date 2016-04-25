<?php

namespace Krak\Coll\Set;

use function iter\chain,
    iter\reduce,
    iter\all;

interface Set extends ConstSet {
    public function add($value);
    public function remove($value);
}

function factory() {
    return __NAMESPACE__ . '\_factory';
}

/** creates a new set based off of two sets passed in */
function _factory(ConstSet $s1, ConstSet $s2) {
    if (get_class($s1) === get_class($s2)) {
        $cls = get_class($s1);
        return new $cls;
    }

    return new AnySet();
}

/** joins two possibly heterogenous sets, similar to union */
function join(ConstSet $s1, ConstSet $s2, $factory = null) {
    $factory = $factory ?: factory();
    return fill($factory($s1, $s2), chain($s1, $s2));
}

function union(ConstSet $s1, ConstSet $s2) {
    $s3 = clone $s1;
    return fill($s3, $s2);
}

function intersect(ConstSet $s1, ConstSet $s2) {
    $smaller = count($s1) < count($s2) ? $s1 : $s2;
    $s3 = clone($smaller);

    $other = $smaller === $s1 ? $s2 : $s1;

    return reduce(function($acc, $val) use ($other) {
        if (!$other->has($val)) {
            $acc->remove($val);
        }

        return $acc;
    }, $smaller, $s3);
}

function difference(ConstSet $s1, ConstSet $s2) {
    $s3 = clone $s1;

    return reduce(function($acc, $val) use ($s2) {
        if ($s2->has($val)) {
            $acc->remove($val);
        }

        return $acc;
    }, $s1, $s3);
}

/** returns true if s2 is a subset of s1 */
function is_subset(ConstSet $s1, ConstSet $s2) {
    return all(function($val) use ($s1) {
        return $s1->has($val);
    }, $s2);
}

function equals(ConstSet $s1, ConstSet $s2) {
    return is_subset($s1, $s2) && count($s1) == count($s2);
}

function fill(Set $s1, $values) {
    foreach ($values as $val) {
        $s1->add($val);
    }

    return $s1;
}
