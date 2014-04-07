<?php

namespace Netgen\TestBundle\Controller;

use Netgen\TestBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
	public function listAction()
	{
		$repository = $this->getDoctrine()->getRepository('NetgenTestBundle:User');

		$userList = $repository->findAll();

        $data = array();

        foreach($userList as $user)
        {
            if ($user instanceof \Netgen\TestBundle\Entity\User )
            {
                $data[] = array(
                    'id' => $user->getId(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName()
                );
            }
        }

        return new JsonResponse( $data );
	}

    public function userAction( $userID )
    {
        $repository = $this->getDoctrine()->getRepository('NetgenTestBundle:User');

        $user = $repository->find( $userID );

        if (!( $user instanceof User ) )
        {
            throw new NotFoundHttpException( "User not found" );
        }

        return new JsonResponse( array( 'ID' => $user->getId(),
                                        'username' => $user->getUsername(),
                                        'firstName' => $user->getFirstName(),
                                        'lastName' => $user->getLastName(),
                                        'email' => $user->getEmailAddress(),
                                        'enabled' => $user->getIsEnabled() ) );
    }

    public function addAction()
    {
        $user = new User();

        if ( $this->getRequest()->isMethod( 'POST' ) )
        {
            $userName = $this->getRequest()->request->get( 'username', false );

            if ( !$userName || strlen( $userName ) <= 0 )
            {
                throw new \Exception( "Parameter 'username' is required!" );
            }
            $user->setUsername( $userName );

            $email = $this->getRequest()->request->get( 'email', false );

            if ( !$email || strlen( $email ) <= 0 )
            {
                throw new \Exception( "Parameter 'email' is required!" );
            }
            $user->setEmailAddress( $email );

            $user->setFirstName( $this->getRequest()->request->get( 'firstName', '' ) );
            $user->setLastName( $this->getRequest()->request->get( 'lastName', '' ) );

            $user->setPassword( "" );
            $user->setIsEnabled( true );

            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist( $user );
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    "netgen_test_user_show",
                    array( "userID" => $user->getId() )
                )
            );
        }

        return new Response( $this->renderView(
                                'NetgenTestBundle:User:add.html.twig' ) );
    }

    public function editAction( $userID )
    {
        $repository = $this->getDoctrine()->getRepository('NetgenTestBundle:User');

        $user = $repository->find( $userID );

        if (!( $user instanceof User ) )
        {
            throw new NotFoundHttpException( "User not found" );
        }

        if ( $this->getRequest()->isMethod( 'POST' ) )
        {
            $email = $this->getRequest()->request->get( 'email', false );

            if ( !$email || strlen( $email ) <= 0 )
            {
                throw new \Exception( "Parameter 'email' is required!" );
            }
            $user->setEmailAddress( $email );

            $user->setFirstName( $this->getRequest()->request->get( 'firstName', '' ) );
            $user->setLastName( $this->getRequest()->request->get( 'lastName', '' ) );

            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            $em->persist( $user );
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    "netgen_test_user_show",
                    array( "userID" => $user->getId() )
                )
            );
        }

        return new Response( $this->renderView(
            'NetgenTestBundle:User:edit.html.twig',
            array( 'userID' => $user->getId(),
                   'firstName' => $user->getFirstName(),
                   'lastName' => $user->getLastName(),
                   'email' => $user->getEmailAddress() ) ) );
    }

    public function disableAction( $userID )
    {
        $repository = $this->getDoctrine()->getRepository('NetgenTestBundle:User');

        $user = $repository->find( $userID );

        if (!( $user instanceof User ) )
        {
        throw new NotFoundHttpException( "User not found." );
        }

        if ($user->getIsEnabled() == false)
        {
            return new Response( "User already disabled." );
        }

        $user->setIsEnabled(false);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new Response( "User disabled successfully." );
    }

    public function removeAction( $userID )
    {
        $repository = $this->getDoctrine()->getRepository('NetgenTestBundle:User');

        $user = $repository->find( $userID );

        if (!( $user instanceof \Netgen\TestBundle\Entity\User ) )
        {
            throw new NotFoundHttpException( "User not found." );
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($user);
        $em->flush();

        return new Response( "User removed successfully." );
    }
}
