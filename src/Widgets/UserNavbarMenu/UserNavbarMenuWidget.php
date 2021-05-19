<?php

namespace ZnLib\Web\Widgets\UserNavbarMenu;

use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnLib\Web\Widgets\Base\BaseWidget2;

class UserNavbarMenuWidget extends BaseWidget2
{

    public $loginUrl = '/auth';
    public $userMenuHtml = '';
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function run(): string
    {
        if ($this->authService->isGuest()) {
            return $this->render('guest', [
                'loginUrl' => $this->loginUrl,
            ]);
        } else {
            $identity = $this->authService->getIdentity();
            return $this->render('user', [
                'identity' => $identity,
                'userMenuHtml' => $this->userMenuHtml,
            ]);
        }
    }
}
