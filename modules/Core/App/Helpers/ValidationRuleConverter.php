<?php

namespace Modules\Core\App\Helpers;

class ValidationRuleConverter
{
    /**
     * Convertit les règles Laravel en contraintes HTML5
     */
    public static function toHtmlAttributes(array $rules, array $context = []): array
    {
        $attrs = [];

        foreach ($rules as $rule) {
            if (is_string($rule)) {
                // min:0
                if (str_starts_with($rule, 'min:')) {
                    $attrs['min'] = (float) explode(':', $rule)[1];
                }

                // max:10
                if (str_starts_with($rule, 'max:')) {
                    $attrs['max'] = (float) explode(':', $rule)[1];
                }

                // lte:bareme
                if (str_starts_with($rule, 'lte:')) {
                    $field = explode(':', $rule)[1];
                    if (isset($context[$field])) {
                        $attrs['max'] = $context[$field]; // 👈 prend la valeur réelle
                    }
                }

                // gte:bareme
                if (str_starts_with($rule, 'gte:')) {
                    $field = explode(':', $rule)[1];
                    if (isset($context[$field])) {
                        $attrs['min'] = $context[$field];
                    }
                }

                // required
                if ($rule === 'required') {
                    $attrs['required'] = true;
                }

                // nullable annule required
                if ($rule === 'nullable') {
                    unset($attrs['required']);
                }

                // numeric → type=number (déjà géré côté JS)
                if ($rule === 'numeric') {
                    $attrs['step'] = 'any';
                }
            }
        }

        return $attrs;
    }
}
