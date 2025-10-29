@extends('layouts.user_type.auth')
@section('title', 'إضافة طلب موافقة')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">➕ إضافة طلب موافقة جديدة</h3>
        <form method="POST" action="{{ route('approvals.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">الوحدة (Module)</label>
                <input type="text" name="module" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">رقم السجل (Record ID)</label>
                <input type="number" name="record_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">المستخدمون بالتسلسل</label>
                <select name="approvers[]" class="form-select" multiple required>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">اختر المستخدمين حسب ترتيب الموافقة.</small>
            </div>

            <button type="submit" class="btn btn-primary">حفظ</button>
        </form>
    </div>
@endsection
