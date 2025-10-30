<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ApprovalLog extends Model
{
    protected $table = 'approval_logs';

    protected $fillable = ['approval_id', 'user_id', 'action', 'comment'];

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
