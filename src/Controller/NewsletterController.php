<?php

declare(strict_types=1);

namespace App\Controller;

use App\AppEvents;
use App\Entity\NewsletterSubscription;
use App\Form\Type\NewsletterSubscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class NewsletterController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     *
     * @Route(name="newsletter_index", path="/")
     */
    public function indexAction(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $newsletterSubscription = new NewsletterSubscription();

        $form = $this->createForm(NewsletterSubscriptionType::class, $newsletterSubscription);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'app.ui.added_to_newsletter');
            $em = $this->getDoctrine()->getManager();

            $em->persist($newsletterSubscription);
            $em->flush();

            $event = new GenericEvent($newsletterSubscription);
            $dispatcher->dispatch(AppEvents::SUBSCRIBED, $event);
        }

        return $this->render('Newsletter/index.html.twig', [
            'newsletter' => $newsletterSubscription,
            'form' => $form->createView(),
        ]);
    }
}