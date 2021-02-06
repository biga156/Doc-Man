<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Users;
use App\Form\RegistrationType;
//use App\Controller\MailerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            // Set their role
            $user->setRoles(['ROLE_USER_TEMP']);
            
            //set the current datetime
            $user->setCreatedAt(new \DateTime("now",  new DateTimeZone('Europe/Paris')));

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            //Sending email and login
            return $this->redirectToRoute('email_registration_succes',['ta'=>$user->getEmail(), 'un'=>$user->getFirstName()]);
            
        }

        return $this->render('users/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
