<?php

namespace App\Paiement;

use App\Form\Paiements\TypeCB;
use App\Form\Paiements\TypeFaux;
use App\Form\Paiements\TypeVide;
use Symfony\Component\Form\FormInterface;

interface MethodePaiement
{
    public function Valider(FormInterface $form, int $total, array $data): bool;

    public function getTypeForm(): string;
}



