<?php

/*
 * This file is part of Auth0 for EC-CUBE
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Auth0\Security\Exception;

use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;

class EmailVerifiedException extends FinishRegistrationException
{
    public function getMessageKey(): string
    {
        return 'You need to verify your email address.';
    }
}
