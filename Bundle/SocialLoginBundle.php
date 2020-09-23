<?php
/**
 * This file is part of SocialLogin4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SocialLogin4\Bundle;


use Plugin\SocialLogin4\DependencyInjection\SocialLoginExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SocialLoginBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SocialLoginExtension();
    }
}
