<?php

class AhmedTemplateTest extends TestCase
{
    private $template;

    public function __construct()
    {
        $this->template = new AhmedTemplate();
    }

    public function testVariableEcho()
    {
        $content = 'Hello {{ $name }}';
        $parsed = $this->parse($content);
        $this->assertEquals('Hello <?= htmlentities($name) ?>', $parsed);
    }

    public function testIfDirective()
    {
        $content = '@if($test) Yes @endif';
        $parsed = $this->parse($content);
        $this->assertEquals('<?php if ($test): ?> Yes <?php endif; ?>', $parsed);
    }

    public function testForeachDirective()
    {
        $content = '@foreach($items as $item) {{ $item }} @endforeach';
        $parsed = $this->parse($content);
        $this->assertEquals('<?php foreach ($items as $item): ?> <?= htmlentities($item) ?> <?php endforeach; ?>', $parsed);
    }

    public function testRender()
    {
        $tmpFile = __DIR__.'/test_template.ahmed.php';
        file_put_contents($tmpFile, 'Hello {{ $name }}');

        $output = $this->template->render($tmpFile, ['name' => 'World']);
        unlink($tmpFile);

        $this->assertEquals('Hello World', $output);
    }

    private function parse($content)
    {
        $reflection = new ReflectionClass('AhmedTemplate');
        $method = $reflection->getMethod('parse');
        $method->setAccessible(true);

        return $method->invoke($this->template, $content);
    }
}
