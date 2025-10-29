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
        $approvals = \Modules\Core\Approvals\Models\Approval::withCount('steps')->latest()->get();
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
        $approval = \Modules\Core\Approvals\Models\Approval::with(['steps.user', 'logs.user'])->findOrFail($id);
        return view('core::approvals.show', compact('approval'));
    }




    public function action(Request $request, $id)
    {
        $approval = \Modules\Core\Approvals\Models\Approval::findOrFail($id);
        $user = auth()->user();
        $action = $request->input('action'); // approve / reject
        $comment = $request->input('comment');

        $step = \Modules\Core\Approvals\Models\ApprovalStep::where('approval_id', $approval->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$step) {
            return back()->with('error', 'No active approval step for you.');
        }

        $step->status = $action;
        $step->notes = $comment;
        $step->save();

        \Modules\Core\Approvals\Models\ApprovalLog::create([
            'approval_id' => $approval->id,
            'user_id' => $user->id,
            'action' => $action,
            'comment' => $comment,
        ]);

        $remaining = \Modules\Core\Approvals\Models\ApprovalStep::where('approval_id', $approval->id)
            ->where('status', 'pending')
            ->count();

        if ($action === 'approve' && $remaining === 0) {
            $approval->status = 'approved';
        } elseif ($action === 'reject') {
            $approval->status = 'rejected';
        } else {
            $approval->status = 'in_progress';
        }

        $approval->save();

        return redirect()->route('approvals.show', $approval->id)
            ->with('success', 'Action applied successfully.');
    }

    public function create()
    {
        $users = \App\Models\User::all(['id','name']); // لاختيار المراجعين
        return view('core::approvals.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|string',
            'record_id' => 'required|integer',
            'approvers' => 'required|array', // قائمة المستخدمين بالترتيب
        ]);

        $approval = \Modules\Core\Approvals\Models\Approval::create([
            'module' => $data['module'],
            'record_id' => $data['record_id'],
            'status' => 'pending',
        ]);

        foreach ($data['approvers'] as $i => $userId) {
            \Modules\Core\Approvals\Models\ApprovalStep::create([
                'approval_id' => $approval->id,
                'user_id' => $userId,
                'step_order' => $i + 1,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('approvals.index')
            ->with('success', 'تم إنشاء طلب الموافقة بنجاح.');
    }




}
