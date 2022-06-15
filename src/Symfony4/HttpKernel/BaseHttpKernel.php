<?php

namespace ZnLib\Web\Symfony4\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\TerminableInterface;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;

abstract class BaseHttpKernel implements HttpKernelInterface, TerminableInterface
{

    use EventDispatcherTrait;

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        $this->getEventDispatcher()->dispatch(new TerminateEvent($this, $request, $response), KernelEvents::TERMINATE);
    }
}
