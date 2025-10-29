<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'module',
        'record_id',
        'status',
        'current_step',
        'created_by'
    ];

    public function steps()
    {
        return $this->hasMany(ApprovalStep::class);
    }

    public function logs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    public function currentStep()
    {
        return $this->hasOne(ApprovalStep::class)->where('status', 'pending');
    }
}
