<?php

use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    public function testBase()
    {
        $vector = new JVector();

        // в конец
        $vector[] = 12;
        $vector->add(12);
        $vector->push(12);
        $vector->append(12);

        // в середину
        $vector[0] = 12;
        $vector->set(0, 12);
        $vector->insert(0, 12);

        // удаления
        $vector->pop();
        $vector->del(0);
        $vector->delete(0);
        $vector->remove(0);
        unset($vector[0]);

        // доступ
        $vector[0];
        $vector->at(0);
        $vector->get(0);
    }
}
