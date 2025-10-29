<?php

namespace Modules\Core\Approvals\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Approvals\Models\{Approval, ApprovalStep, ApprovalLog};
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = Approval::with('steps')->latest()->get();
        return view('core::approvals.index', compact('approvals'));
    }

    // قبول خطوة
    public function approveStep(Request $request, $stepId)
    {
        $step = ApprovalStep::findOrFail($stepId);
        $approval = $step->approval;

        // تحديث الخطوة الحالية
        $step->update([
            'status' => 'approved',
            'comment' => $request->comment,
            'approved_at' => now(),
        ]);

        // إنشاء سجل في اللوج
        ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
            'comment' => $request->comment,
        ]);

        // فتح الخطوة التالية
        $nextStep = ApprovalStep::where('approval_id', $approval->id)
            ->where('step_number', '>', $step->step_number)
            ->orderBy('step_number')
            ->first();

        if ($nextStep) {
            $nextStep->update(['status' => 'pending']);
            $approval->update(['current_step' => $nextStep->id]);
            // 🔔 إرسال إشعار للمعتمد التالي (لاحقًا)
        } else {
            // آخر خطوة → الموافقة الكاملة
            $approval->update(['status' => 'approved']);
        }

        return response()->json(['status' => 'success']);
    }

    // رفض خطوة
    public function rejectStep(Request $request, $stepId)
    {
        $step = ApprovalStep::findOrFail($stepId);
        $approval = $step->approval;

        $step->update([
            'status' => 'rejected',
            'comment' => $request->comment,
        ]);

        $approval->update(['status' => 'rejected']);

        ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'comment' => $request->comment,
        ]);

        // 🔔 إشعار للمنشئ الأصلي (لاحقًا)
        return response()->json(['status' => 'success']);
    }
    public function show($id)
    {
        $approval = Approval::with('steps')->findOrFail($id);
        return view('core::approvals.show', compact('approval'));
    }
}
