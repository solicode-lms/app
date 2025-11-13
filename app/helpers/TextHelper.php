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


     /**
     * Nettoyer un contenu HTML avant de l'injecter dans un <textarea>.
     *
     * - Empêche la fermeture accidentelle du <textarea>
     * - Neutralise les délimiteurs Blade dans le texte
     */
    public static function sanitizeTextarea($html)
    {
        $html ??= '';

        // 1) Empêcher la fermeture du textarea
        $html = preg_replace('~</textarea~i', '&lt;/textarea', $html);

        // 2) Empêcher d’éventuels délimiteurs Blade dans le texte d’exemple
        $html = str_replace(['{!!', '!!}'], ['&#123;!!', '!!&#125;'], $html);
        $html = str_replace(['{{', '}}'], ['&#123;&#123;', '&#125;&#125;'], $html);

        return $html;
    }

     
}
