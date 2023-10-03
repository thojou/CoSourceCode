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

use Thojou\Ilias\Plugin\Utils\Info\PluginInfo;

$pluginInfo = PluginInfo::resolve(__DIR__ . '/composer.json');
extract($pluginInfo);
