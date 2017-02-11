<?php

namespace jugger\ds;

/**
 * Ассоциативный массив
 */
class JArray extends JCollection
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    //
    // Методы доступа
    //

    public function shift()
    {
        return array_shift($this->data);
    }

    public function unshift(...$values)
    {
        array_unshift($this->data, ...$values);
        return $this;
    }

    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function insert($key, $value)
    {
        return $this->splice($key, 0, [$value]);
    }

    public function remove(...$keys)
    {
        $value = null;
        foreach ($keys as $key) {
            $value = $this->data[$key] ?? null;
            unset($this->data[$key]);
        }
        return $value;
    }

    public function push(...$values)
    {
        array_push($this->data, ...$values);
        return $this;
    }

    public function pop()
    {
        return array_pop($this->data);
    }

    public function exists($key): bool
    {
        return isset($this->data[$key]);
    }

    public function length(): int
    {
        return count($this->data);
    }

    //
    // Методы модификаторы
    //

    public function sum(): float
    {
        return array_sum($this->data);
    }

    public function max(): float
    {
        return max($this->data);
    }

    public function min(): float
    {
        return min($this->data);
    }

    public function keys(): JCollection
    {
        return new static(array_keys($this->data));
    }

    public function values(): JCollection
    {
        return new static(array_values($this->data));
    }

    public function reduce(\Closure $callback, $initial = null)
    {
        return array_reduce($this->data, $callback, $initial);
    }

    public function search($value, $strict = false)
    {
        return array_search($value, $this->data, $strict);
    }

    public function slice(int $offset, int $length = null, $preserve_keys = false): JCollection
    {
        return new static(array_slice($this->data, $offset, $length, $preserve_keys));
    }

    public function splice(int $offset, int $length, array $values = []): JCollection
    {
        return new static(array_splice($this->data, $offset, $length, $values));
    }

    public function fill($value, int $count): JCollection
    {
        for ($i=0; $i<$count; $i++) {
            $this->push($value);
        }
        return $this;
    }

    public function filter(\Closure $callback = null, $flag = 0): JCollection
    {
        if (is_null($callback)) {
            $callback = function($item) {
                return $item != false;
            };
        }
        $this->data = array_filter($this->data, $callback, $flag);
        return $this;
    }

    public function map(\Closure $callback): JCollection
    {
        $this->data = array_map($callback, $this->data);
        return $this;
    }

    public function merge($data): JCollection
    {
        if ($data instanceof self) {
            $data = $data->toArray();
        }
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function replace($need, $replace, $strict = false): JCollection
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

    public function sort($sort_flags = SORT_REGULAR): JCollection
    {
        sort($this->data, $sort_flags);
        return $this;
    }

    public function unique($sort_flags = SORT_STRING): JCollection
    {
        $this->data = array_unique($this->data, $sort_flags);
        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function subArray(array $keys): JArray
    {
        $arr = clone $this;
        $arr->filter(function($key) use($keys) {
            return in_array($key, $keys);
        }, \ARRAY_FILTER_USE_KEY);
        return $arr;
    }
}
