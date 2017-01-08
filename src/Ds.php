<?php

namespace jugger\ds;

abstract class Ds
{
    public static function arr(array $data)
    {
        return new JArray($data);
    }

    public static function map(array $data)
    {
        return new JMap($data);
    }

    public static function vec(array $data)
    {
        return new JVector($data);
    }

    public static function list(array $data)
    {
        return new JList($data);
    }
}
