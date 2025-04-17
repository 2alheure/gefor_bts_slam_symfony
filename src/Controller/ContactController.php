<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController {
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response {
        $form = $this->createForm(ContactType::class, [
            'subject' => 'Demande de contact',
            'date' => new \DateTime(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = new Email();
            $email
                ->from($data['email'])
                ->to('emmanuel.macron@elysee.gouv.fr')
                ->subject($data['subject'])
                ->text($data['message']);

            $mailer->send($email);
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
