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

use function in_array;
use function json_encode;
use function usort;

abstract class BaseOptionsService implements OptionsServiceInterface
{
    protected \ilSetting $ilSetting;

    public function __construct(
        \ilSetting $ilSetting
    ) {
        $this->ilSetting = $ilSetting;
    }

    /**
     * @inheritcoc
     */
    public function loadAll(): array
    {
        $options = $this->getAvailableOptions();
        $actives = $this->loadActives();

        return $this->sort($options, $actives);
    }

    /**
     * @inheritcoc
     */
    public function loadActives(): array
    {
        $storedOptions = $this->ilSetting->get($this->getSettingsKey());

        if(!$storedOptions) {
            return $this->getDefaultActives();
        }

        $result = json_decode($storedOptions);
        if(!is_array($result)) {
            return $this->getDefaultActives();
        }

        return $result;
    }

    /**
     * @inheritcoc
     */
    public function setActives(array $actives): void
    {
        $this->ilSetting->set($this->getSettingsKey(), (string)json_encode($actives));
    }

    /**
     * @inheritcoc
     */
    public function setDefault(string $default): void
    {
        $this->ilSetting->set($this->getSettingsKey() . '_default', $default);
    }

    /**
     * @inheritcoc
     */
    public function getDefault(): string
    {
        return $this->ilSetting->get($this->getSettingsKey() . '_default') ?? $this->getDefaultFallback();
    }

    /**
     * Sort the available options to set all active options to the beginning.
     *
     * @param array<string> $available The list of all available options
     * @param array<string> $actives The list of all active options
     *
     * @return array<string> The sorted list of all available options
     */
    private function sort(array $available, array $actives): array
    {
        usort($available, function ($a, $b) use ($actives) {
            $aActive = in_array($a, $actives, true);
            $bActive = in_array($b, $actives, true);
            if($aActive && !$bActive) {
                return -1;
            }
            if(!$aActive && $bActive) {
                return 1;
            }

            return $a <=> $b;
        });

        return $available;
    }

    /**
     * Get the raw list of all available options.
     *
     * @return array<string>
     */
    abstract protected function getAvailableOptions(): array;

    /**
     * Get the settings key, which is used to save configuration inside the ilSetting.
     *
     * @return string
     */
    abstract protected function getSettingsKey(): string;

    /**
     * Get a default fallback selection if non is present in ilSetting.
     *
     * @return string
     */
    abstract protected function getDefaultFallback(): string;

    /**
     * Get a list of default active options if non is present in ilSetting.
     *
     * @return array<string>
     */
    abstract protected function getDefaultActives(): array;
}
