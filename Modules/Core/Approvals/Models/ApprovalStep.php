<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = [
        'approval_id', 'step_number', 'approver_id',
        'status', 'comment', 'approved_at'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
