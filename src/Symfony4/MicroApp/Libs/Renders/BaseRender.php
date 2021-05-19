<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use Symfony\Component\Form\FormView;

abstract class BaseRender implements RenderInterface
{

    private $formView;
    private $options = [];

    public function __construct(FormView $formView)
    {
        $this->formView = $formView;
    }

    public function getFormView(): FormView
    {
        return $this->formView;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function addOption(string $name, $value)
    {
        $this->options[$name] = $value;
    }
}
