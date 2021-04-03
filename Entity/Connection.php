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

namespace Plugin\SocialLogin4\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Connection
 * @package Plugin\SocialLogin4\Entity
 *
 * @ORM\Table(name="plg_social_login_connection", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id", "customer_id"})})
 * @ORM\Entity(repositoryClass="Plugin\SocialLogin4\Repository\ConnectionRepository")
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $customer_id;

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
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * @return $this
     */
    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }
}
