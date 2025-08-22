<?php

namespace Modules\Core\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StepRule implements Rule
{
    public float $step;

    public function __construct(float $step)
    {
        $this->step = $step;
    }

    public function passes($attribute, $value): bool
    {
       return true;
    }

    public function message(): string
    {
        return "La valeur doit être un multiple de {$this->step}.";
    }
}
