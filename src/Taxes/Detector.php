<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Detector {

    protected $seuil;

    public function __construct($seuil) 
    {
        $this->seuil = $seuil;
    }

    public function detect(float $amount) : bool {

        if ($amount > $this->seuil) {
            return true;
        } else {
            return false;
        }
    }
}