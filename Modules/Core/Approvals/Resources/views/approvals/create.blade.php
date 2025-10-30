@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="mb-0">Create New Approval Request</h4>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('approvals.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required placeholder="Approval title">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Module</label>
                                <input type="text" name="module" class="form-control" required placeholder="e.g. HR, Purchases" value="general">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Record ID</label>
                                <input type="number" name="record_id" class="form-control" required value="0">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Approval Steps</h5>
                        <p class="text-muted small">Define approvers in sequence (step 1 → step 2 → ...)</p>

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="steps-table">
                                <thead>
                                <tr>
                                    <th style="width: 80px;">Order</th>
                                    <th>User ID (optional)</th>
                                    <th style="width: 100px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="number" class="form-control step-order" value="1" disabled></td>
                                    <td><input type="number" class="form-control" name="steps[0][user_id]" placeholder="e.g. 5"></td>
                                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-step" disabled>Remove</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" id="add-step" class="btn btn-outline-primary btn-sm mt-2">Add Step</button>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success">Create</button>
                            <a href="{{ route('approvals.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.querySelector('#steps-table tbody');
            const addBtn = document.getElementById('add-step');

            function renumber() {
                [...tbody.querySelectorAll('tr')].forEach((tr, idx) => {
                    tr.querySelector('.step-order').value = idx + 1;
                    const input = tr.querySelector('input[name^="steps"]');
                    input.name = `steps[${idx}][user_id]`;
                    tr.querySelector('.remove-step').disabled = (tbody.querySelectorAll('tr').length === 1);
                });
            }

            addBtn.addEventListener('click', () => {
                const idx = tbody.querySelectorAll('tr').length;
                const tr = document.createElement('tr');
                tr.innerHTML = `
      <td><input type="number" class="form-control step-order" value="${idx+1}" disabled></td>
      <td><input type="number" class="form-control" name="steps[${idx}][user_id]" placeholder="e.g. 7"></td>
      <td><button type="button" class="btn btn-outline-danger btn-sm remove-step">Remove</button></td>
    `;
                tbody.appendChild(tr);
                renumber();
            });

            tbody.addEventListener('click', e => {
                if (e.target.classList.contains('remove-step')) {
                    e.target.closest('tr').remove();
                    renumber();
                }
            });
        });
    </script>
@endsection
