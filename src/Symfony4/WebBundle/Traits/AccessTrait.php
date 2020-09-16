<?php

namespace ZnLib\Web\Symfony4\WebBundle\Traits;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

trait AccessTrait
{

    protected function checkAuth()
    {
        $user = $this->getUser();
        if ( ! is_object($user) || ! $user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
    }

}