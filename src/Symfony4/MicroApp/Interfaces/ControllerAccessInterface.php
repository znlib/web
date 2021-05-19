<?php

namespace ZnLib\Web\Symfony4\MicroApp\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface ControllerAccessInterface
{

    public function access(): array;
}
