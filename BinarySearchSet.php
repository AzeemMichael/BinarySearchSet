<?php

namespace AppBundle\Utils;

use Doctrine\Common\Collections\Collection;

/**
 * A collection that contains no duplicate elements. More formally, sets contain no
 * pair of elements $e1 and $e2 such that $e1->equals($e2), and at most one null element.
 * As implied by its name, this class models the mathematical set abstraction.
 */
class BinarySearchSet implements SetInterface {

    const COMPARABLE_MODE_CASE_INSENSITIVE = 0;
    const COMPARABLE_MODE_CASE_SENSITIVE   = 1;

    /**
     * @var \SplDoublyLinkedList $data
     */
    private $data;

    /**
     * @var bool
     */
    private $mode;

    /**
     * @param null $collection
     * @throws \InvalidArgumentException
     */
    public function __construct($collection = null) {
        if ($collection !== null) {
            $this->data = new \SplDoublyLinkedList();
            if ($collection instanceof \SplDoublyLinkedList) {
                for($collection->rewind(); $collection->valid(); $collection->next()) {
                    $this->add($collection->current());
                }
            } elseif (is_array($collection) || $collection instanceof Collection) {
                foreach ($collection as $element) {
                    $this->add($element);
                }
            } else {
                throw new \InvalidArgumentException('Unsupported argument supplied for BinarySearchSet::__constructor(). Expected array|Collection|SplDoublyLinkedList but found '. (is_object($collection)? get_class($collection) : gettype($collection)));
            }
        } else {
            $this->data = new \SplDoublyLinkedList();
        }
        $this->mode = self::COMPARABLE_MODE_CASE_INSENSITIVE;
    }

    /**
     * The class destructor to prevent memory leak
     */
    public function __destructor() {
        unset($this);
    }

    /**
     * The copy constructor
     */
    public function __clone() {
        $this->data = clone $this->data;
    }

    /**
     * Return a string representation of this set.
     * @return string A string that contains the contents of the set.
     */
    public function __toString() {
        return json_encode($this->data);
        // $result = '(';
        // for($this->data->rewind(); $this->data->valid(); $this->data->next()) {
        //     $result .= "{$this->data->current()} ";
        // }
        // return $result .= ')';
    }

    /**
     * Remove all the values from this collection so that it becomes empty.
     */
    public function clear() {
        $this->data = new \SplDoublyLinkedList();
    }

    /**
     * @return int the number of objects in this collection
     */
    public function count() {
        return $this->data->count();
    }

    /**
     * If this collection is empty, return true otherwise return false.
     * @return boolean A boolean indicating if this collection is or is not empty.
     */
    public function isEmpty() {
        return $this->data->isEmpty();
    }

    /**
     * If this collection contains the given value return true, otherwise return false.
     * @param mixed $element The value being searched for in this collection.
     * @return boolean A boolean indicating if this collection contains the value.
     */
    public function contains($element) {
        return $this->find($element) >= 0;
    }

    /**
     * Remove the given item from this collection. If the item is not a member of this
     * collection the operation fails.
     * @param mixed $element value to be removed from the set
     * @return boolean true if the value is removed, false if it is not removed.
     */
    public function remove($element) {
        $foundAt = $this->find($element);
        if ($foundAt >= 0) {
            $this->data->offsetUnset($foundAt);
            return true;
        }
        return false;
    }

    /**
     * Create and return an iterator for this collection.
     * @return Iterator an iterator for this collection.
     */
    public function iterator() {
        return new BinarySearchIterator($this->data);
    }

    /**
     * Insert a value into this set if it is not already present.
     * @param mixed $element the object being added to the set
     * @return boolean true if the object is added, false if it is not added.
     */
    public function add($element) {
        $foundAt = $this->find($element);
        if ($foundAt < 0) { // item not in list
            $pos = -$foundAt - 1; //position where item should be inserted
            $this->data->add($pos, $element);
            return true;
        }
        return false;
    }

