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
use Highlight\Highlighter;
use ilCheckboxInputGUI;
use ilCoSourceCodePlugin;
use ilCoSourceCodePluginGUI;
use ilCtrlException;
use ilPropertyFormGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;

use function array_combine;
use function array_fill;
use function HighlightUtilities\getAvailableStyleSheets;
use function preg_replace;
use function sort;

class SourceCodeForm extends ilPropertyFormGUI
{
    public const MODE_CREATE = "create";
    public const MODE_UPDATE = "update";

    public const TAB_SIZE = 4;

    protected ilCoSourceCodePlugin $plugin;

    /**
     * MarkdownForm constructor.
     *
     * @param string                  $mode
     * @param ilCoSourceCodePlugin    $plugin
     * @param OptionsServiceInterface $languageOptionsService
     * @param OptionsServiceInterface $themeOptionsService
     * @param string[]                $properties
     *
     * @throws ilCtrlException
     */
    public function __construct(
        string $mode,
        ilCoSourceCodePlugin $plugin,
        OptionsServiceInterface $languageOptionsService,
        OptionsServiceInterface $themeOptionsService,
        array $properties = []
    ) {
        parent::__construct();
        $this->plugin = $plugin;
        $this->setTitle($plugin->txt("co_source_code"));

        $languages = $languageOptionsService->loadActives();
        $languageInput = new ilSelectInputGUI($this->plugin->txt("language"), "language");
        $languageInput->setOptions(array_combine($languages, $languages));
        $languageInput->setRequired(true);
        $languageInput->setValue($languageOptionsService->getDefault());

        $themes = $themeOptionsService->loadActives();
        $themeInput = new ilSelectInputGUI($this->plugin->txt("theme"), "theme");
        $themeInput->setOptions(array_combine($themes, $themes));
        $themeInput->setRequired(true);
        $themeInput->setValue($themeOptionsService->getDefault());

        $lineNumbersInput = new ilCheckboxInputGUI($this->plugin->txt("line_numbers"), "lineNumbers");
        $lineNumbersInput->setChecked(false);

        $srcCodeInput = new ilTextAreaInputGUI($this->plugin->txt("text"), "srcCode");
        $srcCodeInput->setRequired(true);
        $srcCodeInput->setUseRte(false);
        $srcCodeInput->setRows(20);

        $descriptionInput = new ilTextInputGUI($this->plugin->txt("description"), "description");
        $descriptionInput->setRequired(false);

        $this->addItem($languageInput);
        $this->addItem($themeInput);
        $this->addItem($lineNumbersInput);
        $this->addItem($srcCodeInput);
        $this->addItem($descriptionInput);
        $this->setFormAction($this->ctrl->getFormActionByClass(ilCoSourceCodePluginGUI::class, $mode));
        $this->addCommandButton($mode, $this->plugin->txt($mode));

        if (!$properties && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->setValuesByPost();
        } else {
            $this->setValuesByArray($properties, true);
        }
    }

    /**
     * Creates a string array of all input fields the form contains
     *
     * @return string[]
     */
    public function getData(): array
    {
        $props = [];
        foreach ($this->getItems() as $item) {
            $value = $item->getValue();
            if ($item instanceof ilCheckboxInputGUI) {
                $value = $item->getChecked();
            }

            if ($item->getPostVar() == 'srcCode') {
                $value = preg_replace('/\t/', join("", array_fill(0, self::TAB_SIZE, " ")), (string)$value);
            }

            $props[$item->getPostVar()] = $value;
        }
        return $props;
    }

}
