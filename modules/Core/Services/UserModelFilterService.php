<?php


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseUserModelFilterService;

/**
 * Classe UserModelFilterService pour gérer la persistance de l'entité UserModelFilter.
 */
class UserModelFilterService extends BaseUserModelFilterService
{
   

    public function storeLastFilter(string $context_key, string $modelName, array $filters): void
    {

        \Modules\Core\Models\UserModelFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'model_name' => $modelName,
                'context_key' => $context_key
            ],
            [
                'filters' => $filters,
            ]
        );
    }

    public function getLastSavedFilter(string $context_key,string $modelName): ?array
    {
        if (!auth()->check()) return null;

        $record = \Modules\Core\Models\UserModelFilter::where('user_id', auth()->id())
                    ->where('model_name', $modelName)
                    ->where('context_key', $context_key)
                    ->first();

        return $record ? $record->filters : null;
    }

   
}
