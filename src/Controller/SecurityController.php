<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Security\LoginAuthenticator;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    protected function sendErrorMessage($form, string $msg = null) : Response
    {
        return $this->render('security/signup.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form->createView(),
            'error' => $msg,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/signup', name: "signup")]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, UserAuthenticatorInterface $auth, LoginAuthenticator $loginAuth) : Response
    {
        if($this->getUser())
        {
            return $this->redirectToRoute('index');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $userRepos = $doctrine->getRepository(User::class);
            $user = $form->getData();

            if($userRepos->findOneBy(['email' => $user->getEmail()]))
            {
                return $this->sendErrorMessage($form, 'Email already used');
            }

            if($userRepos->findOneBy(['username' => $user->getUsername()]))
            {
                return $this->sendErrorMessage($form, 'Username already used');
            }
            
            if($user->getPlainPassword() === $user->getPlainPasswordConfirm())
            {
                $user->setSignupDate(new DateTime());
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));

                $entityManager = $doctrine->getManager();

                $entityManager->persist($user);

                $entityManager->flush();

                return $auth->authenticateUser($user, $loginAuth, $request);
            }
            else
            {
                return $this->sendErrorMessage($form, 'The passwords must be identical');
            }
        }

        return $this->render('security/signup.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form->createView(),
            'error' => null,
        ]);
    }
}
