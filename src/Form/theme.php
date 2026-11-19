<?php

namespace App\Form;

use InvalidArgumentException;

enum theme : string
{
    case TOUS = "Tous";
    case POKEMON = "pokemon";
    case YU_GI_OH = "Yu-gi-oh";
    case CLASSIQUE = "Classique";
    case HELLO_KITTY = "Hello Kitty";
}
