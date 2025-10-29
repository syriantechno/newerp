<?php

namespace App\Traits;

use App\Models\DynamicField;
use App\Models\DynamicFieldValue;

trait HasDynamicFields
{
    /**
     * Automatically handle saving custom dynamic field values
     * whenever a model using this trait is saved.
     */
    public static function bootHasDynamicFields()
    {
        static::saved(function ($model) {
            if (request()->has('custom')) {
                foreach (request('custom') as $fieldId => $value) {
                    DynamicFieldValue::updateOrCreate(
                        [
                            'module'    => $model->getModuleName(),
                            'record_id' => $model->id,
                            'field_id'  => $fieldId,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }
        });
    }

    /**
     * Relationship to all custom values for this record
     */
    public function customValues()
    {
        return $this->hasMany(DynamicFieldValue::class, 'record_id')
            ->where('module', $this->getModuleName())
            ->with('field');
    }

    /**
     * Returns the module name automatically from class namespace
     * Example: Modules\HR\Models\Employee -> "HR"
     */
    public function getModuleName()
    {
        $parts = explode('\\', static::class);
        return $parts[1] ?? 'Global';
    }

    /**
     * Retrieve all active dynamic fields for this model's module
     */
    public function getCustomFields()
    {
        return DynamicField::where('module', $this->getModuleName())
            ->where('is_active', 1)
            ->get();
    }

    /**
     * Helper to save dynamic field values manually if needed
     */
    public function saveCustomFields(array $customData)
    {
        foreach ($customData as $fieldId => $value) {
            DynamicFieldValue::updateOrCreate(
                [
                    'module'    => $this->getModuleName(),
                    'record_id' => $this->id,
                    'field_id'  => $fieldId,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }
}
