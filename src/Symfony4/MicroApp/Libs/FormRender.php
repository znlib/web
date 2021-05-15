<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs;

use Symfony\Component\Form\FormView;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\BaseRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\ButtonRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\CheckboxRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\HintRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\LabelRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\PasswordRender;
use ZnLib\Web\Symfony4\MicroApp\Libs\Renders\TextRender;

class FormRender
{

    private $formView;
    private $tokenManager;

    public function __construct(FormView $formView, CsrfTokenManagerInterface $tokenManager)
    {
        $this->formView = $formView;
        $this->tokenManager = $tokenManager;
    }

    public function beginFrom() {
        $html =  Html::beginTag('form', [
            'name' => $this->formView->vars['name'],
            'method' => $this->formView->vars['method'],
        ]);
        $token = $this->tokenManager->getToken(DotEnv::get('CSRF_TOKEN_ID'));
        $html .= Html::hiddenInput('csrfToken', $token->getValue());
        return $html;
    }

    public function endFrom() {
        return Html::endForm();
    }
    
    public function input($name, $type) {
        $renderDefinition = $this->renderDefinitions[$type];
        /** @var BaseRender $renderInstance */
        $renderInstance = new $renderDefinition($this->formView, $name);
        return $renderInstance->render();
    }

    private $renderDefinitions = [
        'text' => TextRender::class,
        'password' => PasswordRender::class,
        'submit' => ButtonRender::class,
        'checkbox' => CheckboxRender::class,
        'hint' => HintRender::class,
        'label' => LabelRender::class,
    ];

    public function renderDefinitions(): array {
        return $this->renderDefinition;
    }

    public function label($name) {
        return $this->input($name, 'label');
    }

    public function hint($name, $text = null) {
        return $this->input($name, 'hint');
    }
}
