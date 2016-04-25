<?php

use Krak\Coll\Set;

describe('Set', function() {
    $test_set = function($set, $create_val) {
        beforeEach(function() use ($set, $create_val) {
            $this->set = $set;
            $this->val = $create_val;
        });
        describe('->count()', function() {
            it('returns the count of the set elements', function() {
                assert(count($this->set) === 1);
            });
        });
        describe('->has($value)', function() {
            it('returns false if value is not in set', function() {
                assert($this->set->has(call_user_func($this->val)) === false);
            });
            it('returns true if value is in set', function() {
                $val = call_user_func($this->val);
                $this->set->add($val);
                assert($this->set->has($val));
            });
        });
        describe('->get($value)', function() {
            it('returns null if value is not in set', function() {
                assert($this->set->get(call_user_func($this->val)) === null);
            });
            it('returns value if value is in set', function() {
                $val = call_user_func($this->val);
                $this->set->add($val);
                assert($this->set->get($val) == $val);
            });
        });
        describe('->getIterator()', function() {
            it('iterates through the result set', function() {
                assert(iterator_count($this->set) == 3);
            });
        });
        describe('->add($value)', function() {
            it('adds non-duplicate elements to the set', function() {
                $val = call_user_func($this->val);
                $this->set->add($val);
                $count = count($this->set);
                $this->set->add($val);
                assert(count($this->set) == $count);
            });
        });
        describe('->remove($value)', function() {
            it('removes a value from the set if exists', function() {
                $orig_count = count($this->set);
                $val = call_user_func($this->val);
                $this->set->add($val);
                $new_count = count($this->set);

                // remove it twice to make sure it only does it once
                $this->set->remove($val);
                $this->set->remove($val);

                assert($orig_count + 1 == $new_count && $orig_count == count($this->set));
            });
        });
        describe('__clone()', function() {
            it('will make a deep copy', function() {
                $orig_count = count($this->set);
                $new_set = clone($this->set);
                $new_set->add(call_user_func($this->val));

                assert(count($this->set) === $orig_count);
            });
        });
    };

    describe('ObjectSet', function() use ($test_set) {
        $storage = new SplObjectStorage();
        $storage->attach(new stdClass());

        $test_set(new Set\ObjectSet($storage), function() {
            return new stdClass();
        });
    });
    describe('ArraySet', function() use ($test_set) {
        $vals = [
            0 => null,
        ];

        $test_set(new Set\ArraySet($vals), function() {
            static $i = 1;
            $j = $i;
            $i += 1;
            return $j;
        });
    });
    describe('HashSet', function() use ($test_set) {
        $vals = [
            md5(serialize([0])) => [0],
        ];

        $test_set(new Set\HashSet($vals), function() {
            static $i;
            if (!$i) {
                $i = [1];
            }

            $j = $i;
            $i[0] += 1;

            return $j;
        });
    });
    describe('AnySet', function() use ($test_set) {
        $vals = [0 => null];

        $test_set(new Set\AnySet(null, new Set\ArraySet($vals)), function() {
            static $i = 1;
            $j = $i % 3;
            if ($j == 0) {
                $val = $i;
            }
            else if ($j == 1) {
                $val = [$i];
            }
            else {
                $val = new stdClass();
            }

            $i += 1;
            return $val;
        });
    });

    describe('#factory', function() {
        it('creates a typed set if the two sets are the same type', function() {
            $factory = Set\factory();
            $s1 = new Set\ArraySet(); $s2 = new Set\ArraySet();

            $s3 = $factory($s1, $s2);
            assert(get_class($s3) == get_class($s1) && $s3 !== $s1 && $s3 !== $s2);
        });
        it('creates an AnySet if the two sets are different', function() {
            $factory = Set\factory();
            $s1 = new Set\ArraySet(); $s2 = new Set\ObjectSet();

            $s3 = $factory($s1, $s2);
            assert($s3 instanceof Set\AnySet && $s3 !== $s1 && $s3 !== $s2);
        });
    });
    describe('#fill', function() {
        it('fills a set and returns it', function() {
            assert(count(set\fill(new Set\ArraySet(), [1, 2])) === 2);
        });
    });
    describe('#is_subset', function() {
        it('returns true if a set is a subset of another and false if not', function() {
            $s1 = Set\ArraySet::create([1,2,3]);
            $s2 = Set\ArraySet::create([2]);

            assert(set\is_subset($s1, $s2) && !set\is_subset($s2, $s1));
        });
    });
    describe('#equals', function() {
        it('returns true if two sets have the exact same elements', function() {
            $s1 = Set\ArraySet::create([1,2]);
            $s2 = Set\ArraySet::create([2,1]);

            assert(set\equals($s1, $s2));
        });
        it('returns false if two sets do not have the exact same elements', function() {
            $s1 = Set\ArraySet::create([1,2]);
            $s2 = Set\ArraySet::create([2]);

            assert(!set\equals($s1, $s2));
        });
    });
    describe('#union', function() {
        it('unions two sets', function() {
            $s1 = Set\ArraySet::create([1,2,3]);
            $s2 = Set\ArraySet::create([2,3,4]);

            assert(set\equals(
                set\union($s1, $s2),
                Set\ArraySet::create([1,2,3,4])
            ));
        });
    });
    describe('#intersect', function() {
        it('intersects two sets', function() {
            $s1 = Set\ArraySet::create([1,2,3]);
            $s2 = Set\ArraySet::create([2,3,4]);

            assert(set\equals(
                set\intersect($s1, $s2),
                Set\ArraySet::create([2,3])
            ));
        });
    });
    describe('#difference', function() {
        it('performs the difference of two sets', function() {
            $s1 = Set\ArraySet::create([1,2,3]);
            $s2 = Set\ArraySet::create([2,3,4]);

            assert(set\equals(
                set\difference($s1, $s2),
                Set\ArraySet::create([1])
            ));
        });
    });
});
