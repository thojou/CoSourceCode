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

namespace CoSourceCode\Form;

use CoSourceCode\Options\OptionsServiceInterface;
use ilCoSourceCodeConfigGUI;
use ilMultiSelectInputGUI;
use ilPlugin;
use ilPropertyFormGUI;
use ilSelectInputGUI;

use function array_combine;
use function method_exists;
use function var_dump;

class SourceCodeConfigForm extends ilPropertyFormGUI
{
    private ilPlugin $plugin;
    private OptionsServiceInterface $languageOptionsService;
    private OptionsServiceInterface $themeOptionsService;

    public function __construct(
        ilPlugin $plugin,
        OptionsServiceInterface $languageOptionsService,
        OptionsServiceInterface $themeOptionsService
    ) {
        parent::__construct();

        $this->plugin = $plugin;
        $this->languageOptionsService = $languageOptionsService;
        $this->themeOptionsService = $themeOptionsService;

        $languages = $this->languageOptionsService->loadAll();
        $activeLanguagesSelectionInput = new ilMultiSelectInputGUI(
            $this->plugin->txt("language_actives"),
            "language_actives"
        );
        $activeLanguagesSelectionInput->setOptions(array_combine($languages, $languages));
        $activeLanguagesSelectionInput->setValue($this->languageOptionsService->loadActives());
        $activeLanguagesSelectionInput->setHeight(400);

        $activeLanguages = $this->languageOptionsService->loadActives();
        $languageDefault = new ilSelectInputGUI($this->plugin->txt("language_default"), "language_default");
        $languageDefault->setOptions(array_combine($activeLanguages, $activeLanguages));
        $languageDefault->setValue($this->languageOptionsService->getDefault());

        $themes = $this->themeOptionsService->loadAll();
        $activeThemesSelectionInput = new ilMultiSelectInputGUI($this->plugin->txt("theme_actives"), "theme_actives");
        $activeThemesSelectionInput->setOptions(array_combine($themes, $themes));
        $activeThemesSelectionInput->setValue($this->themeOptionsService->loadActives());
        $activeThemesSelectionInput->setHeight(400);

        $activeThemes = $this->themeOptionsService->loadActives();
        $themeDefault = new ilSelectInputGUI($this->plugin->txt("theme_default"), "theme_default");
        $themeDefault->setOptions(array_combine($activeThemes, $activeThemes));
        $themeDefault->setValue($this->themeOptionsService->getDefault());

        $this->addItem($activeLanguagesSelectionInput);
        $this->addItem($languageDefault);
        $this->addItem($activeThemesSelectionInput);
        $this->addItem($themeDefault);

        $this->setFormAction($this->ctrl->getFormActionByClass(ilCoSourceCodeConfigGUI::class, 'save'));
        $this->addCommandButton('save', $this->plugin->txt('save'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->setValuesByPost();
        }
    }

    public function save(): void
    {
        $this->languageOptionsService->setActives((array)$this->getItemValue('language_actives'));
        $this->languageOptionsService->setDefault((string)$this->getItemValue('language_default')); // @phpstan-ignore-line
        $this->themeOptionsService->setActives((array)$this->getItemValue('theme_actives'));
        $this->themeOptionsService->setDefault((string)$this->getItemValue('theme_default')); // @phpstan-ignore-line
    }

    /**
     * @param string $postVar
     *
     * @return null|string|array<string>
     */
    private function getItemValue(string $postVar)
    {
        $item = $this->getItemByPostVar($postVar);

        return $item->getValue(); // @phpstan-ignore-line
    }
}
