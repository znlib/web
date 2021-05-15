<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use Symfony\Component\Form\FormView;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;

abstract class BaseRender
{

    protected $formView;
    protected $attributeName;
    protected $view;

    public function __construct(FormView $formView, string $attributeName)
    {
        $this->formView = $formView;
        $this->attributeName = $attributeName;
        $this->view = $formView->children[$attributeName];
    }

    protected function tagName(): string {
        return 'input';
    }
    
    protected function allowOptions(): array {
        return [
            "value",
            "id",
            'type',
//    "name",
            "disabled",
            "multipart",
            "required",
        ];
    }

    protected function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }

    public function render()
    {
        $options = $this->getOptions();
        return Html::tag($this->tagName(), '', $options);
    }

    protected function getViewOptions(): array {
        return $this->view->vars;
    }

    protected function getViewOption($name) {
        return $this->view->vars[$name];
    }
    
    protected function getOptions(): array {
        $inputVars = $this->getViewOptions();
        $options = $this->defaultOptions();
        $options = ArrayHelper::merge($options, $this->filterOptions($inputVars));
        return $options;
    }

    protected function filterOptions(array $options): array
    {
        $options1 = ArrayHelper::extractByKeys($options, $this->allowOptions());
        $options1['name'] = $options['full_name'];
        return $options1;
    }
}
