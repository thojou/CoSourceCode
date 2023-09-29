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

use function HighlightUtilities\getAvailableStyleSheets;

class ThemeOptionsService extends BaseOptionsService
{
    /**
     * @inheritcoc
     */
    protected function getAvailableOptions(): array
    {
        return getAvailableStyleSheets();
    }

    /**
     * @inheritcoc
     */
    protected function getSettingsKey(): string
    {
        return 'theme';
    }

    /**
     * @inheritcoc
     */
    protected function getDefaultFallback(): string
    {
        return 'atom-one-dark';
    }

    /**
     * @inheritcoc
     */
    protected function getDefaultActives(): array
    {
        return [
            'atom-one-dark',
            'atom-one-light'
        ];
    }
}
