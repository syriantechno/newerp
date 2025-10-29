<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $fillable = [
        'approval_id', 'user_id', 'action', 'comment'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }
}
