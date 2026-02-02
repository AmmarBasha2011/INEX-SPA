<?php

class SessionTest extends TestCase
{
    public function testMakeAndGet()
    {
        Session::make('user_id', 123);
        $this->assertEquals(123, Session::get('user_id'));
    }

    public function testDelete()
    {
        Session::make('temp', 'value');
        Session::delete('temp');
        $this->assertNull(Session::get('temp'));
    }

    public function testComplexData()
    {
        $data = ['name' => 'John', 'roles' => ['admin', 'editor']];
        Session::make('user_data', $data);
        $this->assertEquals($data, Session::get('user_data'));
    }
}
