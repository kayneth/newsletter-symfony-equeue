<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\NewsletterSubscription;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewsletterMailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
    /**
     * @var UrlGeneratorInterface
     */
    protected $router;
    /**
     * @var \Twig_Environment
     */
    protected $templating;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer         $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment       $templating
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface  $router, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
    }

    /**
     * @param NewsletterSubscription $newsletterSubscription
     */
    public function sendConfirmationEmailMessage(NewsletterSubscription $newsletterSubscription)
    {
            // TODO: Add an unsubscribe link
//        $url = $this->router->generate('unsubscribe', array('token' => 'todo', UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render('Newsletter/Mail/success.html.twig', array(
            'newsletterSubscription' => $newsletterSubscription,
        ));
        $this->sendEmailMessage($rendered, 'test@test.fr', (string) $newsletterSubscription->getEmail());
    }

    /**
     * @param string $email
     * @param string $newsletter
     * @param null $username
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendNewsletterEmailMessage(string $email, string $newsletter, $username = null)
    {
        $rendered = $this->templating->render('Newsletter/Mail/newsletter.html.twig', array(
            'newsletter' => $newsletter,
            'username' => $username,
        ));
        $this->sendEmailMessage($rendered, 'test@test.fr', (string) $email);
    }

    /**
     * @param string       $renderedTemplate
     * @param array|string $fromEmail
     * @param array|string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);
        $this->mailer->send($message);
    }
}
