<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $userPasswordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        UserAuthenticator $authenticator,
        EntityManagerInterface
        $entityManager,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        // $form = $this->createForm(RegistrationFormType::class, $user);
        // $form->handleRequest($request);
        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $password_confirm = $request->request->get('password_confirm');

        if (!empty($nom) && !empty($email) && !empty($password)) {

            if ($password == $password_confirm) {
                $user->setEmail($email);
                $user->setName($nom);
                // Roles
                $users = $userRepository->findAll();
                if ($users != null) {
                    $user->setRoles(["USER"]);
                } else {
                    $user->setRoles(["ADMIN"]);
                }

                $user->setPassword(
                    // encode the plain password
                    $userPasswordEncoder->encodePassword(
                        $user,
                        $password
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $this->addFlash("passwords_match_errors", "Vos mots de passe ne correspondent pas");
                return $this->redirect("register");
            }


            // generate a signed url and email it to the user
            // $this->emailVerifier->sendEmailConfirmation(
            //     'app_verify_email',
            //     $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('bitsapascal033@gmail.com', 'PascalBitsa'))
            //         ->to($user->getEmail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register2.html.twig', [
            // 'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        // try {
        //     $this->emailVerifier->handleEmailConfirmation($request, $user);
        // } catch (VerifyEmailExceptionInterface $exception) {
        //     $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

        //     return $this->redirectToRoute('app_register');
        // }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
