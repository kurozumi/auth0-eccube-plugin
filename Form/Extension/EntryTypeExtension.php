<?php

/**
 * This file is part of Auth0
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Auth0\Form\Extension;

use Eccube\Form\Type\Front\EntryType;
use Eccube\Form\Type\RepeatedEmailType;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class EntryTypeExtension extends AbstractTypeExtension
{
    use FinishRegistrationBehavior;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userInfo = $this->getUserInfoFromSession($this->requestStack->getMasterRequest());
        if ($userInfo) {
            $builder
                ->add('email', RepeatedEmailType::class, [
                    'attr' => ['readonly' => 'readonly'],
                ]);

            // メールアドレスをセット
            $builder
                ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($userInfo) {
                    $form = $event->getForm();
                    $form['email']->setData($userInfo['email']);
                });
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return EntryType::class;
    }

    /**
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [EntryType::class];
    }
}
