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


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $container->extension('knpu_oauth2_client', [
        'clients' => [
            'auth0' => [
                'type' => 'auth0',
                'client_id' => '%env(OAUTH_AUTH0_CLIENT_ID)%',
                'client_secret' => '%env(OAUTH_AUTH0_CLIENT_SECRET)%',
                'redirect_route' => 'auth0_callback',
                'redirect_params' => [],
                'custom_domain' => '%env(OAUTH_AUTH0_CUSTOM_DOMAIN)%'
            ]
        ]
    ]);
};
