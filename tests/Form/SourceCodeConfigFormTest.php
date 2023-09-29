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

namespace CoSourceCode\Tests\Form;

use CoSourceCode\Form\SourceCodeConfigForm;
use CoSourceCode\Options\OptionsServiceInterface;
use ilComponentFactory;
use ILIAS\DI\Container;
use ILIAS\HTTP\Services;
use ILIAS\UI\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

use function mail;
use function var_dump;

class SourceCodeConfigFormTest extends TestCase
{
    public function testForm(): void
    {
        $this->prepareDIC();

        $plugin = $this->createMock(\ilCoSourceCodePlugin::class);
        $languageOptionsService = $this->createMock(OptionsServiceInterface::class);
        $themeOptionsService = $this->createMock(OptionsServiceInterface::class);

        $form = new SourceCodeConfigForm($plugin, $languageOptionsService, $themeOptionsService);
        $this->assertCount(4, $form->getItems());
    }

    public function testFormPostRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $properties = [
            'language_actives' => ['php', 'js'],
            'language_default' => 'js',
            'theme_actives' => ['atom-one-dark', 'atom-one-light'],
            'theme_default' => 'atom-one-light',
        ];

        $DIC = $this->prepareDIC();

        $request = $this->createMock(ServerRequestInterface::class);
        $http = $this->createMock(Services::class);

        $request
            ->method('getParsedBody')
            ->willReturn($properties);

        $http
            ->method('request')
            ->willReturn($request);

        $DIC->method('http')
            ->willReturn($http);
        $DIC->method('offsetExists')
            ->willReturnCallback(function (string $key) {
                switch($key) {
                    case 'http':
                        return true;
                    default:
                        return false;
                }
            });

        $plugin = $this->createMock(\ilCoSourceCodePlugin::class);
        $languageOptionsService = $this->createMock(OptionsServiceInterface::class);
        $themeOptionsService = $this->createMock(OptionsServiceInterface::class);

        $languageOptionsService
            ->expects($this->once())
            ->method('setActives')
            ->with($this->equalTo(['php', 'js']));

        $languageOptionsService
            ->expects($this->once())
            ->method('setDefault')
            ->with($this->equalTo('js'));

        $themeOptionsService
            ->expects($this->once())
            ->method('setActives')
            ->with($this->equalTo(['atom-one-dark', 'atom-one-light']));

        $themeOptionsService
            ->expects($this->once())
            ->method('setDefault')
            ->with($this->equalTo('atom-one-light'));

        $form = new SourceCodeConfigForm($plugin, $languageOptionsService, $themeOptionsService);
        $form->save();
    }

    /**
     * @return (Container&MockObject)
     */
    private function prepareDIC()
    {
        global $DIC;

        $DIC = $this->createMock(Container::class);
        $DIC
            ->method('language')
            ->willReturn($this->createMock(\ilLanguage::class));

        $DIC
            ->method('ctrl')
            ->willReturn($this->createMock(\ilCtrl::class));

        return $DIC;
    }

}
