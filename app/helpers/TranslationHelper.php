<?php

use Illuminate\Support\Facades\Config;

/**
 * Generates a localized title for an index page.
 *
 * This function retrieves the plural form of the entity name from the translation
 * system and constructs a localized title based on the current application locale.
 *
 * @param string $entity_key The key of the entity in the translation system.
 * @return string The localized index title.
 */
function curd_index_title(string $entity_key): string
{
    $locale = Config::get('app.locale', 'en');
    $modelName = strtolower(trans($entity_key . '.plural'));

    $titles = [
        'fr' => 'Liste des ' . $modelName,
        'en' => 'List of ' . $modelName,
    ];

    return $titles[$locale] ?? $titles['en'];
}

/**
 * Generates a localized "Add" label for a given entity.
 *
 * This function retrieves the singular form of the entity name from the translation
 * system and constructs a localized "Add" label based on the current application locale.
 *
 * @param string $entity_key The key of the entity in the translation system.
 * @return string The localized "Add" label.
 */
function curd_index_add_label(string $entity_key): string
{
    $locale = Config::get('app.locale', 'en');
    $modelName = strtolower(trans($entity_key . '.singular'));

    $messages = [
        'fr' => "Ajouter un $modelName",
        'en' => "Add $modelName",
    ];

    return $messages[$locale] ?? $messages['en'];
}
