<?php

namespace ABR\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ABR\UserBundle\Entity\User;
use ABR\UserBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('ABRUserBundle:User')->findAll();
        /*
        $res = 'Lista de usuarios: <br />';
        
        foreach ($users as $user) {
            $res .= 'Usuario: ' . $user->getUsername() .' - Email: ' .$user->getEmail() .'<br />';
        }
        
        return new Response($res);
        */
        
        return $this->render('ABRUserBundle:User:index.html.twig', array('users' => $users));
        
    }
    
    public function addAction()
    {
        $user = new User();
        $form = $this->createCreateForm($user);
        return $this->render('ABRUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType, $entity, array(
                'action' => $this->generateUrl('abr_user_create'),
                'method' => 'POST'
            ));
        return $form;
    }
    
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('abr_user_index');
        }
        
        return $this->render('ABRUserBundle:User:add.html.twig', array('form' => $form->createView())); 
    }
    
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ABRUserBundle:User');
        
        $user = $repository->find($id);
        //$user = $repository->findOneById($id);
        //$user = $repository->findByUserName($nombre);
        
        return new Response('Usuario: ' . $user->getUsername() .' - Email: ' .$user->getEmail());
    }
}
