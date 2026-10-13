<?php

namespace Modules\PkgTableauBord\Services;

use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur01Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur02Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur03Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur04Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur05Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardIndicateur06Trait;
use Modules\PkgTableauBord\Services\Traits\DashboardSessionTrait;

class TableauBordService
{
    use DashboardIndicateur01Trait, DashboardIndicateur02Trait, DashboardIndicateur03Trait, DashboardIndicateur04Trait, DashboardIndicateur05Trait, DashboardIndicateur06Trait, DashboardSessionTrait;
}
