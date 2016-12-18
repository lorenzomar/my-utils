<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\UseCase;

use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class AbstractRequestResponse
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/my-utils
 */
abstract class AbstractRequestResponse implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $items = [], PropertyAccessor $propertyAccessor = null)
    {
        $this->accessor = is_null($propertyAccessor) ? new PropertyAccessor() : $propertyAccessor;

        $this->setMultiple($items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function count()
    {
        return count($this->data);
    }

    /**
     * setMultiple.
     * Set multiple items
     *
     * @param array $items
     */
    public function setMultiple(array $items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * set.
     *
     * @param string $key   The data key
     * @param mixed  $value The data value
     */
    public function set($key, $value)
    {
        $this->accessor->setValue($this->data, $this->prepareKey($key), $value);
    }

    /**
     * get.
     *
     * @param string $key     The data key
     * @param mixed  $default The default value to return if data key does not exist
     *
     * @return mixed The key's value, or the default value
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->accessor->getValue($this->data, $this->prepareKey($key)) : $default;
    }

    /**
     * has.
     *
     * @param string $key The data key
     *
     * @return bool
     */
    public function has($key)
    {
        return !is_null($this->accessor->getValue($this->data, $this->prepareKey($key)));
    }

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    private function prepareKey($key)
    {
        return "[" . trim($key, '[]') . "]";
    }
}