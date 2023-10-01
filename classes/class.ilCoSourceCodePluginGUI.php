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

use CoSourceCode\CodeHighlighter;
use CoSourceCode\DI\PluginContainer;
use CoSourceCode\Form\SourceCodeForm;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\OptionsServiceInterface;
use CoSourceCode\Options\ThemeOptionsService;
use Highlight\Highlighter;

use function HighlightUtilities\splitCodeIntoArray;

/**
 * @ilCtrl_isCalledBy ilCoSourceCodePluginGUI: ilPCPluggedGUI
 */
class ilCoSourceCodePluginGUI extends ilPageComponentPluginGUI
{
    /**
     * @var ilCoSourceCodePlugin&ilPageComponentPlugin
     */
    protected ilPageComponentPlugin $plugin;
    private ilCtrlInterface $ctrl;
    private ilGlobalTemplateInterface $mainTemplate;
    private CodeHighlighter $codeHighlighter;
    private OptionsServiceInterface $languageOptionsService;
    private OptionsServiceInterface $themeOptionsService;

    public function __construct()
    {
        parent::__construct();

        $this->ctrl = PluginContainer::get()->core()->ctrl();
        $this->mainTemplate = PluginContainer::get()->core()->ui()->mainTemplate();
        $this->codeHighlighter = PluginContainer::get()->getService(CodeHighlighter::class);
        $this->languageOptionsService = PluginContainer::get()->getService(LanguageOptionsService::class);
        $this->themeOptionsService = PluginContainer::get()->getService(ThemeOptionsService::class);
    }

    public function executeCommand(): void
    {
        if (method_exists($this, (string)$this->ctrl->getCmd())) {
            $this->{$this->ctrl->getCmd()}();
        }
    }

    /**
     * @throws ilCtrlException
     */
    public function insert(): void
    {
        $this->renderForm(SourceCodeForm::MODE_CREATE);
    }

    /**
     * @throws ilCtrlException
     */
    public function edit(): void
    {
        $this->renderForm(SourceCodeForm::MODE_UPDATE, $this->getProperties());
    }

    /**
     * @throws ilCtrlException
     */
    public function create(): void
    {
        $form = $this->renderForm(SourceCodeForm::MODE_CREATE);

        if (!$form->checkInput()) {
            return;
        }

        if (!$this->createElement($form->getData())) {
            return;
        }

        $this->mainTemplate->setOnScreenMessage(
            ilGlobalTemplateInterface::MESSAGE_TYPE_SUCCESS,
            $this->getPlugin()->txt("co_source_code_created"),
            true
        );
        $this->returnToParent();
    }

    /**
     * @throws ilCtrlException
     */
    public function update(): void
    {
        $form = $this->renderForm(SourceCodeForm::MODE_UPDATE, $this->getProperties());

        if (!$form->checkInput()) {
            return;
        }

        if (!$this->updateElement($form->getData())) {
            return;
        }

        $this->mainTemplate->setOnScreenMessage(
            ilGlobalTemplateInterface::MESSAGE_TYPE_SUCCESS,
            $this->getPlugin()->txt("co_source_code_updated"),
            true
        );
        $this->returnToParent();
    }

    /**
     * @param string        $a_mode
     * @param array<string> $a_properties
     * @param string        $plugin_version
     *
     * @return string
     * @throws ilSystemStyleException
     * @throws ilTemplateException
     * @throws Exception
     */
    public function getElementHTML(string $a_mode, array $a_properties, string $plugin_version): string
    {
        $this->mainTemplate->addCss(
            $this->plugin->getDirectory() . "/vendor/scrivo/highlight.php/styles/{$a_properties['theme']}.css"
        );

        $showLineNumbers = $a_properties['lineNumbers'] ?? false;
        $highlighted = $this->codeHighlighter->highlight(
            $a_properties['srcCode'],
            $a_properties['language'],
            (bool)$showLineNumbers
        );

        if ($showLineNumbers and is_array($highlighted['value'])) {
            $tpl = new ilTemplate('tpl.source_code_line_numbers.html', true, true, $this->plugin->getDirectory());

            foreach ($highlighted['value'] as $number => $row) {
                $tpl->setCurrentBlock('source_code_row');
                $tpl->setVariable('NUMBER', $number);
                $tpl->setVariable('LANGUAGE', $highlighted['language']);
                $tpl->setVariable('ROW', $row);
                $tpl->parseCurrentBlock();
            }
            $tpl->setVariable('DESCRIPTION', $a_properties['description'] ?? '');

            return $tpl->get();
        }

        $tpl = new ilTemplate('tpl.source_code.html', true, true, $this->plugin->getDirectory());

        $tpl->setCurrentBlock('source_code');
        $tpl->setVariable('LANGUAGE', $highlighted['language']);
        $tpl->setVariable('VALUE', $highlighted['value']);
        $tpl->setVariable('DESCRIPTION', $a_properties['description'] ?? '');
        $tpl->parseCurrentBlock();

        return $tpl->get();
    }


    /**
     * @param string        $mode
     * @param array<string> $properties
     *
     * @return SourceCodeForm
     * @throws ilCtrlException
     */
    private function renderForm(string $mode, array $properties = []): SourceCodeForm
    {
        $form = new SourceCodeForm(
            $mode,
            $this->plugin,
            $this->languageOptionsService,
            $this->themeOptionsService,
            $properties
        );

        $this->mainTemplate->setContent($form->getHTML());

        return $form;
    }
}
