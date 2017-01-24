<?php

namespace jugger\ds;

trait JCollectionImplementsTrait
{
    protected $position;

    /*
     * ArrayAccess
     */

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /*
     * Countable
     */

    public function count()
    {
        return $this->length();
    }

    /*
     * Iterator
     */

    public function current()
    {
        return $this->get($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return $this->exists($this->position);
    }
}
