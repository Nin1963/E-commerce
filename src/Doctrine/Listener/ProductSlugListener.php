<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener {

    protected $slugger;

    public function __construct(SluggerInterface $slugger) 
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $product) {
        if(empty($product->getSlug())) {
            // SluggerInterface
            $product->setSlug(strtolower($this->slugger->slug($product->getName())));
        }
    }
}