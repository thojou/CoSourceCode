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

interface OptionsServiceInterface
{
    /**
     * Get a list of all available options.
     *
     * @return array<string>
     */
    public function loadAll(): array;

    /**
     * Get a list of all activated options.
     *
     * @return array<string>
     */
    public function loadActives(): array;

    /**
     * Set the list of all activated options.
     *
     * @param array<string> $actives
     *
     * @return void
     */
    public function setActives(array $actives): void;

    /**
     * Set the default option.
     *
     * @param string $default
     *
     * @return void
     */
    public function setDefault(string $default): void;

    /**
     * Get the default option.
     *
     * @return string
     */
    public function getDefault(): string;
}
