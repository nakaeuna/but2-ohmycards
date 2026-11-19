<?php

namespace App\Paiement;

use App\Form\Paiements\TypeCB;
use Symfony\Component\Form\FormInterface;

class PaiementCB implements MethodePaiement
{
    public function Valider(FormInterface $form, int $total, array $data): bool
    {
        if (!isset($data['success']) || !$data['success'] || $data['success'] == 'false') {
            return false;
        }

        if (floatval($data['total']) < $total) {
            return false;
        }
        return true;
    }

    public function getTypeForm(): string
    {
        return TypeCB::class;
    }
}