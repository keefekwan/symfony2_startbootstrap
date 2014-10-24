<?php
// src/KK/StartBootStrapBundle/Form/FromHandler/ContactFormHandler.php

namespace KK\StartBootStrapBundle\Form\FormHandler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\TwigBundle\TwigEngine;
use KK\StartBootStrapBundle\Service\EmailManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Handles Contact forms
 */
class ContactFormHandler
{

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The Template Engine
     */
    protected $templating;

    /**
     * The email manager
     */
    protected $emailManager;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param Request $request;
     * @param TwigEngine $templating
     * @param EmailManager $emailManager
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(Request $request, TwigEngine $templating, EmailManager $emailManager, Container $container)
    {
        $this->request = $request;
        $this->templating = $templating;
        $this->emailManager = $emailManager;
        $this->container = $container;

    }

    /**
     * Processes the form with the request
     *
     * @param Form $form
     * @return Email|false
     */
    public function process(Form $form)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }

        $form->submit($this->request);

        if ($form->isValid()) {
            return $this->processValidForm($form);
        }

        return false;
    }

    /**
     * Processes the valid form, sends the email
     *
     * @param \Symfony\Component\Form\Form $form
     * @internal param $Form
     * @return EmailInterface The email sent
     */
    public function processValidForm(Form $form)
    {
        /** @var EmailInterface */
        $email = $this->composeEmail($form);

        /** Send Email */
        $this->emailManager->sendEmail($email);

        return $email;
    }

    /**
     * Composes the email from the form
     *
     * @param Form $form
     * @return \Swift_Message
     */
    public function composeEmail(Form $form)
    {
        $recipientEmail = $form->get('email')->getData();
        $bodyHTML = $this->container->get('templating')->render(
            'Bootstrap/message.html.twig',
            array(
                'ip'      => $this->request->getClientIp(),
                'name'    => $form->get('name')->getData(),
                'message' => $form->get('message')->getData(),
                'form'    => $form->createView(), // Only need to use this if you are using index.html.twig as the form to send (which needs 'form' variable)
                'from'    => $form->get('email')->getData(),
            )
        );

        /** @var \Swift_Message */
        return $this->emailManager->composeEmail($recipientEmail, $bodyHTML);
    }

}