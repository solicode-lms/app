<?php

namespace Modules\Core\App\Helpers;

use Modules\Core\App\Rules\StepRule;

class ValidationRuleConverter
{
    /**
     * Convertit les règles Laravel en contraintes HTML5
     *
     * @param array $rules   Liste des règles Laravel (ex: ['nullable','numeric','min:0','lte:bareme', new StepRule(0.5)])
     * @param array $context Contexte avec les valeurs actuelles de l'entité ($e->toArray())
     * @return array         Attributs HTML5 utilisables côté front
     */
    public static function toHtmlAttributes(array $rules, array $context = []): array
    {
        $attrs = [];

        foreach ($rules as $rule) {
            // 🔹 Cas 1 : règle en string
            if (is_string($rule)) {
                if (str_starts_with($rule, 'min:')) {
                    $attrs['min'] = (float) explode(':', $rule, 2)[1];
                }

                if (str_starts_with($rule, 'max:')) {
                    $attrs['max'] = (float) explode(':', $rule, 2)[1];
                }

                if (str_starts_with($rule, 'lte:')) {
                    $field = explode(':', $rule, 2)[1];
                    if (isset($context[$field]) && is_numeric($context[$field])) {
                        $attrs['max'] = $context[$field];
                    }
                }

                if (str_starts_with($rule, 'gte:')) {
                    $field = explode(':', $rule, 2)[1];
                    if (isset($context[$field]) && is_numeric($context[$field])) {
                        $attrs['min'] = $context[$field];
                    }
                }

                if ($rule === 'required') {
                    $attrs['required'] = true;
                }

                if ($rule === 'nullable') {
                    unset($attrs['required']);
                }

                if ($rule === 'numeric') {
                    $attrs['step'] = $attrs['step'] ?? 'any';
                }

                if (str_starts_with($rule, 'step:')) {
                    $attrs['step'] = explode(':', $rule, 2)[1];
                }
            }

            // 🔹 Cas 2 : règle en objet
            if ($rule instanceof StepRule) {
                $attrs['step'] = $rule->step;
            }
        }

        return $attrs;
    }
}
