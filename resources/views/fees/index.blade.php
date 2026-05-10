@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Fee Management</h2>
    <button class="btn btn-primary" id="addBtn">+ Add Fee</button>
</div>

<table class="table table-bordered" id="feesTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Student</th>
            <th>Amount</th>
            <th>Month</th>
            <th>Year</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

{{-- Modal --}}
<div class="modal fade" id="feeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="fee_id">

                <div class="mb-3">
                    <label>Student</label>
                    <select id="student_id" class="form-control">
                        <option value="">-- Select Student --</option>
                    </select>
                    <span class="text-danger" id="studentError"></span>
                </div>

                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" id="amount" class="form-control" placeholder="e.g. 5000">
                    <span class="text-danger" id="amountError"></span>
                </div>

                <div class="mb-3">
                    <label>Month</label>
                    <select id="month" class="form-control">
                        <option value="">-- Select Month --</option>
                        <option>January</option>
                        <option>February</option>
                        <option>March</option>
                        <option>April</option>
                        <option>May</option>
                        <option>June</option>
                        <option>July</option>
                        <option>August</option>
                        <option>September</option>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>
                    <span class="text-danger" id="monthError"></span>
                </div>

                <div class="mb-3">
                    <label>Year</label>
                    <input type="number" id="year" class="form-control" value="2026">
                    <span class="text-danger" id="yearError"></span>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select id="status" class="form-control">
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });

    let modal = new bootstrap.Modal(document.getElementById('feeModal'));

    //Table init
    let table = $('#feesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("fees.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'student_name', name: 'student_name', orderable: true, searchable: true},
            { data: 'amount', name: 'amount'},
            { data: 'month', name: 'month'},
            { data: 'year', name: 'year'},
            { data: 'status', name: 'status', orderable: false, searchable: false},
            { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    function loadFees(){
        table.ajax.reload();
    }

    //Students Dropdown Load
    function loadStudents(){
        $.get('{{ route("students.data") }}', function(data){
            let options = '<option value="">-- Select Student --</option>';
            data.data.forEach(function(student){
                options += `<option value="${student.id}">${student.name}</option>`;
            });
            $('#student_id').html(options);
        });
    }

    // Fees Load
    function loadFees(){
        $.get('{{ route("fees.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
    loadFees();

    //Add button
    $('#addBtn').click(function(){
        $('#modalTitle').text('Add Fee');
        $('#fee_id').val('');
        $('#amount').val('');
        $('#month').val('');
        $('#year').val('2026');
        $('#status').val('unpaid');
        $('#studentError, #amountError, #monthError, #yearError').text('');
        loadStudents();
        modal.show();
    });

    // Save button
    $('#saveBtn').click(function(){
        let id         = $('#fee_id').val();
        let student_id = $('#student_id').val();
        let amount     = $('#amount').val();
        let month      = $('#month').val();
        let year       = $('#year').val();
        let status     = $('#status').val();
        
        let url = id ? '{{ url("fees") }}/' + id : '{{ url("fees") }}';

        

        $('#studentError, #amountError, #monthError, #yearError').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: { student_id, amount, month, year, status, _method: id ? 'PUT' : 'POST'},
            success: function(res){
                if(res.success){
                    modal.hide();
                    loadFees();
                    Swal.fire({
                        icon: 'success',
                        title: 'Done!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(err){
                if(err.status === 422){
                    let errors = err.responseJSON.errors;
                    if(errors.student_id) $('#studentError').text(errors.student_id[0]);
                    if(errors.amount) $('#amountError').text(errors.amount[0]);
                    if(errors.month) $('#monthError').text(errors.month[0]);
                    if(errors.year) $('#yearError').text(errors.year[0]);
                }
            }
        });
    });
    //Edit Button 
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');

        loadStudents();

        $.get('/fees/' + id + '/edit', function(data){
            $('#modalTitle').text('Edit Fee');
            $('#fee_id').val(data.id);
            $('#student_id').val(data.student_id);
            $('#amount').val(data.amount);
            $('#month').val(data.month);
            $('#year').val(data.year);
            $('#status').val(data.status);
            modal.show();
        });
    });

    // Delete Button 
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id')

        Swal.fire({
            title: 'Do you want to delete it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '/fees/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE'},
                    success: function(res){
                        if(res.success){
                            loadFees();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });
</script>
    
@endpush