<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\ProductViewEvent;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchasePaymentSuccessController extends AbstractController {
    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher) {
        // 1. Je récupère la commande
        $purchase = $purchaseRepository->find($id);

        if(!$purchase || ($purchase && $purchase->getUser() !== $this->getUser()) || $purchase && $purchase->getStatus() === Purchase::STATUS_PAID) {
            $this->addFlash('warning', "La commande n'existe pas");

            return $this->redirectToRoute("purchase_index");
        }

        // 2. Je la fais passer au status PAYÉE (PAID)
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        // 3 . Je vide le panier
        $cartService->empty();

        // Lancer un évènement qui permette aux autres développeurs de réagir à la prise d'une commande
        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        // 4 . Je redirige avec un flash vers la liste de commandes
        $this->addFlash('success', "La commande a été confirmée et payée !");
        return $this->redirectToRoute("purchase_index");
    }
}