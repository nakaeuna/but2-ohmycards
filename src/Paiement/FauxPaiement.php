<?php
namespace App\Paiement;

use App\Form\Paiements\TypeFaux;
use Symfony\Component\Form\FormInterface;

class FauxPaiement implements MethodePaiement
{
    public function Valider(FormInterface $form, int $total, array $data): bool
    {
        return true;
    }

    public function getTypeForm(): string
    {
        return TypeFaux::class;
    }
}