<?php

/**
 * @author Azeem Michael
 */
class BinarySearchIterator implements \Iterator
{
    /**
     * @var \SplDoublyLinkedList
     */
    private $data;

    /**
     * @param \SplDoublyLinkedList $data
     */
    public function __construct(\SplDoublyLinkedList $data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    public function rewind()
    {
        return $this->data->rewind();
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->data->current();
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->data->key();
    }

    /**
     *
     */
    public function next()
    {
        return $this->data->next();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->data->valid();
    }
}
