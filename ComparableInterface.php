<?php

namespace AppBundle\Utils;

/**
 * This interface imposes a total ordering on the objects of each class that implements
 * it. This ordering is referred to as the class's natural ordering, and the class's
 * compareTo method is referred to as its natural comparison method
 */
interface ComparableInterface {

    /**
     * Compares this object with the specified object for order. Returns a negative
     * integer, zero, or a positive integer as this object is less than, equal to,
     * or greater than the specified object
     *
     * @uses Compares this object with the specified object for order
     * @param ComparableInterface $o - the object to be compared
     * @return int a negative integer, zero, or a positive integer as this object is
     * less than, equal to, or greater than the specified object
     */
    public function compareTo(ComparableInterface $o);

    /**
     * Case Insensitive Comparator
     * @param ComparableInterface $o - the object to be compared
     * @return int a negative integer, zero, or a positive integer as this object is
     * less than, equal to, or greater than the specified object
     */
    public function compareToIgnoreCase(ComparableInterface $o);
}
