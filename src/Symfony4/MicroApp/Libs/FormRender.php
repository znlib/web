<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs;

use Symfony\Component\Form\FormView;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\BaseRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\ButtonRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\CheckboxRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\FormErrorRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\HintRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\LabelRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\PasswordRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\SelectRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\TextRender;

class FormRender
{

    private $formOptions = [];
    private $formView;
    private $tokenManager;
    private $renderDefinitions = [
        'text' => TextRender::class,
        'select' => SelectRender::class,
        'password' => PasswordRender::class,
        'submit' => ButtonRender::class,
        'checkbox' => CheckboxRender::class,
        'hint' => HintRender::class,
        'label' => LabelRender::class,
        'formError' => FormErrorRender::class,
    ];

    public function __construct(FormView $formView, CsrfTokenManagerInterface $tokenManager)
    {
        $this->formView = $formView;
        $this->tokenManager = $tokenManager;
    }

    public function addFormOption(string $name, string $value = null) {
        $this->formOptions[$name] = $value;
    }

    public function beginFrom() {
        $formOptions = ArrayHelper::merge($this->formOptions, [
            'name' => $this->formView->vars['name'],
            'method' => $this->formView->vars['method'],
        ]);
        $html =  Html::beginTag('form', $formOptions);
        $html .= $this->csrfTokenInput();
        return $html;
    }

    public function endFrom() {
        return Html::endForm();
    }

    public function errors() {
        $renderInstance = $this->createRender('formError');
        return $renderInstance->render();
    }

    public function input(string $name, string $type, array $options = []) {
        $renderInstance = $this->createRender($type, $options);
        $renderInstance->setAttributeName($name);
        return $renderInstance->render();
    }

    public function renderDefinitions(): array {
        return $this->renderDefinitions;
    }

    public function addDefinition($definition): array {
        $this->renderDefinitions[] = $definition;
    }

    public function label($name) {
        return $this->input($name, 'label');
    }

    public function hint($name, $text = null) {
        return $this->input($name, 'hint');
    }

    private function createRender(string $type, array $options = []): BaseRender {
        $renderDefinition = $this->renderDefinitions[$type];
        /** @var BaseRender $renderInstance */
        $renderInstance = new $renderDefinition($this->formView);
        $renderInstance->setOptions($options);
        return $renderInstance;
    }

    private function csrfTokenInput(): string {
        $token = $this->tokenManager->getToken(DotEnv::get('CSRF_TOKEN_ID'));
        return Html::hiddenInput('csrfToken', $token->getValue());
    }

}
