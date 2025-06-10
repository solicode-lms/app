<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgWidgets\Models\WidgetType;
use Modules\Core\Models\SysModel;
use Modules\PkgWidgets\Models\WidgetOperation;
use Modules\Core\Models\SysColor;
use Modules\PkgWidgets\Models\SectionWidget;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgWidgets\Models\WidgetUtilisateur;

/**
 * Classe BaseWidget
 * Cette classe sert de base pour le modèle Widget.
 */
class BaseWidget extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'type',
        'model',
        'operation',
        'sysColor',
        'sectionWidget'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
        // Colonne dynamique : package
        $sql = "SELECT sm.name
                FROM sys_models s
                JOIN sys_modules sm ON s.sys_module_id = sm.id
                WHERE s.id = widgets.model_id";
        static::addDynamicAttribute('package', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'icon', 'name', 'label', 'type_id', 'model_id', 'operation_id', 'color', 'sys_color_id', 'section_widget_id', 'parameters'
    ];
    public $manyToMany = [
        'Role' => ['relation' => 'roles' , "foreign_key" => "role_id" ]
    ];
    public $manyToOne = [
        'WidgetType' => [
            'model' => "Modules\\PkgWidgets\\Models\\WidgetType",
            'relation' => 'widgetTypes' , 
            "foreign_key" => "widget_type_id", 
            ],
        'SysModel' => [
            'model' => "Modules\\Core\\Models\\SysModel",
            'relation' => 'sysModels' , 
            "foreign_key" => "sys_model_id", 
            ],
        'WidgetOperation' => [
            'model' => "Modules\\PkgWidgets\\Models\\WidgetOperation",
            'relation' => 'widgetOperations' , 
            "foreign_key" => "widget_operation_id", 
            ],
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
            ],
        'SectionWidget' => [
            'model' => "Modules\\PkgWidgets\\Models\\SectionWidget",
            'relation' => 'sectionWidgets' , 
            "foreign_key" => "section_widget_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour WidgetType.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(WidgetType::class, 'type_id', 'id');
    }
    /**
     * Relation BelongsTo pour SysModel.
     *
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(SysModel::class, 'model_id', 'id');
    }
    /**
     * Relation BelongsTo pour WidgetOperation.
     *
     * @return BelongsTo
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(WidgetOperation::class, 'operation_id', 'id');
    }
    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }
    /**
     * Relation BelongsTo pour SectionWidget.
     *
     * @return BelongsTo
     */
    public function sectionWidget(): BelongsTo
    {
        return $this->belongsTo(SectionWidget::class, 'section_widget_id', 'id');
    }

    /**
     * Relation ManyToMany pour Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_widget');
    }

    /**
     * Relation HasMany pour Widgets.
     *
     * @return HasMany
     */
    public function widgetUtilisateurs(): HasMany
    {
        return $this->hasMany(WidgetUtilisateur::class, 'widget_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? "";
    }
}
