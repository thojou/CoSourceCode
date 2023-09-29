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

namespace CoSourceCode;

use DomainException;
use Exception;
use Highlight\Highlighter;

use function explode;
use function HighlightUtilities\splitCodeIntoArray;
use function html_entity_decode;
use function htmlentities;

class CodeHighlighter
{
    private Highlighter $highlighter;

    public function __construct(
        ?Highlighter $highlighter = null
    ) {
        $this->highlighter = $highlighter ?? new Highlighter();
    }

    /**
     * @param string $code
     * @param string $language
     * @param bool   $splitLines
     *
     * @return array{language: string, value: string|array<string>}
     *
     * @throws Exception
     */
    public function highlight(string $code, string $language = 'php', bool $splitLines = false): array
    {
        $code = html_entity_decode($code);

        try {
            $highlighted = $this->highlighter->highlight($language, $code);

            return [
                'language' => $highlighted->language,
                'value' => $splitLines ? splitCodeIntoArray($highlighted->value) : $highlighted->value
            ];
        } catch (DomainException $e) {
            $code = htmlentities($code);
            return [
                'language' => $language,
                'value' => $splitLines ? explode("\n", $code) : $code
            ];
        }
    }
}
