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

use function array_map;
use function file_exists;
use function is_a;
use function is_array;

class PluginInfo
{
    /**
     * @param string $composerJsonPath
     *
     * @return array<string, string>
     */
    public static function resolve(string $composerJsonPath): array
    {
        if(!file_exists($composerJsonPath)) {
            throw new \InvalidArgumentException('composer.json not found at ' . $composerJsonPath);
        }

        $composerJson = (array)json_decode((string)file_get_contents($composerJsonPath), true);

        if(!is_array($composerJson['authors'])) {
            throw new \InvalidArgumentException('composer.json does not contain authors information');
        }

        if(!is_array($composerJson['extra'])) {
            throw new \InvalidArgumentException('composer.json does not contain extra information');
        }

        if(!is_array($composerJson['extra']['ilias-plugin'])) {
            throw new \InvalidArgumentException('composer.json does not contain ilias-plugin information');
        }

        $authorInfo = $composerJson['authors'];
        $pluginInfo = $composerJson['extra']['ilias-plugin'];

        if(!isset($pluginInfo['id'])) {
            throw new \InvalidArgumentException('composer.json does not contain ilias-plugin.id information');
        }

        if(!isset($pluginInfo['version'])) {
            throw new \InvalidArgumentException('composer.json does not contain ilias-plugin.version information');
        }

        if(!isset($pluginInfo['min_version'])) {
            throw new \InvalidArgumentException('composer.json does not contain ilias-plugin.ilias_min_version information');
        }

        if(!isset($pluginInfo['max_version'])) {
            throw new \InvalidArgumentException('composer.json does not contain ilias-plugin.ilias_max_version information');
        }

        return [
            'id' => $pluginInfo['id'],
            'version' => $pluginInfo['version'],
            'ilias_min_version' => $pluginInfo['min_version'],
            'ilias_max_version' => $pluginInfo['max_version'],
            'responsible' => join(", ", array_map(fn (array $author) => $author['name'], $authorInfo)),
            'responsible_mail' => join(", ", array_map(fn (array $author) => $author['email'], $authorInfo)),
        ];
    }

}
