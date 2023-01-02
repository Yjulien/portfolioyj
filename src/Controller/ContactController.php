<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer, FlashBagInterface $flashBag): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $adress = $data['email'];
            $message = $data['message'];
            $email = (new Email())
            ->from('contact@yjulien.com')
            ->to('contact@yjulien.com') 
            ->subject('Demande de contact')
            ->text($message);
            
        $mailer->send($email);
        // Ajoutez le message flash
        $flashBag->add('success', 'Votre message a été envoyé avec succès');
        }

        return $this->renderForm('contact/_form.html.twig', [
            'controller_name' => 'ContactController',
            'formulaire' => $form,
        ]);
        
    }
}
