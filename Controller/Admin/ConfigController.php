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

namespace Plugin\Auth0\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Plugin\Auth0\Entity\Config;
use Plugin\Auth0\Form\Type\Admin\ConfigType;
use Plugin\Auth0\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/social_login/config", name="social_login_admin_config")
     * @Template("@Auth0/admin/config.twig")
     */
    public function index(Request $request, CacheUtil $cacheUtil)
    {
        $Config = $this->configRepository->get();
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Config $Config */
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush();

            $envFile = $this->getParameter('kernel.project_dir').'/.env';
            $env = file_get_contents($envFile);

            $env = StringUtil::replaceOrAddEnv($env, [
                'OAUTH_AUTH0_CLIENT_ID' => $Config->getClientId(),
                'OAUTH_AUTH0_CLIENT_SECRET' => $Config->getClientSecret(),
                'OAUTH_AUTH0_CUSTOM_DOMAIN' => $Config->getCustomDomain(),
                'OAUTH_AUTH0_COOKIE_SECRET' => shell_exec('openssl rand -hex 32')
            ]);

            file_put_contents($envFile, $env);

            $cacheUtil->clearCache();

            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('social_login_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
