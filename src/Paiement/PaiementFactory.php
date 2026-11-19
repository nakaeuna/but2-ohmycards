<?php

namespace App\Paiement;

class PaiementFactory
{
    public static function getMethodePaiement(string $id): MethodePaiement
    {
        switch ($id) {
            case "CB":
                return new PaiementCB();
            case "Paypal":
                return new PaiementPaypal();
            case "Faux":
                return new FauxPaiement();
            default:
                throw new \Exception("Unknown Payment Method");
        }
    }
}