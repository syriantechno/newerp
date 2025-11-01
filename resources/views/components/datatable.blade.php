@props([
    'id' => 'dataTable',
    'route' => null,
    'columns' => [],
])

<div class="card">
    <div class="card-body">
        <table id="{{ $id }}" class="table align-items-center mb-0 w-100">
            <thead>
            <tr>
                @foreach($columns as $col)
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ $col }}</th>
                @endforeach
            </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableId = "#{{ $id }}";
            const ajaxUrl = "{{ $route }}";

            console.log("📡 AJAX URL:", ajaxUrl);

            // ✅ Prevent duplicate init
            window.__SoftUITableLock = window.__SoftUITableLock || {};
            if (window.__SoftUITableLock[tableId]) {
                console.warn(`⚠️ [Soft-UI Table] ${tableId} already initialized — stopping duplicate init.`);
                return;
            }
            window.__SoftUITableLock[tableId] = true;

            if (typeof $ === "undefined" || typeof $.fn.DataTable === "undefined") {
                console.error("❌ DataTables or jQuery not loaded globally!");
                return;
            }

            // ✅ Clean up old table safely
            if ($.fn.DataTable.isDataTable(tableId)) {
                try {
                    $(tableId).DataTable().clear().destroy();
                } catch (err) {
                    console.warn("⚠️ Failed to destroy old instance:", err);
                }
            }

            // ✅ Build columns dynamically
            const baseColumns = @json($columns).map(c => ({
                data: c.toLowerCase().replace(/\s+/g, '_'),
                defaultContent: '-'
            }));

            // ✅ Handle "Actions" column if exists
            if (@json($columns).slice(-1)[0].toLowerCase() === 'actions') {
                baseColumns[baseColumns.length - 1] = {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: row => `
                <div class="table-actions">
                    <i class="action-icon edit text-primary" data-id="${row.id}" title="Edit"></i>
                    <i class="action-icon delete text-danger" data-id="${row.id}" title="Delete"></i>
                </div>`
                };
            }

            // ✅ Initialize DataTable
            const table = $(tableId).DataTable({
                ajax: {
                    url: ajaxUrl,
                    type: "GET",
                    dataType: "json",
                    timeout: 10000,
                    data: function (d) {
                        // Merge DataTable params with form filters if exist
                        const form = document.querySelector('#employeeFilters');
                        if (form) {
                            const filters = Object.fromEntries(new FormData(form).entries());
                            return { ...d, ...filters };
                        }
                        return d;
                    },
                    beforeSend: function (xhr) {
                        if (window.__SoftUITableXHR && window.__SoftUITableXHR.readyState !== 4) {
                            window.__SoftUITableXHR.abort();
                            console.warn("⚠️ [Soft-UI Table] Previous request aborted before new reload");
                        }
                        window.__SoftUITableXHR = xhr;
                    },
                    dataSrc: json => json.data || [],
                    error: function (xhr, status, error) {
                        if (status === "abort") {
                            console.log("⏹️ [Soft-UI Table] Aborted previous request safely");
                            return;
                        }
                        console.error("❌ AJAX error:", status, error, xhr.responseText);
                        toastr.error('Failed to load table data.');
                    }
                },


                columns: baseColumns,
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                pagingType: "simple_numbers",
                language: {
                    paginate: { previous: "&laquo;", next: "&raquo;" },
                    search: "",
                    searchPlaceholder: "Search...",
                    emptyTable: "No records found"
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination")
                        .addClass("pagination pagination-primary justify-content-end mt-3");
                },
                initComplete: function () {
                    console.log(`✅ [Soft-UI Table] ${tableId} Ready`);
                }
            });

            // ✅ Delete Handler
            $(document).off('click', '.action-icon.delete').on('click', '.action-icon.delete', function () {
                const id = $(this).data("id");
                Swal.fire({
                    title: 'Delete this record?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `${ajaxUrl.replace('/table', '')}/${id}`,
                            method: "DELETE",
                            data: { _token: '{{ csrf_token() }}' },
                            success: function () {
                                toastr.info("Record deleted");
                                table.ajax.reload(null, false);
                            },
                            error: xhr => {
                                toastr.error("Delete failed");
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
