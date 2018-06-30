<?php

declare(strict_types=1);

namespace App\Processor;

use App\Mailer\NewsletterMailer;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrProcessor;
use Enqueue\Client\TopicSubscriberInterface;

class NewsletterSenderProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * @var NewsletterMailer
     */
    private $mailer;

    public function __construct(NewsletterMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function process(PsrMessage $message, PsrContext $session)
    {
        list('email' => $email, 'username' => $username, 'newsletter' => $newsletter) = $message->getBody();

        try{
            $this->mailer->senNewsletterEmailMessage($email, $newsletter, $username);
        } catch (\Exception $exception)
        {
            return self::REQUEUE;
        }

        return self::ACK;
    }

    public static function getSubscribedTopics()
    {
        return ['newsletter'];
    }
}