@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Companies</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                <i class="fas fa-plus"></i> Add Company
            </button>
        </div>

        {{-- ✅ Unified DataTable Component --}}
        <x-datatable
            id="companiesTable"
            route="{{ route('hr.companies.table') }}"
            :columns="['ID', 'Name', 'Trade License', 'VAT Number', 'Actions']"
        />
    </div>

    {{-- ✅ Add Company Modal --}}
    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Add Company</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCompanyForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trade License</label>
                            <input type="text" class="form-control" name="trade_license">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Number</label>
                            <input type="text" class="form-control" name="vat_number">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("🧩 [HR] Companies page loaded");

            const form = $('#addCompanyForm');
            const modalEl = document.getElementById('addCompanyModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

            form.on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('hr.companies.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function() {
                        toastr.success('Company added successfully!');
                        modal.hide();
                        form[0].reset();

                        // 🔁 Refresh unified table
                        if ($.fn.DataTable.isDataTable('#companiesTable')) {
                            $('#companiesTable').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to add company');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
