# Coll (Collections)

The Collections library is yet another attempt to generate OO collections in php.

Currently, the only collection in here is the Set because that's the only collection PHP actually is missing.

## Installation

```
compose require krak/coll
```

## Usage

### Set

A set represents a set of unique values.

There are two interfaces for the Set type: `ConstSet` and `Set`.

Const sets allow read access only whereas Sets allow read write access.

```
// const methods
public function get($value);
public function has($value);
public function count();
public function getIterator();

// non-const methods
public function add($value);
public function remove($value);
```

```php
<?php

use Krak\Coll\Set;

$s1 = new Set\ArraySet();
// or
$s1 = Set\ArraySet::create([1,2,3]);

$s1->add(4);
$s2 = set\union($s1, Set\ArraySet::create([3,4]));

set\is_subset($s2, $s1); // returns true because s1 is a subset of s2

// if you need to store object values

// internally uses SplObjectStorage
$oset = new Set\ObjectSet();
$oset->add(new stdClass());

// if you need to store values that work as array keys
$hset = new Set\HashSet();
$hset->add(['a' => 1]);

// if you need to store any of those types of values
$aset = new Set\AnySet();
set\fill($aset, [1, [1], new stdClass()]);
```

Each is set does also work as an iterable and is countable

```php
<?php

count($s1); // returns the count
foreach ($s1 as $val) {
    // iterate over the set values
}
```

For type hinting use, `Set` or `ConstSet`

```php
<?php

use Krak\Coll\Set;

function operate_on_mutable(Set\Set $s1) {
    // ...
}

function operate_on_immutable(Set\ConstSet $s1) {
    // ...
}
```

As seen earlier, we do have several set operation functions.

```
// similar to union, but can work with different typed set (AnySet and ArraySet)
function join(ConstSet $s1, ConstSet $s2, $factory = null);
// s1 + s2
function union(ConstSet $s1, ConstSet $s2);
// s1 * s2
function intersect(ConstSet $s1, ConstSet $s2);
// s1 - s2
function difference(ConstSet $s1, ConstSet $s2);
// compares to sets returns true if s2 is subset of s1
function is_subset(ConstSet $s1, ConstSet $s2);
// compares to sets returns true on equal
function equals(ConstSet $s1, ConstSet $s2);
// fills a set with values and returns it
function fill(Set $s1, $values);
```

In addition to the functional methods, there is an OO interface like so

```php
<?php
$s1->union($s2)
    ->intersect($s3)
    ->difference($s4)
    ->equals($s5);
```
