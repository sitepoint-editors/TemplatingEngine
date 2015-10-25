<?php

namespace SitePoint\TemplatingEngine\Test;

use SitePoint\TemplatingEngine\TemplatingEngine;

class TemplatingEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testEngine()
    {
        $engine = new TemplatingEngine(['test' => __DIR__.'/templates/valid']);

        // Test exists()
        $this->assertTrue($engine->exists('test::first'));
        $this->assertFalse($engine->exists('foo::bar'));

        // Test render()
        $result = $engine->render('test::first', ['title' => 'Hello World']);

        $expectedResult = <<<EOT
<html>
    <head><title>Middle Hello World</title></head>
    <body>
        Partial Block
        Middle First
    </body>
</html>
EOT;

        $this->assertEquals(
            str_replace([' ', PHP_EOL], '', $expectedResult),
            str_replace([' ', PHP_EOL], '', $result)
        );
    }

    /**
     * @expectedException        SitePoint\TemplatingEngine\Exception\TemplateNotFoundException
     * @expectedExceptionMessage namespace has not been registered
     */
    public function testUnregisteredNamespace()
    {
        $engine = new TemplatingEngine();
        $result = $engine->render('foo::bar');
    }

    /**
     * @expectedException        SitePoint\TemplatingEngine\Exception\TemplateNotFoundException
     * @expectedExceptionMessage There is no template at the path
     */
    public function testTemplateDoesNotExist()
    {
        $engine = new TemplatingEngine(['foo' => __DIR__.'/templates/invalid']);
        $result = $engine->render('foo::bar');
    }

    public function invalidTemplateNameProvider()
    {
        return [
            [':bar'],
            ['::bar'],
            ['foo:bar'],
            ['foo'],
        ];
    }

    /**
     * @dataProvider      invalidTemplateNameProvider
     * @expectedException SitePoint\TemplatingEngine\Exception\InvalidTemplateNameException
     */
    public function testInvalidTemplateName($name)
    {
        $engine = new TemplatingEngine();
        $result = $engine->render($name);
    }

    /**
     * @expectedException        SitePoint\TemplatingEngine\Exception\EngineException
     * @expectedExceptionMessage block has not been defined
     */
    public function testUndefinedBlock()
    {
        $engine = new TemplatingEngine(['test' => __DIR__.'/templates/invalid']);
        $result = $engine->render('test::undefined-block');
    }

    /**
     * @expectedException        SitePoint\TemplatingEngine\Exception\EngineException
     * @expectedExceptionMessage parent template has already been defined
     */
    public function testDoubleParent()
    {
        $engine = new TemplatingEngine(['test' => __DIR__.'/templates/invalid']);
        $result = $engine->render('test::double-parent');
    }
}
