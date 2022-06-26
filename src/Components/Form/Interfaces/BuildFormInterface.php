<?php

namespace ZnLib\Web\Components\Form\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface BuildFormInterface
{

    public function buildForm(FormBuilderInterface $formBuilder);
}
