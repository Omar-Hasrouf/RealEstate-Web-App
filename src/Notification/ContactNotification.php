<?php


namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
{

    /**
     * @var SwiftMailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {

        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('Maskan: ', $contact->getProperty()->getTitle()))
            ->setFrom('noreply@maskan.com')
            ->setTo('contact@maskan.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig',[
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }
}