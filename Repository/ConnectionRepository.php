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

namespace Plugin\SocialLogin4\Repository;


use Doctrine\Persistence\ManagerRegistry;
use Eccube\Repository\AbstractRepository;
use Plugin\SocialLogin4\Entity\Connection;

class ConnectionRepository extends AbstractRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Connection::class);
    }
}
