<?php

namespace ZnLib\Web\Yii2\Helpers;

use yii\base\Model;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnYii\Web\Widgets\Toastr\Toastr;
use ZnSandbox\Sandbox\RestClient\Domain\Interfaces\Services\AccessServiceInterface;
use ZnSandbox\Sandbox\RestClient\Domain\Interfaces\Services\EnvironmentServiceInterface;
use ZnSandbox\Sandbox\RestClient\Domain\Interfaces\Services\ProjectServiceInterface;
use ZnSandbox\Sandbox\RestClient\Yii\Web\models\EnvironmentForm;

class ErrorHelper
{

    public static function handleError(UnprocessibleEntityException $e, Model $model)
    {
        $arr = EntityHelper::collectionToArray($e->getErrorCollection());
        foreach ($arr as $error) {
            if (!empty($error['field'])) {
                $model->addError($error['field'], $error['message']);
            } else {
                Toastr::create($error['message'], Toastr::TYPE_WARNING);
            }
        }
    }

    public static function addErrorsFromException(UnprocessibleEntityException $e, Model $form) {
        $errors = $e->getErrorCollection();
        if($errors instanceof Model) {
            $errors = $errors->getErrors();
        }
        foreach($errors as $errorEntity) {
            $form->addError($errorEntity->getField(), $errorEntity->getMessage());
        }
    }
}
