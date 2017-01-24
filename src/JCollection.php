<?php

namespace jugger\ds;

/**
 * Коллекция
 */
abstract class JCollection implements \ArrayAccess, \Countable, \Iterator
{
    use JCollectionImplementsTrait;

    //
    // Методы доступа
    //

    abstract public function shift();

    abstract public function unshift(...$values);

    abstract public function get($key);

    abstract public function set($key, $value);

    abstract public function insert($key, $value);

    abstract public function remove(...$keys);

    abstract public function push(...$values);

    abstract public function pop();

    abstract public function exists($key): bool;

    abstract public function length(): int;

    public function count(): int
    {
        return $this->length();
    }

    //
    // Методы модификаторы
    //

    abstract public function sum(): float;

    abstract public function max(): float;

    abstract public function min(): float;

    abstract public function keys(): JCollection;

    abstract public function values(): JCollection;

    abstract public function reduce(\Closure $callback, $initial = null);

    abstract public function search($value, $strict = false);

    abstract public function slice(int $key, int $length = null, $preserve_keys = false): JCollection;

    abstract public function splice(int $offset, int $length, array $values = []): JCollection;

    abstract public function fill($value, int $count): JCollection;

    abstract public function filter(\Closure $callback = null, $flag = 0): JCollection;

    abstract public function map(\Closure $callback): JCollection;

    abstract public function merge($data): JCollection;

    abstract public function replace($need, $replace, $strict = false): JCollection;

    abstract public function sort($sort_flags = SORT_REGULAR): JCollection;

    abstract public function unique($sort_flags = SORT_STRING): JCollection;

    abstract public function toArray(): array;
}
