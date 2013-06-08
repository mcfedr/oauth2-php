<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Tests\Entity;

use Doctrine\ORM\EntityRepository;
use Pantarei\OAuth2\Model\AccessTokenInterface;
use Pantarei\OAuth2\Model\AccessTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * AccessTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccessTokenRepository extends EntityRepository implements AccessTokenManagerInterface, UserProviderInterface
{
    public function createAccessToken()
    {
        return new $this->getClassName();
    }

    public function deleteAccessToken(AccessTokenInterface $access_token)
    {
        $this->remove($access_token);
        $this->flush();
    }

    public function findAccessTokenByAccessToken($access_token)
    {
        return $this->findOneBy(array(
            'access_token' => $access_token,
        ));
    }

    public function reloadAccessToken(AccessTokenInterface $access_token)
    {
        $this->refresh($access_token);
    }

    public function updateAccessToken(AccessTokenInterface $access_token)
    {
        $this->persist($access_token);
        $this->flush();
    }

    public function loadUserByAccessToken($access_token)
    {
        $result = $this->findOneBy(array(
            'access_token' => $access_token,
        ));
        if ($result === null) {
            throw new UsernameNotFoundException();
        }

        return $result;
    }

    public function loadUserByUsername($username)
    {
        $result = $this->findOneBy(array(
            'username' => $username,
        ));
        if ($result === null) {
            throw new UsernameNotFoundException();
        }

        return $result;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException();
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
}