    /**
     * Test to see if this set has the same contents as the given set.
     * @param SetInterface the set that is being compared to this set.
     * @return boolean true if the two sets have the same contents, false otherwise.
     */
    public function equals(SetInterface $otherSet) {
        if ($otherSet === $this) {
            return true;
        }
        if (!($otherSet instanceof SetInterface)) {
            return false;
        }
        if ($this->count() != $otherSet->count()) {
            return false;
        }
        for ($otherSet->rewind(); $otherSet->valid(); $otherSet->next()) {
            if (!$this->contains($otherSet->current())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Create a new set whose contents will be the union of the this set
     * and the given set.
     * @param SetInterface the set whose contents are being added to this set to form the union.
     * @return SetInterface a new set that contains the union of the two sets.
     */
    public function union(SetInterface $otherSet) {
        $tmp = clone $otherSet;
        for ($this->data->rewind(); $this->data->valid(); $this->data->next()) {
            $tmp->add($this->data->current());
        }
        return $tmp;
    }

    /**
     * Create a new set whose contents will be the intersection of this
     * set and the given set.
     * @param SetInterface the set whose contents are being intersected with this set.
     * @return SetInterface a new set that contains the intersection of the two sets.
     */
    public function intersection(SetInterface $otherSet) {
        $tmp = new BinarySearchSet();
        for ($this->data->rewind(); $this->data->valid(); $this->data->next()) {
            $hold = $this->data->current();
            if ($otherSet->contains($hold)) {
                $tmp->add($hold);
            }
        }
        return $tmp;
    }

    /**
     * Create a new set whose contents will be the set difference of this set and
     * the given set.  An item will be in the set difference if and only if it is
     * in this set and not in the given set.
     * @param SetInterface $otherSet the set being used to form the difference with this set.
     * @return SetInterface a new set that contains the difference of the two sets.
     */
    public function difference(SetInterface $otherSet) {
        $tmp = new BinarySearchSet();
        for ($this->data->rewind(); $this->data->valid(); $this->data->next()) {
            $hold = $this->data->current();
            if (!$otherSet->contains($hold)) {
                $tmp->add($hold);
            }
        }
        return $tmp;
    }

    /**
     * Test to see if the given set is a subset of this set.
     * @param SetInterface the set that is being tested.
     * @return boolean true if the given set is a subset of this set, false otherwise.
     */
    public function hasSubset(SetInterface $otherSet) {
        for ($otherSet->rewind(); $otherSet->valid(); $otherSet->next()) {
            $hold = $otherSet->current();
            if (!$this->contains($hold)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Performs binary search on collection. If item is not in the list, it returns a signal that gives the
     * location where the ModuleTerm should be inserted into the list.
     * @param mixed $item the haystack to be search
     * @param mixed $item the needle to search for
     * @return int returns the position of the item, if item is not in the list,
     * returns signal that gives location where it should be inserted
     * @throws \InvalidArgumentException only ComarableInterface|string|int|float allowed
     */
    private function find($item) {
        $low = 0;
        $high = $this->data->count() - 1;

        while ($low <= $high) {
            $mid = ($low + $high) >> 1; //shift the bits of ($low+$hight) result to the right (each step means "divide by two")
            $tmp = $this->data->offsetGet($mid);

            if ($item instanceof ComparableInterface) {
                $result = $this->getComparableMode() == self::COMPARABLE_MODE_CASE_SENSITIVE ? $item->compareTo($tmp) : $item->compareToIgnoreCase($tmp);
            } elseif (is_int($item) || is_float($item)) {
                if ($item < $tmp) {
                    $result = -1;
                } elseif ($item > $tmp) {
                    $result = 1;
                } else {
                    $result = 0;
                }
            } elseif (is_string($item)) {
                $result = $this->getComparableMode() == self::COMPARABLE_MODE_CASE_SENSITIVE ? strcasecmp($item, $tmp) : strcmp($item, $tmp);
            } else {
                throw new \InvalidArgumentException('BinarySearch::find only accepts ComarableInterface|string|int|float, '.gettype($item) . ' given.');
            }

            if ($result == 0) //item has been found, return its location
                return $mid;
            if ($result < 0) //item comes before middle elements, search the top of the table
                $high = $mid - 1;
            else           //item comes after the middle elements, search the bottom of the table
                $low = $mid + 1;
        }
        //item is not in the list, return a signal that gives the location where
        //the item should be inserted into the list.
        return -$low - 1;
    }

    /**
     * Set the comparable mode. There are two sets of modes that can be set:
     * <ul>
     * <li>COMPARABLE_MODE_CASE_INSENSITIVE</li>
     * <li>COMPARABLE_MODE_CASE_SENSITIVE</li>
     * </ul>
     * The default mode is COMPARABLE_MODE_CASE_INSENSITIVE
     * @param bool $mode
     */
    public function setComparableMode($mode) {
        $this->mode = $mode;
    }

    /**
     * @return bool
     */
    public function getComparableMode() {
        return $this->mode;
    }

}
