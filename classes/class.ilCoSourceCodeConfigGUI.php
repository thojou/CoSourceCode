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

use CoSourceCode\Form\SourceCodeConfigForm;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\ThemeOptionsService;

/**
 * @ilCtrl_IsCalledBy ilCoSourceCodeConfigGUI: ilUIPluginRouterGUI,ilObjComponentSettingsGUI
 */
class ilCoSourceCodeConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilCoSourceCodePlugin|ilPlugin|null
     */
    protected ?ilPlugin $plugin_object;
    private ilGlobalTemplateInterface $mainTemplate;
    private ilCtrlInterface $ctrl;

    public function __construct()
    {
        global $DIC;

        $this->mainTemplate = $DIC->ui()->mainTemplate();
        $this->ctrl = $DIC->ctrl();
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
        $form->setValuesByPost();

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

        $setting = new ilSetting($this->plugin_object->getPluginName());

        $form = new SourceCodeConfigForm(
            $this->plugin_object,
            new LanguageOptionsService($setting),
            new ThemeOptionsService($setting)
        );

        $this->mainTemplate->setContent($form->getHTML());

        return $form;
    }
}
