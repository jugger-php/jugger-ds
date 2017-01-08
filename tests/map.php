<?php

use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    public function testBase()
    {
        $vector = new JMap();

        // добавление
        $vector['key'] = 'value';
        $vector->set('key', 'value');
        $vector->insert('key', 'value');

        // удаления
        $vector->del('key');
        $vector->delete('key');
        $vector->remove('key');
        unset($vector['key']);

        // доступ
        $vector['key'];
        $vector->at('key');
        $vector->get('key');
    }
}
