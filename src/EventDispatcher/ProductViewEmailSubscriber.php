<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductviewEvent;
use Symfony\Component\Mime\Email;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewEmailSubscriber implements EventSubscriberInterface {
    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer) {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmail'
        ];
    }

    public function sendEmail(ProductviewEvent $productviewEvent) {
        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@mail.com", "Infos de la boutique"))
        //     ->to("admin@mail.com")
        //     ->text("Un visiteur est en train de voir la page du produit n°" . $productviewEvent->getProduct()->getId())
        //     ->htmlTemplate('emails/product_view.html.twig')
        //     ->context([
        //         'product' => $productviewEvent->getProduct()
        //     ])
        //     ->subject("Visite du produit n°" . $productviewEvent->getProduct()->getId());

        //     $this->mailer->send($email);

        $this->logger->info("Un email a été envoyé à l'admin pour le produit" . $productviewEvent->getProduct()->getId());
    }
}

