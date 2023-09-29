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

namespace CoSourceCode\Options;

use Highlight\Highlighter;

/**
 * @author Thomas Joußen <tjoussen@databay.de>
 */
class LanguageOptionsService extends BaseOptionsService
{
    /**
     * @inheritcoc
     */
    protected function getAvailableOptions(): array
    {
        Highlighter::registerAllLanguages();

        return Highlighter::listRegisteredLanguages(true);
    }

    /**
     * @inheritcoc
     */
    protected function getSettingsKey(): string
    {
        return 'language';
    }

    /**
     * @inheritcoc
     */
    protected function getDefaultFallback(): string
    {
        return 'php';
    }

    /**
     * @inheritcoc
     */
    protected function getDefaultActives(): array
    {
        return [
            'bash',
            'c',
            'c++',
            'css',
            'go',
            'html',
            'js',
            'java',
            'php',
            'python',
            'ruby',
            'vb',
            'xml'
        ];
    }
}
