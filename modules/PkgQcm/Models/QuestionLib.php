<?php
namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BaseQuestionLib;
use Modules\PkgQcm\Models\Enums\QuestionTypeEnum;

class QuestionLib extends BaseQuestionLib
{
    protected $casts = [
        'type' => QuestionTypeEnum::class,
    ];
}
