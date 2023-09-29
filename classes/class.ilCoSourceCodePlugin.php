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

require_once __DIR__ . "/../vendor/autoload.php";

class ilCoSourceCodePlugin extends ilPageComponentPlugin
{
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
     * Returns the assets folder path
     * @return string
     */
    public function assetsFolder(): string
    {
        return $this->getDirectory() . "/assets/";
    }

    /**
     * @param string $a_template
     * @param bool   $a_par1
     * @param bool   $a_par2
     * @return ilTemplate
     * @throws ilTemplateException
     */
    public function getTemplate(string $a_template, bool $a_par1 = true, bool $a_par2 = true): ilTemplate
    {
        return new ilTemplate($this->assetsFolder() . "templates/{$a_template}", $a_par1, $a_par2);
    }

    /**
     * @param string $a_mode
     * @return array<string>
     */
    public function getCssFiles(string $a_mode): array
    {
        return [
            'assets/css/source-code.css'
        ];
    }
}
