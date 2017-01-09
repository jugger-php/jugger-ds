<?php

namespace jugger\ds;

/**
 * Ассоциативный массив
 */
class JArray implements \ArrayAccess, \Serializable, \Countable, \Iterator
{
    use DataImplementsTrait;

    protected $data;

    /*
     * служебные функции
     */

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    /*
     * кол-во элементов
     */

    public function count()
    {
        return count($this->data);
    }

    public function size()
    {
        return $this->count();
    }

    public function length()
    {
        return $this->count();
    }

    /*
     * Добавить
     */

    public function add(...$values): JArray
    {
        foreach ($values as $value) {
            $this->data[] = $value;
        }
        return $this;
    }

    public function append(...$values): JArray
    {
        foreach ($values as $value) {
            $this->data[] = $value;
        }
        return $this;
    }

    public function push(...$values): JArray
    {
        foreach ($values as $value) {
            $this->data[] = $value;
        }
        return $this;
    }

    public function unshift(...$values)
    {
        foreach ($values as $value) {
            array_unshift($this->data, $value);
        }
        return $this;
    }

    public function set($offset, $value)
    {
        $this->data[$offset] = $value;
        return $this;
    }

    public function insert($offset, $value)
    {
        $this->set($offset, $value);
        return $this;
    }

    /*
     * Удалить
     */

    public function pop()
    {
        return array_pop($this->data);
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function remove($offset)
    {
        $ret = $this->get($offset);
        unset($this[$offset]);
        return $ret;
    }

    public function removeKeys(...$keys)
    {
        foreach ($keys as $key) {
            $this->remove($key);
        }
        return $this;
    }

    public function delete($offset)
    {
        return $this->remove($offset);
    }

    public function del($offset)
    {
        return $this->remove($offset);
    }

    /*
     * Доступ
     */

    public function get($offset)
    {
        return $this[$offset];
    }

    public function at($offset)
    {
        return $this[$offset];
    }

    /*
     * Методы не модифицирующие массив
     */

    public function values()
    {
        return new static(array_values($this->data));
    }

    public function keys()
    {
        return new static(array_keys($this->data));
    }

    public function reduce(\Closure $callback, $initial = null)
    {
        return array_reduce($this->data, $callback, $initial);
    }

    public function keyExist($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function search($value, $strict = false)
    {
        return array_search($value, $this->data, $strict);
    }

    public function exist($value, $strict = false)
    {
        return $this->search($value, $strict);
    }

    public function indexOf($value, $strict = false)
    {
        return $this->search($value, $strict);
    }

    public function contains($value, $strict = false)
    {
        return $this->search($value, $strict);
    }

    public function sum()
    {
        return array_sum($this->data);
    }

    public function max()
    {
        return max($this->data);
    }

    public function min()
    {
        return min($this->data);
    }

    public function slice($offset, $length = null, $preserve_keys = false)
    {
        return new static(array_slice($this->data, $offset, $length, $preserve_keys));
    }

    /*
     * Методы модифицирующие массив
     */

    public function fill($value, $count)
    {
        for ($i=0; $i<$count; $i++) {
            $this->add($value);
        }
        return $this;
    }

    public function filter(\Closure $callback = null, $flag = 0)
    {
        if (is_null($callback)) {
            $callback = function($item) {
                return $item != false;
            };
        }
        $this->data = array_filter($this->data, $callback, $flag);
        return $this;
    }

    public function map(\Closure $callback)
    {
        $this->data = array_map($callback, $this->data);
        return $this;
    }

    public function merge($data)
    {
        if ($data instanceof self) {
            $data = $data->toArray();
        }
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function replace($need, $replace, $strict = false)
    {
        $need = is_array($need) ? new static($need) : $need;
        $f = function($item) use($need, $replace, $strict) {
            if (!is_scalar($need)) {
                $i = $need->search($item, $strict);
            }
            elseif ($strict) {
                $i = $item === $need;
            }
            else {
                $i = $item == $need;
            }

            if ($i !== false) {
                return is_array($replace) ? ($replace[$i] ?? null) : $replace;
            }
            else {
                return $item;
            }
        };
        return $this->map($f);
    }

    public function sort($sort_flags = SORT_REGULAR)
    {
        sort($this->data, $sort_flags);
        return $this;
    }

    public function unique($sort_flags = SORT_STRING)
    {
        $this->data = array_unique($this->data, $sort_flags);
        return $this;
    }
}
