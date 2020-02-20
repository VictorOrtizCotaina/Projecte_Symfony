<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/contact_form", name="contact_form")
     * @param \Swift_Mailer $mailer
     * @param Request $request
     */
    public function index(\Swift_Mailer $mailer, Request $request)
    {
        $name = null;
        $email = null;
        $subject = null;
        $text = null;

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $email = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $text = $form->get('text')->getData();
        }

        $message = (new \Swift_Message($subject))
            ->setFrom($email)
            ->setTo('vortiz.prueba.email@gmail.com')
            ->setBody(
                $this->renderView(
                    'front-office/email/email.html.twig',
                    ['name' => $name, 'text' => $text]
                ),
                'text/html'
            );

        $mailer->send($message);
        return $this->render('front-office/email/show.email_form.html.twig', [
            'title' => "Foro Programacion • Contacto",
            'EmailForm' => $form->createView()
        ]);

    }
}
