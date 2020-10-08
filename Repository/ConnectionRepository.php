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


use Eccube\Repository\AbstractRepository;
use Plugin\SocialLogin4\Entity\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ConnectionRepository
 * @package Plugin\SocialLogin4\Repository
 */
class ConnectionRepository extends AbstractRepository
{
    /**
     * ConnectionRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Connection::class);
    }

    public function findOneByIdJoinedToCustomer($connect_id)
    {
        $query = $this->getEntityManager()
            ->createQuery(
            'SELECT conn, cus
                FROM Plugin\SocialLogin4\Entity\Connection conn
                INNER JOIN conn.Customer cus
                WHERE conn.id = :id'
        )->setParameter('id', $connect_id);

        return $query->getOneOrNullResult();
    }
}
