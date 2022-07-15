<?php

namespace ZnLib\Web\Form\Helpers;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnCore\DotEnv\Domain\Libs\DotEnv;
use ZnDomain\Validator\Entities\ValidationErrorEntity;
use ZnDomain\Validator\Exceptions\UnprocessibleEntityException;
use ZnDomain\Validator\Helpers\ValidationHelper;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnLib\Web\Form\Interfaces\BuildFormInterface;

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

    public function build(object $form, Request $request): FormInterface
    {
        FormHelper::buildForm($form, $this->formBuilder);
        $form = $this->formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            FormHelper::validCsrfToken($this->tokenManager, $request);
            FormHelper::validate($form);
            /*if ($form->isValid()) {

            }*/
        }
        return $form;
    }

    public static function setErrorsToForm(Enumerable $collection, FormInterface $form)
    {
        foreach ($collection as $errorEntity) {
            /** @var ValidationErrorEntity $errorEntity */
            $cause = $errorEntity->getViolation();
//                    $cause = new ConstraintViolation('Error 1!', null, [], null, '', null, null, 'code1');
            $form->addError(new FormError($errorEntity->getMessage(), null, [], null, $cause));
        }
    }

    protected static function validCsrfToken(CsrfTokenManagerInterface $tokenManager, Request $request)
    {
        $csrfToken = new CsrfToken(DotEnv::get('CSRF_TOKEN_ID'), $request->get('csrfToken'));
        $isValidToken = $tokenManager->isTokenValid($csrfToken);
        if (!$isValidToken) {
            throw new BadRequestException('CSRF token validate error!');
        }
    }

    protected static function buildForm(object $form, FormBuilderInterface $formBuilder)
    {
        if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }
    }

    protected static function validate(FormInterface $form)
    {
        try {
            ValidationHelper::validateEntity($form->getData());
        } catch (UnprocessibleEntityException $e) {
            FormHelper::setErrorsToForm($e->getErrorCollection(), $form);
        }
    }
}
