<?php

namespace ZnLib\Web\Components\TwBootstrap\Widgets\UserNavbarMenu;

use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnLib\Web\Components\Widget\Base\BaseWidget2;
use ZnUser\Rbac\Domain\Entities\AssignmentEntity;
use ZnUser\Rbac\Domain\Entities\ItemEntity;
use ZnUser\Rbac\Domain\Interfaces\Services\MyAssignmentServiceInterface;

class UserNavbarMenuWidget extends BaseWidget2
{

    public $loginUrl = '/auth';
    public $userMenuHtml = '';

    private $authService;
    private $myAssignmentService;

    public function __construct(AuthServiceInterface $authService, MyAssignmentServiceInterface $myAssignmentService)
    {
        $this->authService = $authService;
        $this->myAssignmentService = $myAssignmentService;
    }

    public function run(): string
    {
        if ($this->authService->isGuest()) {
            return $this->render('guest', [
                'loginUrl' => $this->loginUrl,
            ]);
        } else {
            $assignmentCollection = $this->myAssignmentService->all();
            $userMenuHtml = $this->userMenuHtml;

            if($assignmentCollection->first() instanceof AssignmentEntity) {
                /** @var ItemEntity $roleEntity */
                $roleEntity = $assignmentCollection->first()->getItem();
                $userMenuHtml = '<h6 class="dropdown-header">' . $roleEntity->getTitle() . '</h6>' . $this->userMenuHtml;
            }

            $identity = $this->authService->getIdentity();
            return $this->render('user', [
                'identity' => $identity,
                //'roleEntity' => $assignmentCollection->first()->getItem(),
                'userMenuHtml' => $userMenuHtml,
            ]);
        }
    }
}
