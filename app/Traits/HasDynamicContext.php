<?php

namespace App\Traits;

use App\Scopes\DynamicContextScope;

trait HasDynamicContext
{
    public static bool $activeScope = false;
    
    /**
     * Appliquer le scope global DynamicContextScope.
     */
    public static function bootHasDynamicContext()
    {
        static::addGlobalScope(new DynamicContextScope());
    }

    
    public static function withScope(callable $callback)
    {
        static::$activeScope = true;
        $result = $callback();
        static::$activeScope = false;
        return $result;
    }

}
