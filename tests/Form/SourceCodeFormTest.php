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

use CoSourceCode\Form\SourceCodeForm;
use CoSourceCode\Options\OptionsServiceInterface;
use ILIAS\DI\Container;
use ILIAS\HTTP\Services;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class SourceCodeFormTest extends TestCase
{
    public function testForm(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->prepareDIC();

        $plugin = $this->createMock(\ilCoSourceCodePlugin::class);
        $languageOptionsService = $this->createMock(OptionsServiceInterface::class);
        $themeOptionsService = $this->createMock(OptionsServiceInterface::class);

        $form = new SourceCodeForm(SourceCodeForm::MODE_CREATE, $plugin, $languageOptionsService, $themeOptionsService);
        $this->assertCount(5, $form->getItems());
    }

    public function testFormPostRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $properties = [
            'language' => 'js',
            'lineNumbers' => '1',
            'theme' => 'github',
            'srcCode' => 'console.log("Hello World!");',
            'description' => 'This is a test'
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

        $form = new SourceCodeForm(SourceCodeForm::MODE_CREATE, $plugin, $languageOptionsService, $themeOptionsService);
        $this->assertEquals(
            $properties,
            $form->getData()
        );
    }

    public function testFormWithProperties(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $properties = [
            'language' => 'js',
            'lineNumbers' => '1',
            'theme' => 'github',
            'srcCode' => 'console.log("Hello World!");',
            'description' => 'This is a test'
        ];

        $this->prepareDIC();

        $plugin = $this->createMock(\ilCoSourceCodePlugin::class);
        $languageOptionsService = $this->createMock(OptionsServiceInterface::class);
        $themeOptionsService = $this->createMock(OptionsServiceInterface::class);

        $form = new SourceCodeForm(SourceCodeForm::MODE_CREATE, $plugin, $languageOptionsService, $themeOptionsService, $properties);
        $this->assertEquals(
            $properties,
            $form->getData()
        );

        $form = new SourceCodeForm(SourceCodeForm::MODE_CREATE, $plugin, $languageOptionsService, $themeOptionsService, ["srcCode" => "\techo 'test';"]);
        $this->assertEquals(
            "    echo 'test';",
            $form->getData()['srcCode']
        );
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
