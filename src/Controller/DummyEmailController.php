<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class DummyEmailController extends AbstractController {

    #[Route('/test/email', name: 'dummy_email')]
    public function sendEmail(MailerInterface $mailer) {
        $email = new Email();
        $email->from('toto@monupernomdedomaine.com')
            ->to('emmanuel.macron@elysee.gouv.fr')
            ->subject('Test email')
            ->text('This is a test email sent from Symfony!')
            ->html('<p style="color:red">This is a test email sent from Symfony!</p>');

        $mailer->send($email);

        dd($email);
    }
}
