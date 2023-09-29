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

namespace CoSourceCode\DI;

use ILIAS\DI\Container;

use function is_array;
use function is_callable;
use function is_object;
use function is_resource;
use function is_scalar;

class PluginContainer
{
    private static ?PluginContainer $instance = null;
    private Container $dic;

    private string $pluginId;

    public static function init(Container $dic, string $pluginId): self
    {
        if(!self::$instance) {
            self::$instance = new self($dic, $pluginId);
        }

        return self::$instance;
    }

    public static function get(): self
    {
        if(!self::$instance) {
            throw new \RuntimeException('PluginContainer not initialized');
        }
        return self::$instance;
    }

    private function __construct(Container $dic, string $pluginId)
    {
        $this->pluginId = $pluginId;
        $this->dic = $dic;
    }

    public function core(): Container
    {
        return $this->dic;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $key
     *
     * @return T
     */
    public function getService(string $key): object
    {
        $service = $this->dic[$this->pluginId . '.'  . $key];

        if(!is_object($service)) {
            throw new \RuntimeException("Service $key not found for plugin $this->pluginId");
        }

        return $service; //@phpstan-ignore-line
    }

    /**
     * @param string   $key
     * @param callable $registerFunction
     *
     * @return self
     */
    public function register(string $key, callable $registerFunction): self
    {
        $this->dic[$this->pluginId . '.' . $key] = $registerFunction;

        return $this;
    }

}
