<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use App\Purchase\PurchhasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PurchaseConfirmationController extends AbstractController {

    protected $cartService;
    protected $em;
    protected $persister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER"), message="Vous devez être connecté pour valider une commande"
     */
    public function confirm(Request $request) {
        // 1. Nous voulons lire les données du formulaire => FormFactory / Request
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        // 2. Si le formulaire n'a pas été soumis : dégager
        if(!$form->isSubmitted()) {
            //Message Flash puis redirection (FlashBagInterface)
            $this->addFlash('warning', "Vous devez remplir le formulaire de confirmation");
            return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 4. Si il n'y a pas de produits dans mon panier : dégager (CartService)
        $carItems = $this->cartService->getDetailedCartItems();

        if(count($carItems) === 0) {
            $this->addFlash('warning', "Vous ne pouvez pas confimez la commande avec un panier vide");

            return $this->redirectToRoute('cart_show');
        }

        // 5. Nous allons créer une Purchase
        /**
         * @var Purchase
         */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);


        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}