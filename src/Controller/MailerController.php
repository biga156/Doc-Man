<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Mime\Address;

class MailerController extends AbstractController
{
    /**
     * @Route("/email_registration", name="email_registration_succes", methods={"GET"})
     */
    public function sendEmail(MailerInterface $mailer)
    {
         
        $email = (new Email())
            ->from('bigastora@gmail.com')
            ->to($_GET['ta'])
            //->cc('cc@example.com')
            ->bcc('bigastora@gmail.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Registration succes!')
            ->text($_GET['un'].', you have successfully registered!');
            //->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        return $this->redirectToRoute('app_login');
        
    }
}