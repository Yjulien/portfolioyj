<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
            
            $name = $data['name'];
            $adress = $data['email'];
            $message = $data['message'];
            $email = (new TemplatedEmail())
            ->from('mail@yjulien.com')
            ->to('contact@yjulien.com') 
            ->replyTo('mathieusiaudeau@gmail.com')
            ->subject('Demande de contact')
            ->htmlTemplate('mail/mailer/mailExploit.html.twig')
            ->context([
                'name' => $name,
                'content' => $message
            ]);
            
            try {
                $mailer->send($email);
                $flashBag->add('success', 'Votre message a été envoyé avec succès');
            } catch (TransportExceptionInterface $e) {
                $flashBag->add('error', 'error :' .$e);
            }
        }
       

        return $this->renderForm('contact/_form.html.twig', [
            'controller_name' => 'ContactController',
            'formulaire' => $form,
        ]);
        
    }
}
