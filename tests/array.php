<?php

use PHPUnit\Framework\TestCase;
use jugger\ds\JArray;

class JArrayTest extends TestCase
{
    public function testCreate()
    {
        $arr1 = [1,2,3];
        $arr2 = new JArray($arr1);
        $arr3 = $arr2;
        $arr4 = clone $arr3;

        // why?!
        $this->assertTrue($arr1 == $arr2->toArray());
        $this->assertTrue($arr1 === $arr2->toArray());

        $this->assertTrue($arr2 === $arr3);
        $this->assertTrue($arr2->toArray() === $arr3->toArray());

        $this->assertTrue($arr3 !== $arr4);
        $this->assertTrue($arr3->toArray() === $arr4->toArray());
    }


    public function testBase()
    {
        $arr = new JArray([1,2,3,'four','five',[]]);

        // эквиваленты
        $this->assertEquals(count($arr), 6);
        $this->assertEquals($arr->count(), 6);
        $this->assertEquals($arr->length(), 6);

        // эквиваленты
        $arr->unshift(0);
        $arr->push(7);
        $arr->push(8, '9');
        $arr->push(...[10,11]);
        $arr[] = 12;

        // эквиваленты, почти
        unset($arr[6]);
        $this->assertTrue($arr->remove(5) == 'five');
        $this->assertTrue($arr->remove(4) == 'four');
        $this->assertNull($arr->remove(4)); // уже удален
        // $this->assertTrue($arr->shift() == 0);
        $this->assertTrue($arr->pop() == 12);


        // эквиваленты
        $this->assertEquals($arr[7], 7);
        $this->assertEquals($arr->get(8), 8);

        return $arr;
    }

    /**
     * @depends testBase
     */
    public function testMethods(JArray $arr)
    {
        $arr->keys();
        $arr->values();

        $this->assertTrue((bool) isset($arr[9]));
        $this->assertTrue((bool) $arr->search(9));
        $this->assertTrue((bool) $arr->exists(9));

        $this->assertFalse($arr->exists(12));

        $this->assertEquals($arr->sum(), 51);
        $this->assertEquals($arr->max(), 11);
        $this->assertEquals($arr->min(), 0);

        $this->assertTrue([10, 11] == $arr->slice(7)->toArray());
        $this->assertTrue([0,1,2] == $arr->slice(0, 3)->toArray());

        $arr = new JArray();
        $arr->fill(1, 5);
        $this->assertTrue([1,1,1,1,1] == $arr->toArray());

        $arr->map(function($x) {
            return $x + 1;
        });
        $this->assertTrue([2,2,2,2,2] == $arr->toArray());

        $arr1 = (clone $arr)->map(function($x) {
            return $x + 1;
        });

        $this->assertTrue([2,2,2,2,2] == $arr->toArray());
        $this->assertTrue([3,3,3,3,3] == $arr1->toArray());

        $arr->merge($arr1);
        $this->assertTrue([2,2,2,2,2,3,3,3,3,3] == $arr->toArray());

        $arr->replace(3, null);
        $this->assertTrue([2,2,2,2,2,null,null,null,null,null] == $arr->toArray());

        $arr->filter();
        $this->assertTrue([2,2,2,2,2] == $arr->toArray());

        $arr->replace(2, 4);
        $this->assertTrue([4,4,4,4,4] == $arr->toArray());

        $arr->unique();
        $this->assertTrue([4] == $arr->toArray());

        $arr->push(8,2,9,4,2,1);
        $this->assertTrue([4,8,2,9,4,2,1] == $arr->values()->toArray());

        $arr->sort();
        $this->assertTrue([1,2,2,4,4,8,9] == $arr->toArray());
    }
}
