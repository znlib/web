<?php

namespace ZnLib\Web\Symfony4\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\TerminableInterface;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;

abstract class BaseHttpKernel implements HttpKernelInterface, TerminableInterface
{

    use EventDispatcherTrait;

    protected $requestStack;

    /**
     * Filters a response object.
     *
     * @throws \RuntimeException if the passed object is not a Response instance
     */
    protected function filterResponse(Response $response, Request $request, int $type): Response
    {
        $event = new ResponseEvent($this, $request, $type, $response);
        $this->getEventDispatcher()->dispatch($event, KernelEvents::RESPONSE);
        $this->finishRequest($request, $type);
        return $event->getResponse();
    }

    /**
     * Publishes the finish request event, then pop the request from the stack.
     *
     * Note that the order of the operations is important here, otherwise
     * operations such as {@link RequestStack::getParentRequest()} can lead to
     * weird results.
     */
    private function finishRequest(Request $request, int $type)
    {
        $this->getEventDispatcher()->dispatch(new FinishRequestEvent($this, $request, $type), KernelEvents::FINISH_REQUEST);
        $this->requestStack->pop();
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        $this->getEventDispatcher()->dispatch(new TerminateEvent($this, $request, $response), KernelEvents::TERMINATE);
    }
}
