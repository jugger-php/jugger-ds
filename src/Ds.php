<?php

namespace jugger\ds;

abstract class Ds
{
    public static function str($data)
    {
        return new JString($data);
    }

    public static function string($data)
    {
        return new JString($data);
    }

    public static function num($data)
    {
        return new JNumber($data);
    }

    public static function number($data)
    {
        return new JNumber($data);
    }

    public static function arr(array $data = [])
    {
        return new JArray($data);
    }

    public static function array(array $data = [])
    {
        return new JArray($data);
    }

    public static function vec(array $data = [])
    {
        return new JVector($data);
    }

    public static function vector(array $data = [])
    {
        return new JVector($data);
    }

    public static function list(array $data = [])
    {
        return new JList($data);
    }
}
