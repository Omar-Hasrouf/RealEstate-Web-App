<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils ;

class SecurityController extends AbstractController{
    
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response{
        $error        = $authenticationUtils->getLastAuthenticationError(); // get the login error if there's one
        $lastUsername = $authenticationUtils->getLastUsername(); // last username entered by the user
        return $this->render('security/login.html.twig',[
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }
}