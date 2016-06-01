<?php

/**
 * A collection that contains no duplicate elements. More formally, sets contain no
 * pair of elements e1 and e2 such that e1->equals(e2), and at most one null element.
 * As implied by its name, this interface models the mathematical set abstraction.
 * @author Azeem Michael
 */
interface SetInterface
{
    /**
     * Remove all the values from this collection so that it becomes empty.
     */
    public function clear();

    /**
     * @return int the number of objects in this collection
     */
    public function count();

    /**
     * If this collection is empty, return true otherwise return false.
     * @return boolean A boolean indicating if this collection is or is not empty.
     */
    public function isEmpty();

    /**
     * If this collection is contains the given value return true, otherwise return false.
     * @param mixed $element The value being searched for in this collection.
     * @return boolean A boolean indicating if this collection contains the value.
     */
    public function contains($element);

    /**
     * Remove the given item from this collection. If the item is not a member of this
     * collection the operation fails.
     * @param mixed $element value to be removed from the set
     * @return boolean true if the value is removed, false if it is not removed.
     */
    public function remove($element);

    /**
     * Create and return an iterator for this collection.
     * @return Iterator an iterator for this collection.
     */
    public function iterator();

    /**
     * Insert a value into this set if it is not already present.
     * @param mixed $element the object being added to the set
     * @return true if the object is added, false if it is not added.
     */
    public function add($element);

    /**
     * Return a string representation of this set.
     * @return string A string that contains the contents of the set.
     */
    public function __toString();

    /**
     * Test to see if the this set has the same contents as the given set.
     * @param SetInterface the set that is being compared to this set.
     * @return boolean true if the two sets have the same contents, false otherwise.
     */
    public function equals(SetInterface $other);

    /**
     * Create a new set whose contents will be the union of the this set
     * and the given set.
     * @param SetInterface the set whose contents are being added to this set to form the union.
     * @return SetInterface a new set that contains the union of the two sets.
     */
    public function union(SetInterface $otherSet);

    /**
     * Create a new set whose contents will be the intersection of this
     * set and the given set.
     * @param SetInterface the set whose contents are being intersected with this set.
     * @return SetInterface a new set that contains the intersection of the two sets.
     */
    public function intersection(SetInterface $otherSet);

    /**
     * Create a new set whose contents will be the set difference of this set and
     * the given set.  An item will be in the set difference if and only if it is
     * in this set and not in the given set.
     * @param SetInterface the set being used to form the difference with this set.
     * @return SetInterface a new set that contains the difference of the two sets.
     */
    public function difference(SetInterface $otherSet);

    /**
     * Test to see if the given set is a subset of this set.
     * @param SetInterface the set that is being tested.
     * @return boolean true if the given set is a subset of this set, false otherwise.
     */
    public function hasSubset(SetInterface $otherSet);
}
