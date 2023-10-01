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

namespace CoSourceCode\Tests;

use ilComponentFactory;
use ilCtrl;
use ilDBInterface;
use ilGlobalTemplate;
use ILIAS\Data\Factory as DataFactory;
use ILIAS\HTTP\Services as HttpService;
use ILIAS\HTTP\Wrapper\WrapperFactory;
use ILIAS\Refinery\Factory as Refinery;
use ILIAS\Tests\Refinery\TestCase;
use ilLanguage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractGUITest extends TestCase implements ContainerMockHelperInterface
{
    use ContainerMockHelperTrait;

    private Refinery $refinery;

    /**
     * @var ilGlobalTemplate&MockObject
     */
    private ilGlobalTemplate $tpl;

    /**
     * @var ilCtrl&MockObject
     */
    private ilCtrl $ctrl;

    /**
     * @var ilLanguage&MockObject
     */
    private MockObject $language;

    /**
     * @var ilComponentFactory&MockObject
     */
    private MockObject $componentFactory;

    /**
     * @var HttpService&MockObject
     */
    private HttpService $http;

    /**
     * @var ServerRequestInterface&MockObject
     */
    private ServerRequestInterface $request;

    /**
     * @var ilDBInterface&object&MockObject
     */
    private ilDBInterface $db;

    /**
     * @return Refinery
     */
    public function registerRefinery(): Refinery
    {
        $this->refinery = new Refinery(
            new DataFactory(),
            $this->language ?? $this->registerLanguage()
        );

        $this->mockCoreService('refinery', $this->refinery);

        return $this->refinery;
    }

    /**
     * @return ilLanguage&MockObject
     */
    public function registerLanguage(): ilLanguage
    {
        $this->language = $this->createMock(ilLanguage::class);

        $this->mockCoreService('lng', $this->language);

        return $this->language;
    }

    /**
     * @return ilGlobalTemplate&MockObject
     */
    public function registerTemplate(): ilGlobalTemplate
    {
        $this->tpl = $this->createMock(ilGlobalTemplate::class);

        $this->mockCoreService('tpl', $this->tpl);

        return $this->tpl;
    }

    /**
     * @return ilCtrl&MockObject
     */
    public function registerCtrl(): ilCtrl
    {
        $this->ctrl = $this->createMock(ilCtrl::class);

        $this->mockCoreService('ilCtrl', $this->ctrl);

        return $this->ctrl;
    }

    /**
     * @return ilComponentFactory&MockObject
     */
    public function registerComponentFactory(): ilComponentFactory
    {
        $this->componentFactory = $this->createMock(ilComponentFactory::class);

        $this->mockCoreService('component.factory', $this->componentFactory);

        return $this->componentFactory;
    }

    /**
     * @return ilDBInterface&MockObject
     */
    public function registerDatabase(): ilDBInterface
    {
        $this->db = $this->createMock(ilDBInterface::class);

        $this->mockCoreService('ilDB', $this->db);

        return $this->db;
    }

    /**
     * @return HttpService&MockObject
     */
    public function registerHttp(): HttpService
    {
        $this->http = $this->createMock(HttpService::class);

        $this->mockCoreService('http', $this->http);

        return $this->http;
    }

    /**
     * @param mixed $expectedContent
     */
    public function expectTplContent($expectedContent): void
    {
        $this->tpl->expects($this->once())->method('setContent')->with($expectedContent);

    }

    /**
     * @param string $command
     *
     * @return void
     */
    public function mockCommand(string $command): void
    {
        $this->ctrl->method('getCmd')->willReturn($command);
    }

    /**
     * @param InvocationOrder $expects
     * @param string          $method
     *
     * @return void
     */
    public function expectRedirect(InvocationOrder $expects, string $method): void
    {
        $this->ctrl->expects($expects)
            ->method('redirect')
            ->with($this->equalTo($method));
    }

    /**
     * @param array $properties
     * @param array $queryParameters
     *
     * @return void
     */
    public function mockPostRequest(array $properties, array $queryParameters = []): void
    {
        $this->http ?? $this->registerHttp();
        $this->request = $this->createMock(ServerRequestInterface::class);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->request->method('getServerParams')->willReturn(['REQUEST_METHOD' => 'POST']);
        $this->request->method('getParsedBody')->willReturn($properties);
        $this->request->method('getQueryParams')->willReturn($queryParameters);
        $this->http->method('request')->willReturn($this->request);
        $this->http->method('wrapper')->willReturn(new WrapperFactory($this->request));
    }

    public function setupGUICommons(): void
    {
        $this->registerLanguage();
        $this->registerTemplate();
        $this->registerCtrl();
        $this->registerRefinery();
        $this->registerHttp();
        $this->registerComponentFactory();
    }
}
