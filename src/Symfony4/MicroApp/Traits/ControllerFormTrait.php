<?php

namespace ZnLib\Web\Symfony4\MicroApp\Traits;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;

trait ControllerFormTrait
{

    private $tokenManager;
    private $formFactory;
    private $type = FormType::class;

    // Внедрите зависимости в контроллер
    /*public function __construct(
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $tokenManager
    )
    {
        $this->setFormFactory($formFactory);
        $this->setTokenManager($tokenManager);
    }*/

    protected function getTokenManager(): CsrfTokenManagerInterface
    {
        return $this->tokenManager;
    }

    protected function setTokenManager(CsrfTokenManagerInterface $tokenManager): void
    {
        $this->tokenManager = $tokenManager;
    }

    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    protected function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    protected function getType(): string
    {
        return $this->type;
    }

    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    protected function buildForm(BuildFormInterface $form, Request $request): FormInterface
    {
        $formBuilder = $this->createFormBuilder($form);
        if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }
        $buildForm = $formBuilder->getForm();
        $buildForm->handleRequest($request);
        if ($buildForm->isSubmitted()) {
            $this->validCsrfToken($this->tokenManager, $request);
            $this->validate($buildForm);
            /*if ($buildForm->isValid()) {

            }*/
        }
        return $buildForm;
    }

    protected function setUnprocessableErrorsToForm(FormInterface $buildForm, UnprocessibleEntityException $e): void
    {
        foreach ($e->getErrorCollection() as $errorEntity) {
            $cause = $errorEntity->getViolation();
//          $cause = new ConstraintViolation('Error 1!', null, [], null, '', null, null, 'code1');
            $formError = new FormError($errorEntity->getMessage(), null, [], null, $cause);
            $buildForm->addError($formError);
        }
    }

    private function validate(FormInterface $buildForm)
    {
        try {
            ValidationHelper::validateEntity($buildForm->getData());
        } catch (UnprocessibleEntityException $e) {
            $this->setUnprocessableErrorsToForm($buildForm, $e);
        }
    }

    private static function validCsrfToken(CsrfTokenManagerInterface $tokenManager, Request $request)
    {
        $csrfToken = new CsrfToken(DotEnv::get('CSRF_TOKEN_ID'), $request->get('csrfToken'));
        $isValidToken = $tokenManager->isTokenValid($csrfToken);
        if (!$isValidToken) {
            throw new BadRequestException('CSRF token validate error!');
        }
    }

    private function createFormBuilder(object $formObject, array $options = []): FormBuilderInterface
    {
        return $this->getFormFactory()->createBuilder($this->type, $formObject, $options);
    }
}
