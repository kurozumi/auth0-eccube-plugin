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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * Class CustomerTrait
 *
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @ORM\OneToMany(targetEntity="Plugin\Auth0\Entity\Connection", mappedBy="Customer")
     */
    private $Connections;

    /**
     * @return Collection
     */
    public function getConnections(): Collection
    {
        if (null === $this->Connections) {
            $this->Connections = new ArrayCollection();
        }

        return $this->Connections;
    }

    /**
     * @param Connection $connection
     *
     * @return $this
     */
    public function addConnection(Connection $connection): self
    {
        if (null === $this->Connections) {
            $this->Connections = new ArrayCollection();
        }

        if (false === $this->Connections->contains($connection)) {
            $this->Connections->add($connection);
            $connection->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param Connection $connection
     *
     * @return $this
     */
    public function removeConnection(Connection $connection): self
    {
        if (null === $this->Connections) {
            $this->Connections = new ArrayCollection();
        }

        if ($this->Connections->contains($connection)) {
            $this->Connections->removeElement($connection);
            if ($connection->getCustomer() === $this) {
                $connection->setCustomer(null);
            }
        }

        return $this;
    }
}
