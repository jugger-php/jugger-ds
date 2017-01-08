<?php

use PHPUnit\Framework\TestCase;

class ListTest extends TestCase
{
    public function testBase()
    {
        $list = new JList();

        // в конец
        $list->add(12);
        $list->push(12);
        $list->append(12);

        // в начало
        $list->prepend(12);
        $list->unshift(12);

        // удаления/доступ
        $list->shift();
        $list->pop();
    }
}
