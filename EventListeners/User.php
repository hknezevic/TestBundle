<?php

namespace Netgen\Bundle\TestBundle\EventListeners;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Netgen\Bundle\TestBundle\Entity\User as UserEntity;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class User
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     */
    public function __construct( EncoderFactoryInterface $encoderFactory )
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Returns the encoder
     *
     * @param \Netgen\Bundle\TestBundle\Entity\User $user
     *
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    private function getEncoder( UserEntity $user )
    {
        return $this->encoderFactory->getEncoder( $user );
    }

    /**
     * Updates and hashes the user password
     *
     * @param \Netgen\Bundle\TestBundle\Entity\User $user
     */
    private function encodePassword( UserEntity $user )
    {
        $plainPassword = $user->getPlainPassword();

        if ( !empty( $plainPassword ) )
        {
            $encoder = $this->getEncoder( $user );
            $user->setPassword( $encoder->encodePassword( $plainPassword, $user->getSalt() ) );
            $user->eraseCredentials();
        }
    }

    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function preUpdate( PreUpdateEventArgs $event )
    {
        $user = $event->getEntity();
        if ( !$user instanceof UserEntity )
        {
            return;
        }

        $entityChangeSet = $event->getEntityChangeSet();
        if ( isset( $entityChangeSet["password"] ) )
        {
            $this->encodePassword( $user );
            $event->setNewValue( "password", $user->getPassword() );
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $event
     */
    public function prePersist( LifecycleEventArgs $event )
    {
        $user = $event->getEntity();
        if ( !$user instanceof UserEntity )
        {
            return;
        }

        $this->encodePassword( $user );
    }
}
