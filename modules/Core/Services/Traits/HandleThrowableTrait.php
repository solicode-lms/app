<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait HandleThrowableTrait
{
    /**
     * Gère une exception de manière centralisée.
     *
     * @param Throwable $e
     * @param callable|null $onFail
     * @param string|null $contextMessage
     */
    public function handleThrowable(Throwable $e, ?callable $onFail = null, ?string $contextMessage = null): void
    {
        if (!app()->isProduction()) {
            throw $e;
        }

        $message = $contextMessage ?: '❌ Exception capturée';

        Log::error($message, [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => collect($e->getTrace())->take(10)->all(),
        ]);

        if (is_callable($onFail)) {
            $onFail();
        }
    }
}
