<?php

namespace ZnLib\Web\Symfony4\MicroApp\Libs\Renders;

use Symfony\Component\Form\FormError;
use ZnCore\Base\Legacy\Yii\Helpers\Html;

class FormErrorRender extends BaseRender
{

    public function render(): string
    {
        $html = '';
        foreach($this->getFormView()->vars['errors'] as $error) {
            /** @var FormError $error */
            $html .= Html::tag('div', $error->getMessage(), [
                'class' => 'alert alert-danger',
                'role' => 'alert',
            ]);
            //$html .= ' <div class="alert alert-danger" role="alert">'.  .'</div> ';
        }
        return $html;
    }
}
