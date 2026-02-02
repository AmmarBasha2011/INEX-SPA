<?php

class ValidationTest extends TestCase {
    public function testIsEmail() {
        $this->assertTrue(Validation::isEmail('test@example.com'));
        $this->assertFalse(Validation::isEmail('invalid-email'));
    }

    public function testIsTextLength() {
        $this->assertTrue(Validation::isTextLength('abc', 5));
        $this->assertFalse(Validation::isTextLength('abcdef', 5));
    }

    public function testIsMinTextLength() {
        $this->assertTrue(Validation::isMinTextLength('abcde', 5));
        $this->assertFalse(Validation::isMinTextLength('abcd', 5));
    }

    public function testIsSubDomain() {
        $this->assertTrue(Validation::isSubDomain('sub.example.com'));
        $this->assertFalse(Validation::isSubDomain('example.com'));
    }

    public function testIsSubDir() {
        $this->assertTrue(Validation::isSubDir('http://example.com/subdir'));
        $this->assertFalse(Validation::isSubDir('http://example.com'));
    }

    public function testIsDomain() {
        $this->assertTrue(Validation::isDomain('example.com'));
        $this->assertFalse(Validation::isDomain('not a domain'));
    }

    public function testIsEndWith() {
        $this->assertTrue(Validation::isEndWith('hello world', ['world', 'earth']));
        $this->assertFalse(Validation::isEndWith('hello world', ['hello', 'earth']));
    }

    public function testIsStartWith() {
        $this->assertTrue(Validation::isStartWith('hello world', ['hello', 'hi']));
        $this->assertFalse(Validation::isStartWith('hello world', ['world', 'hi']));
    }

    public function testIsNumber() {
        $this->assertTrue(Validation::isNumber('123'));
        $this->assertTrue(Validation::isNumber(123.45));
        $this->assertFalse(Validation::isNumber('abc'));
    }

    public function testIsBool() {
        $this->assertTrue(Validation::isBool(true));
        $this->assertTrue(Validation::isBool('true'));
        $this->assertTrue(Validation::isBool(1));
        $this->assertFalse(Validation::isBool('notbool'));
    }
}
