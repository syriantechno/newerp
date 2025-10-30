<?php

namespace Modules\Core\Approvals\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Approvals\Models\Approval;
use Modules\Core\Approvals\Models\ApprovalStep;
use Modules\Core\Approvals\Models\ApprovalLog;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = \Modules\Core\Approvals\Models\Approval::latest()->paginate(15);
        return view('core::approvals.index', compact('approvals'));
    }


    public function create()
    {
        // Strong create form (dynamic steps)
        return view('core::approvals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'record_id' => 'required|integer',
        ]);

        // المتغير للتخزين خارج الترانزكشن
        $approval = null;

        DB::transaction(function () use ($request, &$approval) {
            $approval = Approval::create([
                'title' => $request->title,
                'module' => $request->module,
                'record_id' => $request->record_id,
                'status' => 'pending',
                'current_step' => 1,
            ]);

            // إنشاء الخطوات
            foreach ($request->input('steps', []) as $idx => $s) {
                $approval->steps()->create([
                    'step_order' => $idx + 1,
                    'user_id' => $s['user_id'] ?? null,
                    'status' => $idx === 0 ? 'pending' : 'waiting',
                ]);
            }
            // 🔔 إرسال إشعار للمستخدم الأول في التسلسل (الـapprover)
            $firstStep = $approval->steps()->where('step_order', 1)->first();
            if ($firstStep && $firstStep->user_id) {
                notify_user(
                    $firstStep->user_id,
                    msg('approvals.assigned'),
                    msg('notifications.new'),
                    route('approvals.show', $approval->id)
                );
            }


            // أول سجل log
            $approval->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'comment' => 'Approval request created.',
            ]);
        });

        // ✅ بعد نجاح الحفظ تماماً
        notify_user(
            auth()->id(),
            msg('approvals.assigned'),
            msg('notifications.new'),
            route('approvals.show', $approval->id)
        );

        return redirect()->route('approvals.index')->with(msg('approvals.created'));
    }



    public function show($id)
    {
        $approval = Approval::with(['steps.user','logs.user'])->findOrFail($id);
        return view('core::approvals.show', compact('approval'));
    }

    public function action(Request $request, $id)
    {
        $approval = Approval::with('steps')->findOrFail($id);
        $user = auth()->user();
        $action = $request->input('action'); // approve أو reject
        $comment = $request->input('comment', '');

        $currentStep = $approval->steps()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$currentStep) {
            return back()->with('error', 'No active approval step for you.');
        }

        DB::transaction(function () use ($approval, $currentStep, $action, $comment, $user) {
            // تحديث حالة الخطوة الحالية
            $currentStep->update(['status' => $action === 'approve' ? 'approved' : 'rejected']);

            // تسجيل الـ log
            $approval->logs()->create([
                'user_id' => $user->id,
                'action' => $action,
                'comment' => $comment,
            ]);

            if ($action === 'approve') {
                // البحث عن الخطوة التالية
                $nextStep = $approval->steps()
                    ->where('step_order', '>', $currentStep->step_order)
                    ->orderBy('step_order')
                    ->first();

                if ($nextStep) {
                    // تحديث حالة الـApproval والخطوة التالية
                    $approval->update([
                        'status' => 'in_progress',
                        'current_step' => $nextStep->step_order,
                    ]);

                    $nextStep->update(['status' => 'pending']);

                    // ✅ إشعار المستخدم التالي
                    if ($nextStep->user_id) {
                        notify_user(
                            $nextStep->user_id,
                            'Approval Request Assigned',
                            'A new approval request now requires your review.',
                            route('approvals.show', $approval->id)
                        );
                    }
                } else {
                    // لا يوجد خطوات تالية → الموافقة مكتملة
                    $approval->update(['status' => 'approved']);

                    // ✅ إشعار منشئ الطلب بأن الطلب تم اعتماده بالكامل
                    notify_user(
                        $approval->logs()->first()->user_id,
                        'Approval Completed',
                        'Your approval request has been fully approved.',
                        route('approvals.show', $approval->id)
                    );
                }
            } else {
                // ✅ في حال الرفض
                $approval->update(['status' => 'rejected']);

                // إشعار منشئ الطلب بالرفض
                notify_user(
                    $approval->logs()->first()->user_id,
                    'Approval Rejected',
                    'Your approval request has been rejected.',
                    route('approvals.show', $approval->id)
                );
            }
        });

        return redirect()->route('approvals.show', $id)->with('success', 'Action recorded successfully.');
    }


}
