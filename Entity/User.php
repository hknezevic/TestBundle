<?php

namespace Netgen\Bundle\TestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Serializable;

use Netgen\Bundle\TestBundle\Entity\Role;

/**
 * User
 */
class User implements AdvancedUserInterface, Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var boolean
     */
    private $isEnabled;

    /**
     * @var \Netgen\Bundle\TestBundle\Entity\Role[]
     */
    private $userRoles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->refreshSalt();
        $this->userRoles = new ArrayCollection();
        $this->isEnabled = true;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the plain password to User instance
     *
     * @param string $plainPassword
     */
    public function setPlainPassword( $plainPassword )
    {
        $this->plainPassword = $plainPassword;

        // Change some mapped values so preUpdate will get called.
        $this->refreshSalt();
        $this->password = "";
    }

    /**
     * Get plainPassword
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Refreshes the salt
     */
    private function refreshSalt()
    {
        $this->salt = md5( uniqid( null, true ) );
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Add userRole
     *
     * @param \Netgen\Bundle\TestBundle\Entity\Role $userRole
     * @return \Netgen\Bundle\TestBundle\Entity\User
     */
    public function addUserRole(Role $userRole)
    {
        $this->userRoles[] = $userRole;

        return $this;
    }

    /**
     * Remove userRole
     *
     * @param \Netgen\Bundle\TestBundle\Entity\Role $userRole
     */
    public function removeUserRole(Role $userRole)
    {
        $this->userRoles->removeElement($userRole);
    }

    /**
     * Get userRoles
     *
     * @return \Netgen\Bundle\TestBundle\Entity\Role[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return \Symfony\Component\Security\Core\Role\Role[] The user roles
     */
    public function getRoles()
    {
        return $this->userRoles->toArray();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * String representation of object
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->salt,
                $this->password,
                $this->isEnabled
            )
        );
    }

    /**
     * Constructs the object
     *
     * @param string $serialized The string representation of the object.
     *
     * @return mixed the original value unserialized.
     */
    public function unserialize( $serialized )
    {
        list (
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->isEnabled
        ) = unserialize( $serialized );
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return boolean true if the user's account is non expired, false otherwise
     *
     * @see \Symfony\Component\Security\Core\Exception\AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return boolean true if the user is not locked, false otherwise
     *
     * @see \Symfony\Component\Security\Core\Exception\LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return boolean true if the user's credentials are non expired, false otherwise
     *
     * @see \Symfony\Component\Security\Core\Exception\CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return boolean true if the user is enabled, false otherwise
     *
     * @see \Symfony\Component\Security\Core\Exception\DisabledException
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }
}
