<?php

namespace Modules\Core\Approvals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Approval extends Model
{
    protected $table = 'approvals';

    protected $fillable = [
        'title', 'module', 'record_id', 'status', 'current_step'
    ];

    public const STATUS_PENDING     = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_APPROVED    = 'approved';
    public const STATUS_REJECTED    = 'rejected';

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('step_order');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class)->latest();
    }

    public function activeStep(): ?ApprovalStep
    {
        return $this->steps()
            ->where('step_order', $this->current_step)
            ->where('status', ApprovalStep::STATUS_PENDING)
            ->first();
    }

    public function hasNextStep(): bool
    {
        return $this->steps()->where('step_order', '>', $this->current_step)->exists();
    }

    public function nextStep(): ?ApprovalStep
    {
        return $this->steps()->where('step_order', $this->current_step + 1)->first();
    }
}
