<?php

namespace ZnLib\Web\Symfony4\MicroApp\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface ControllerLayoutInterface
{

    public function getLayout(): ?string;

    public function setLayout(?string $layout): void;

    public function getLayoutParams(): array;

    public function setLayoutParams(array $layoutParams): void;

    public function addLayoutParam(string $name, $value): void;
}
