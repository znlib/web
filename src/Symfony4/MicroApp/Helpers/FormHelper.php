<?php

namespace ZnLib\Web\Symfony4\MicroApp\Helpers;

use Illuminate\Support\Collection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;

class FormHelper
{

    protected $tokenManager;
    protected $formBuilder;

    public function __construct(
        FormBuilderInterface $formBuilder,
        CsrfTokenManagerInterface $tokenManager
    )
    {
        $this->formBuilder = $formBuilder;
        $this->tokenManager = $tokenManager;
    }
    
    public static function validCsrfToken(CsrfTokenManagerInterface $tokenManager, Request $request) {
        $csrfToken = new CsrfToken(DotEnv::get('CSRF_TOKEN_ID'), $request->get('csrfToken'));
        $isValidToken = $tokenManager->isTokenValid($csrfToken);
        if(!$isValidToken) {
            throw new BadRequestException('CSRF token validate error!');
        }
    }

    public function build(object $form, Request $request): FormInterface {
        FormHelper::buildForm($form, $this->formBuilder);
        $form = $this->formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            FormHelper::validCsrfToken($this->tokenManager, $request);
            FormHelper::validate($form);
            if($form->isValid()) {

            }
        }
        return $form;
    }
    
    public static function buildForm(object $form, FormBuilderInterface $formBuilder) {
        if($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }
    }
    
    public static function validate(FormInterface $form) {
        try {
            ValidationHelper::validateEntity($form->getData());
        } catch (UnprocessibleEntityException $e) {
            FormHelper::setErrorsToForm($e->getErrorCollection(), $form);
        }
    }

    public static function setErrorsToForm(Collection $collection, FormInterface $form) {
        foreach ($collection as $errorEntity) {
            /** @var ValidateErrorEntity $errorEntity */
            $cause = $errorEntity->getViolation();
//                    $cause = new ConstraintViolation('Error 1!', null, [], null, '', null, null, 'code1');
            $form->addError(new FormError($errorEntity->getMessage(), null, [], null, $cause));
        }
    }
}
