<?php

$header = <<<TXT
This file is part of the CoSourceCode Plugin for ILIAS.

(c) Thomas Joußen <tjoussen@databay.de>
 
This source file is subject to the GPL-3.0 license that is bundled
with this source code in the file LICENSE.
TXT;

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'header_comment' => ['header' => $header],
        'strict_param' => true,
    ])
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setFinder($finder);