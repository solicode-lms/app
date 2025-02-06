<?php


namespace Modules\PkgGapp\Models;

use Modules\PkgGapp\App\Enums\FieldTypeEnum;
use Modules\PkgGapp\Models\Base\BaseEMetadatum;

class EMetadatum extends BaseEMetadatum
{


    public function generateReference(): string
    {
        $objet_reference = "";
        if($this->eDataField != null) {
            $objet_reference = $this->eDataField->reference;
        }
        if($this->eModel != null) {
            $objet_reference = $this->eModel->reference;
        }

        return $objet_reference . "_" . $this->eMetadataDefinition->reference ;
    }

    /**
     * Accesseur pour récupérer dynamiquement la valeur selon le type.
     *
     * @return mixed
     */
    public function getValue()
    {

        if (!$this->eMetadataDefinition) {
            return null;
        }
        
        switch ($this->eMetadataDefinition->type) {
            case FieldTypeEnum::STRING->value:
                return $this->value_string;
            case FieldTypeEnum::INTEGER->value:
                return $this->value_integer;
            case FieldTypeEnum::FLOAT->value:
                return $this->value_float;
            case FieldTypeEnum::BOOLEAN->value:
                return $this->value_boolean;
            case FieldTypeEnum::DATE->value:
                return $this->value_date;
            case FieldTypeEnum::DATETIME->value:
                return $this->value_datetime;
            case FieldTypeEnum::ENUM->value:
                return $this->value_enum;
            case FieldTypeEnum::JSON->value:
                return "JSON";
            case FieldTypeEnum::TEXT->value:
                return $this->value_text;
            default:
                return null;
        }
    }


}
