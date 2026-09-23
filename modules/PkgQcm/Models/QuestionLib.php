<?php
namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BaseQuestionLib;
use Modules\PkgQcm\Models\Enums\QuestionTypeEnum;

class QuestionLib extends BaseQuestionLib
{
    protected $casts = [
        'type' => QuestionTypeEnum::class,
    ];

    public function __toString(): string
    {
        // On surcharge __toString car BaseQuestionLib utilise $this->type qui est maintenant un objet Enum
        return $this->type?->label() ?? (string) $this->id;
    }
}
