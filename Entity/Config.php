<?php

/*
 * This file is part of Auth0 for EC-CUBE
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Auth0\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(Config::class)) {
    /**
     * @ORM\Table(name="plg_social_login_config")
     *
     * @ORM\Entity(repositoryClass="Plugin\Auth0\Repository\ConfigRepository")
     *
     * @ORM\HasLifecycleCallbacks()
     */
    class Config
    {
        public const ID = 1;

        /**
         * @var int
         *
         * @ORM\Column(type="integer", options={"unsigned":true})
         *
         * @ORM\Id
         *
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $client_id;

        /**
         * @var string
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $client_secret;

        /**
         * @var string
         *
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        private $custom_domain;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string|null
         */
        public function getClientId(): ?string
        {
            return $this->client_id;
        }

        /**
         * @param string|null $client_id
         *
         * @return $this
         */
        public function setClientId(?string $client_id): self
        {
            $this->client_id = $client_id;

            return $this;
        }

        /**
         * @return string|null
         */
        public function getClientSecret(): ?string
        {
            return $this->client_secret;
        }

        /**
         * @param string|null $client_secret
         *
         * @return $this
         */
        public function setClientSecret(?string $client_secret): self
        {
            $this->client_secret = $client_secret;

            return $this;
        }

        /**
         * @return string|null
         */
        public function getCustomDomain(): ?string
        {
            return $this->custom_domain;
        }

        /**
         * @param string|null $custom_domain
         *
         * @return $this
         */
        public function setCustomDomain(?string $custom_domain): self
        {
            $this->custom_domain = $custom_domain;

            return $this;
        }
    }
}
