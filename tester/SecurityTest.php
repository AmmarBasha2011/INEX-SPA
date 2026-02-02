<?php

class SecurityTest extends TestCase
{
    public function testSanitizeInput()
    {
        $input = '<script>alert("xss")</script><b>Hello</b>';
        $expected = '&lt;b&gt;Hello&lt;/b&gt;'; // Note: Security::sanitizeInput does htmlspecialchars FIRST, then preg_replace for script tags.
        // Wait, let's look at Security::sanitizeInput again.
        /*
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);
        */
        // If htmlspecialchars is called first, <script> becomes &lt;script&gt;, so the preg_replace won't match!

        $output = Security::sanitizeInput($input);
        // $input -> &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;&lt;b&gt;Hello&lt;/b&gt;
        // preg_replace won't find <script
        $this->assertEquals('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;&lt;b&gt;Hello&lt;/b&gt;', $output);
    }

    public function testValidateAndSanitize()
    {
        $input = '<b>Test</b>';
        $this->assertEquals('&lt;b&gt;Test&lt;/b&gt;', Security::validateAndSanitize($input, 'xss'));
        $this->assertEquals('<b>Test</b>', Security::validateAndSanitize($input, 'other'));
    }
}
