@extends('layouts.user_type.auth')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-dark fw-bold mb-0">🏢 HR Companies</h4>
            <button class="btn btn-primary" id="addCompanyBtn">
                <i class="fas fa-plus me-2"></i> Add Company
            </button>
        </div>

        <table id="companiesTable" class="table table-striped table-bordered w-100">
            <thead class="bg-light">
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

    {{-- ✅ Modal for Adding Company --}}
    <div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="companyForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trade License</label>
                            <input type="text" name="trade_license" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Number</label>
                            <input type="text" name="vat_number" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- ✅ Include needed JS libs inside page (like Employees page) --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

        <script>
            $(document).ready(function() {
                console.log("🧩 [HR] Companies module initialized");

                // Initialize table
                const table = $('#companiesTable').DataTable({
                    ajax: "{{ route('hr.companies.table') }}",
                    columns: [
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'trade_license', defaultContent: '-' },
                        { data: 'vat_number', defaultContent: '-' },
                        {
                            data: null,
                            render: function(row) {
                                return `
                        <button class="btn btn-sm btn-danger deleteCompany" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                            }
                        }
                    ]
                });

                // Open modal
                $('#addCompanyBtn').on('click', function() {
                    $('#companyForm')[0].reset();
                    $('#companyModal').modal('show');
                });

                // Save company via AJAX
                $('#companyForm').on('submit', function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('hr.companies.store') }}",
                        method: "POST",
                        data: formData,
                        success: function(res) {
                            toastr.success(res.message);
                            $('#companyModal').modal('hide');
                            table.ajax.reload();
                            console.log('✅ [HR] Company added:', res.data);
                        },
                        error: function(xhr) {
                            toastr.error('Error saving company');
                            console.error('❌ [HR] Save error:', xhr.responseText);
                        }
                    });
                });

                // Delete company
                $(document).on('click', '.deleteCompany', function() {
                    const id = $(this).data('id');
                    if (!confirm('Are you sure you want to delete this company?')) return;

                    $.ajax({
                        url: `/hr/companies/${id}`,
                        method: "DELETE",
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            toastr.success(res.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            toastr.error('Delete failed');
                            console.error('❌ [HR] Delete error:', xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
