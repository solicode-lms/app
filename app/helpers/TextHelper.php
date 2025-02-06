<?php

namespace App\Helpers;

use DOMDocument;
use Illuminate\Support\Str;

class TextHelper
{
    public static function formatHtmlWithLineBreaks($html, $lineLength = 80)
    {
        // Supprimer temporairement les balises HTML pour compter les caractères
        $textOnly = strip_tags($html);

        // Ajouter un saut de ligne tous les 80 caractères sans couper les mots
        $wrappedText = preg_replace('/(.{'.$lineLength.',}?\s)/u', "$1\n", $textOnly);

        // Réinsérer le HTML d'origine et les sauts de ligne sous forme de <br>
        return nl2br($wrappedText);
    }

     
}
