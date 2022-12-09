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

namespace Plugin\Auth0\Bundle;

use Plugin\Auth0\DependencyInjection\Auth0Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Auth0Bundle extends Bundle
{
    public function getContainerExtension()
    {
        return new Auth0Extension();
    }
}
