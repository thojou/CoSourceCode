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

use CoSourceCode\DI\PluginContainer;
use CoSourceCode\Form\SourceCodeConfigForm;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\OptionsServiceInterface;
use CoSourceCode\Options\ThemeOptionsService;

/**
 * @ilCtrl_IsCalledBy ilCoSourceCodeConfigGUI: ilUIPluginRouterGUI,ilObjComponentSettingsGUI
 */
class ilCoSourceCodeConfigGUI extends ilPluginConfigGUI
{
    private ilGlobalTemplateInterface $mainTemplate;
    private ilCtrlInterface $ctrl;
    private OptionsServiceInterface $languageOptionsService;
    private OptionsServiceInterface $themeOptionsService;

    public function __construct()
    {
        $this->mainTemplate = PluginContainer::get()->core()->ui()->mainTemplate();
        $this->ctrl = PluginContainer::get()->core()->ctrl();
        $this->languageOptionsService = PluginContainer::get()->getService(LanguageOptionsService::class);
        $this->themeOptionsService = PluginContainer::get()->getService(ThemeOptionsService::class);
    }

    public function performCommand(string $cmd): void
    {
        if (method_exists($this, $cmd)) {
            $this->$cmd();
        }
    }

    public function configure(): void
    {
        $this->renderForm();
    }

    public function save(): void
    {
        $form = $this->renderForm();

        if (!$form->checkInput()) {
            return;
        }

        $form->save();

        $this->ctrl->redirect($this, 'configure');
    }

    private function renderForm(): SourceCodeConfigForm
    {
        if (!$this->plugin_object) {
            throw new LogicException('Plugin object not set');
        }

        $form = new SourceCodeConfigForm(
            $this->plugin_object,
            $this->languageOptionsService,
            $this->themeOptionsService
        );

        $this->mainTemplate->setContent($form->getHTML());

        return $form;
    }
}
