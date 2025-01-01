<?php

namespace App\Traits;

use App\Scopes\DynamicContextScope;

trait HasDynamicContext
{
    /**
     * Appliquer le scope global DynamicContextScope.
     */
    public static function bootHasDynamicContext()
    {
        static::addGlobalScope(new DynamicContextScope());
    }
}
