<?php


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseUserModelFilterService;

/**
 * Classe UserModelFilterService pour gérer la persistance de l'entité UserModelFilter.
 */
class UserModelFilterService extends BaseUserModelFilterService
{
    public function dataCalcul($userModelFilter)
    {
        // En Cas d'édit
        if(isset($userModelFilter->id)){
          
        }
      
        return $userModelFilter;
    }

    public function storeLastFilter(string $modelName, array $filters): void
    {

        \Modules\Core\Models\UserModelFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'model_name' => $modelName,
            ],
            [
                'filters' => $filters,
            ]
        );
    }

    public function getLastSavedFilter(string $modelName): ?array
    {
        if (!auth()->check()) return null;

        $record = \Modules\Core\Models\UserModelFilter::where('user_id', auth()->id())
                    ->where('model_name', $modelName)
                    ->first();

        return $record ? $record->filters : null;
    }

   
}
