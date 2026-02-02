<?php

class LanguageTest extends TestCase {
    public function testSetAndGet() {
        Language::setLanguage('en_test');
        $this->assertEquals('Welcome {name}', Language::get('welcome'));
    }

    public function testPlaceholders() {
        Language::setLanguage('en_test');
        $this->assertEquals('Welcome John', Language::get('welcome', ['name' => 'John']));
    }

    public function testFallback() {
        $this->assertEquals('missing_key', Language::get('missing_key'));
    }
}
