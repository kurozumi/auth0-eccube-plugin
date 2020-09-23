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
use Eccube\Annotation\EntityExtension;

/**
 * Class CustomerTrait
 * @package Plugin\SocialLogin4\Entity
 *
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auth0_id;

    /**
     * @return string|null
     */
    public function getAuth0Id(): ?string
    {
        return $this->auth0_id;
    }

    /**
     * @param string|null $auth0_id
     * @return $this
     */
    public function setAuth0Id(?string $auth0_id): self
    {
        $this->auth0_id = $auth0_id;

        return $this;
    }
}
