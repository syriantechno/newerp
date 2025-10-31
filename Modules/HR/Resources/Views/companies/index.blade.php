@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Companies</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                    <i class="fas fa-plus"></i> Add Company
                </button>
            </div>

            <div class="card-body">
                <table id="companiesTable" class="table table-striped w-100">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Trade License</th>
                        <th>VAT Number</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- ✅ Add Company Modal --}}
    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Add Company</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    {{-- ✅ Load only once (Soft-UI already includes jQuery) --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

    <script>
        $(document).ready(function () {
            console.log("🧩 [HR] Companies module initialized");

            console.log("jQuery version:", $.fn.jquery);
            console.log("DataTables loaded:", typeof $.fn.DataTable);

            // ✅ Initialize DataTable safely
            // ✅ Initialize DataTable safely
            const table = $('#companiesTable').DataTable({
                ajax: {
                    url: "{{ route('hr.companies.table') }}",
                    dataSrc: function (json) {
                        console.log("📦 [HR] DataTable response:", json);
                        // تأكد أن البيانات موجودة ضمن data، وإلا أعد مصفوفة فاضية
                        return json.data || [];
                    },
                    error: function (xhr, error, thrown) {
                        console.error("❌ [HR] DataTable AJAX error:", xhr.status, xhr.responseText);
                        toastr.error('Failed to load company list.');
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'trade_license', defaultContent: '-' },
                    { data: 'vat_number', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: function (row) {
                            return `
                    <button class="btn btn-sm btn-danger deleteCompany" data-id="${row.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                        }
                    }
                ],
                responsive: true,
                pageLength: 10,
                language: {
                    emptyTable: "No companies found.",
                    loadingRecords: "Loading...",
                    search: "Filter:"
                },
                initComplete: function(settings, json) {
                    console.log("✅ [HR] DataTable initialized successfully.");
                }
            });


            // ✅ Handle add form
            $('#addCompanyForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('hr.companies.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        toastr.success('Company added successfully!');
                        $('#addCompanyModal').modal('hide');
                        $('#addCompanyForm')[0].reset();
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        toastr.error('Failed to add company');
                        console.error(xhr.responseText);
                    }
                });
            });

            // ✅ Handle delete
            $(document).on('click', '.deleteCompany', function() {
                const id = $(this).data('id');
                if (!confirm('Delete this company?')) return;
                $.ajax({
                    url: `/hr/companies/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        toastr.info('Company deleted');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        toastr.error('Delete failed');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
