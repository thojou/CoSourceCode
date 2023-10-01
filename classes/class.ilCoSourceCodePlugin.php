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

use CoSourceCode\CodeHighlighter;
use CoSourceCode\DI\PluginContainer;
use CoSourceCode\Form\SourceCodeForm;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\ThemeOptionsService;
use Highlight\Highlighter;

require_once __DIR__ . "/../vendor/autoload.php";

class ilCoSourceCodePlugin extends ilPageComponentPlugin
{
    public const PLUGIN_ID = 'cosrc';

    private static bool $IS_INITIALIZED = false;

    protected function init(): void
    {
        if (self::$IS_INITIALIZED) {
            return;
        }

        global $DIC;

        PluginContainer::init($DIC, self::PLUGIN_ID)
            ->register(
                ilSetting::class,
                static fn () => new ilSetting(self::PLUGIN_ID)
            )->register(
                LanguageOptionsService::class,
                static fn ($c) => new LanguageOptionsService($c[self::PLUGIN_ID . '.' . ilSetting::class])
            )->register(
                ThemeOptionsService::class,
                static fn ($c) => new ThemeOptionsService($c[self::PLUGIN_ID . '.' . ilSetting::class])
            )->register(
                CodeHighlighter::class,
                static fn ($c) => new CodeHighlighter()
            );

        self::$IS_INITIALIZED = true;
    }

    public static function getPluginDir(): string
    {
        return 'Customizing/global/plugins/Services/COPage/PageComponent/CoSourceCode';
    }

    /**
     * @inheritDoc
     */
    public function isValidParentType(string $a_type): bool
    {
        return true;
    }

    /**
     * @param string $a_mode
     *
     * @return array<string>
     */
    public function getCssFiles(string $a_mode): array
    {
        return [
            'assets/css/source-code.css'
        ];
    }
}
