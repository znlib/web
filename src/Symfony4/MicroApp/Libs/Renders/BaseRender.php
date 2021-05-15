<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use Symfony\Component\Form\FormView;

abstract class BaseRender implements RenderInterface
{

    private $formView;

    public function __construct(FormView $formView)
    {
        $this->formView = $formView;
    }

    public function getFormView(): FormView
    {
        return $this->formView;
    }
}
