<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\NewsletterSubscriptionRepository;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNewsletterCommand extends Command
{
    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var NewsletterSubscriptionRepository
     */
    private $repository;

    public function __construct(NewsletterSubscriptionRepository $repository ,ProducerInterface $producer)
    {
        $this->producer = $producer;
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:send')
            ->setDescription('Send newsletter')
            ->setHelp('This command allows you to send a newsletter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newsletterSubscriptions = $this->repository->findByNewsletterName('newsletter 1');
        foreach ($newsletterSubscriptions as $newsletterSubscription)
        {
            $this->producer->sendCommand('newsletter', [
                'email' => $newsletterSubscription->getEmail(),
                'username' => $newsletterSubscription->getUsername(),
                'newsletter' => $newsletterSubscription->getNewsletter()->getName(),
            ]);
        }
    }
}