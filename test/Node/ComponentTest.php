<?php

namespace Deniaz\Test\Terrific\Node;

use Deniaz\Terrific\Twig\Node\ComponentNode;
use \Twig_Node_Expression_Constant;
use \Twig_Node_Expression_Array;
use \stdClass;

class ComponentTest extends \Twig_Test_NodeTestCase
{
    public function testConstructor()
    {
        $expr = new Twig_Node_Expression_Constant('foo', 1);
        $node = new ComponentNode($expr, '.twig', 1);

        $this->assertNull($node->getAttribute('variant'));
        $this->assertNull($node->getAttribute('variables'));
        $this->assertEquals($expr, $node->getNode('template'));

        $vars = new Twig_Node_Expression_Array(
            [
                new Twig_Node_Expression_Constant('foo', 1),
                new Twig_Node_Expression_Constant(true, 1)
            ],
            1
        );

        $node = new ComponentNode($expr, '.twig', 1, 'variant', $vars);
        $this->assertEquals($vars, $node->getAttribute('variables'));
        $this->assertEquals('variant', $node->getAttribute('variant'));
    }

    public function getTests()
    {
        $tests = [];

        $expr = new Twig_Node_Expression_Constant('foo', 1);
        $node = new ComponentNode($expr, '.twig', 1);
        $expected = <<<EOF
// line 1
\$this->env->loadTemplate("foo/foo.twig")->display(\$context);
EOF;
        $tests[] = [$node, $expected];

        $expr = new Twig_Node_Expression_Constant('foo', 1);
        $node = new ComponentNode($expr, '.twig', 1, 'bar');
        $expected = <<<EOF
// line 1
\$this->env->loadTemplate("foo/foo-bar.twig")->display(\$context);
EOF;
        $tests[] = [$node, $expected];

        return $tests;
    }
}
