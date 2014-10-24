<?php
// src/KK/StartBootStrapBundle/Services/EmailManager.php

namespace KK\StartBootStrapBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;


class EmailManager
{
    protected $mailer;
    protected $emailFrom;


    public function __construct(\Swift_Mailer $mailer, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->emailFrom = $emailFrom;
    }

    /**
     * Compose email
     *
     * @param String $recipientEmail
     * @param String $bodyHtml
     * @return \Swift_Message
     */
    public function composeEmail($recipientEmail, $bodyHtml)
    {
        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();

        $message
            ->setBody($bodyHtml, 'text/html')
//            ->setTo($recipientEmail) // User input email address for recipient (do not use message.html.twig or email.txt.twig if using this)
            ->setTo('example@email.com') // Hardcode email address recipient (email response address provided by user)
            ->setFrom($this->emailFrom);

        return $message;
    }

    /**
     * Send email
     *
     * @param \Swift_Message $message;
     */
    public function sendEmail(\Swift_Message $message)
    {
        if(!$this->mailer->getTransport()->isStarted()){
            $this->mailer->getTransport()->start();
        }

        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();
    }

}