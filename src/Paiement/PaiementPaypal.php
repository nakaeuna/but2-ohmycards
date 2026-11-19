<?php

namespace App\Paiement;

use App\Form\Paiements\TypeVide;
use Symfony\Component\Form\FormInterface;

class PaiementPaypal implements MethodePaiement
{
    static private $secret = "SuperSecret";

    public function Valider(FormInterface $form, int $total, array $data): bool
    {
//        if ($data['clef'] != md5($id . static::$secret)) {
//            return false;
//        }

        if (!isset($data['success']) || !$data['success'] || $data['success'] == 'false') {
            echo $data['success'];
            return false;
        }

        if (floatval($data['total']) < $total) {
            return false;
        }
        return true;
    }

    public function getTypeForm(): string
    {
        return TypeVide::class;
    }
}