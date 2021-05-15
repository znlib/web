<?php

namespace ZnLib\Web\Symfony4\MicroApp\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface BuildFormInterface
{

    public function buildForm(FormBuilderInterface $formBuilder);
}
