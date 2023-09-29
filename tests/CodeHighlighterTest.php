<?php

declare(strict_types=1);

/*
 * This file is part of the CoSourceCode Plugin for ILIAS.
 *
 * (c) Thomas Joußen <tjoussen@databay.de>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoSourceCode\Tests;

use CoSourceCode\CodeHighlighter;
use Highlight\Highlighter;
use PHPUnit\Framework\TestCase;

class CodeHighlighterTest extends TestCase
{
    public function testInstance(): void
    {
        $highlighter = new CodeHighlighter();

        $this->assertInstanceOf(CodeHighlighter::class, $highlighter);
    }

    public function testHighlight(): void
    {
        $highlighter = new CodeHighlighter();
        $highlighted = $highlighter->highlight('echo "Hello World";', 'php');
        $this->assertEquals(
            '<span class="hljs-keyword">echo</span> <span class="hljs-string">"Hello World"</span>;',
            $highlighted['value']
        );
        $this->assertEquals(
            'php',
            $highlighted['language']
        );
    }

    public function testHighlightSplit(): void
    {
        $highlighter = new CodeHighlighter();
        $highlighted = $highlighter->highlight("echo 'Hello';\necho 'World';", 'php', true);
        $this->assertEquals(
            [
                '<span class="hljs-keyword">echo</span> <span class="hljs-string">\'Hello\'</span>;',
                '<span class="hljs-keyword">echo</span> <span class="hljs-string">\'World\'</span>;',
            ],
            $highlighted['value']
        );
    }

    public function testFallbackOnException(): void
    {
        $hl = $this->createMock(Highlighter::class);
        $hl->expects($this->once())
            ->method('highlight')
            ->willThrowException(new \DomainException());

        $highlighter = new CodeHighlighter($hl);
        $highlighted = $highlighter->highlight('echo "Hello World";', 'php');

        $this->assertEquals(
            'echo &quot;Hello World&quot;;',
            $highlighted['value']
        );
    }

}
