<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * 
     * @route("/register", name="app_register")
     *  
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $email,
        JWTService $jwt
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            //On génère le jwt de l'utilisateur

            //On crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //On crée le payload
            $payload = [
                'id' => $user->getId()
            ];

            //On génère un token 
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));




            //On envoie un mail
            $email->send(
                'no-reply@test.fr',
                $user->getEmail(),
                'Activation de votre compte sur le site fablab',
                'register',
                compact('user', 'token')
            );


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser(
        $token,
        JWTService $jwt,
        UserRepository $usersRepository,
        EntityManagerInterface $em
    ): Response {
        //On verifie si le token valide, modifier ou expiré 
        if (
            $jwt->isValid($token)
            && !$jwt->isExpired($token)
            && $jwt->check($token, $this->getParameter('app.jwtsecret'))
        ) {
            //On recupere le payload
            $payload = $jwt->getPlayLoad($token);

            //on recupere le user du token
            $user = $usersRepository->find($payload['id']);

            //On verifie que l'utilisateur existe et n'a pas encore activié son compte 
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash("success", "Le token est activé");
                return $this->redirectToRoute('app_accueil');
            }
        }
        //Ici un problème se pose dans le token
        $this->addFlash("danger", "Le token est invalide ou a expiré");
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'vous devez être connecté pour acceder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cette utilisateur est déjà activé');
            return $this->redirectToRoute('app_accueil');
        }

        //On crée le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        //On crée le payload
        $payload = [
            'id' => $user->getId()
        ];

        //On génère un token 
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));




        //On envoie un mail
        $mail->send(
            'no-reply@test.fr',
            $user->getEmail(),
            'Activation de votre compte sur le site fablab',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email de verification envoyé');
        return $this->redirectToRoute('app_accueil');
    }
}