<?php

namespace Netgen\Bundle\TestBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 */
class Role implements RoleInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var string
     */
    private $roleIdentifier;

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
     * Set roleName
     *
     * @param string $roleName
     * @return \Netgen\Bundle\TestBundle\Entity\Role
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set roleIdentifier
     *
     * @param string $roleIdentifier
     * @return \Netgen\Bundle\TestBundle\Entity\Role
     */
    public function setRoleIdentifier($roleIdentifier)
    {
        $this->roleIdentifier = $roleIdentifier;

        return $this;
    }

    /**
     * Get roleIdentifier
     *
     * @return string
     */
    public function getRoleIdentifier()
    {
        return $this->roleIdentifier;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->roleIdentifier;
    }
}
