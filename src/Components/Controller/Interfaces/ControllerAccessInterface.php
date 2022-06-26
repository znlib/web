<?php

namespace ZnLib\Web\Components\Controller\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface ControllerAccessInterface
{

    public function access(): array;
}
