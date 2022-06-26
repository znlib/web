<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

return [
    'singletons' => [
        CsrfTokenManagerInterface::class => CsrfTokenManager::class,
        ResolvedFormTypeFactoryInterface::class => ResolvedFormTypeFactory::class,
        FormFactoryInterface::class => FormFactory::class,
        FormRegistryInterface::class => function (ContainerInterface $container) {
            $extensions = [
                $container->get(HttpFoundationExtension::class)
            ];
            $resolvedFormTypeFactory = $container->get(ResolvedFormTypeFactoryInterface::class);
            $registry = new FormRegistry($extensions, $resolvedFormTypeFactory);
            return $registry;
        },
    ],
];
