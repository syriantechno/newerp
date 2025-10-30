<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ApprovalStep extends Model
{
    protected $table = 'approval_steps';

    protected $fillable = ['approval_id', 'user_id', 'step_order', 'status'];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAssignedTo(?int $userId): bool
    {
        return !is_null($this->user_id) && $this->user_id === $userId;
    }
}
