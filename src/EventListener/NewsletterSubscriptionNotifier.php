<?php

declare(strict_types=1);

namespace App\EventListener;

use App\AppEvents;
use App\Entity\NewsletterSubscription;
use App\Mailer\NewsletterMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewsletterSubscriptionNotifier implements EventSubscriberInterface
{
    private $router;

    private $mailer;

    /**
     * EmailConfirmationListener constructor.
     *
     * @param UrlGeneratorInterface   $router
     */
    public function __construct(UrlGeneratorInterface $router, NewsletterMailer $mailer)
    {
        $this->router = $router;
        $this->mailer = $mailer;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            AppEvents::SUBSCRIBED => 'onSubscriptionSuccess',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onSubscriptionSuccess(GenericEvent $event)
    {
        /** @var NewsletterSubscription $newsletterSubscription */
        $newsletterSubscription = $event->getSubject();

        $this->mailer->sendConfirmationEmailMessage($newsletterSubscription);
    }
}