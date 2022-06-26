<?php

namespace ZnLib\Web\Controller\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface ControllerAccessInterface
{

    public function access(): array;
}
