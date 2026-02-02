<?php

class WebhookTest extends TestCase {
    public function testInvalidUrl() {
        $this->assertFalse(Webhook::send('invalid-url'));
    }
}
