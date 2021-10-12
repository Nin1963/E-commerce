<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController {

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté si vous voulez acceder à vos commandes")
     */

    public function index() {
        // 1. Nous devons nous assurer que la personne est connctée (sinon redirection vers la page d'accueil) -> Security
        /** @var User */
        $user = $this->getUser();

        // 2. Nous voulons savoir qui est connecté -> Security
        // 3. Nous voulons âsser l'utilisateur connecté à Twig afin d'afficher ses commandes -> Environment Twig / Response
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}