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

require_once __DIR__ . '/../vendor/autoload.php';

use Thojou\Ilias\Plugin\Utils\Info\IliasRootDetector;

$ILIAS_ROOT = IliasRootDetector::find(__DIR__);

if(file_exists($ILIAS_ROOT . '/libs/composer/vendor/autoload.php')) {
    require_once $ILIAS_ROOT . '/libs/composer/vendor/autoload.php';
}

chdir($ILIAS_ROOT);
