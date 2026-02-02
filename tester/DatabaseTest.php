<?php

class DatabaseTest extends TestCase {
    public function testQuery() {
        $db = new Database();
        $db->query("DROP TABLE IF EXISTS test_table");
        $db->query("CREATE TABLE test_table (id INTEGER PRIMARY KEY, name TEXT)");
        $db->query("INSERT INTO test_table (name) VALUES (?)", ['Test Name'], false);

        $results = $db->query("SELECT * FROM test_table WHERE name = ?", ['Test Name']);
        $this->assertEquals(1, count($results));
        $this->assertEquals('Test Name', $results[0]['name']);
    }
}
