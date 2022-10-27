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

namespace Plugin\Auth0\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Customer;

/**
 * Class Connection
 * @package Plugin\Auth0\Entity
 *
 * @ORM\Table(name="plg_social_login_connection", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id", "customer_id"})})
 * @ORM\Entity(repositoryClass="Plugin\Auth0\Repository\ConnectionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Connection
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $user_id;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="Connections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     * @return $this
     */
    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->Customer;
    }

    /**
     * @param Customer $Customer
     * @return $this
     */
    public function setCustomer(Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }
}
