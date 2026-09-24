<?php



namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BaseQuestion;
use Modules\PkgQcm\Models\Enums\QuestionTypeEnum;

class Question extends BaseQuestion
{
    protected $casts = [
        'type' => QuestionTypeEnum::class,
    ];

    /**
     * Surcharge de __toString pour gérer l'Enum.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->type ? $this->type->label() : "";
    }
}
