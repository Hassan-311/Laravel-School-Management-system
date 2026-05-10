@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Students</h2>
    <button class="btn btn-primary" id="addBtn">+ Add Student</button>
</div>

<table class="table table-bordered" id="studentsTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

{{-- Modal --}}
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="student_id">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Student Name">
                    <span class="text-danger" id="nameError"></span>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="email" class="form-control" placeholder="Email address">
                    <span class="text-danger" id="emailError"></span>
                </div>

                <div class="mb-3">
                    <label>Phone (Optional)</label>
                    <input type="text" id="phone" class="form-control" placeholder="Phone number">
                </div>

                <div class="mb-3">
                    <label>Roll No</label>
                    <input type="text" id="roll_no" class="form-control" placeholder="Roll number">
                    <span class="text-danger" id="rollError"></span>
                </div>

                <div class="mb-3">
                    <label>Class</label>
                    <select id="class_id" class="form-control">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="classError"></span>
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

    let modal = new bootstrap.Modal(document.getElementById('studentModal'));

    //Table Init
    let table = $('#studentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("students.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name'},
            { data: 'email', name: 'email'},
            { data: 'phone', name: 'phone', defaultContent: '-'},
            { data: 'roll_no', name: 'roll_no'},
            { data: 'class_name', name: 'class_name', orderable: false, searchable: true},
            { data: 'action', name: 'action', orderable: false, searchabale: false}
        ]
    });

    function loadStudents(){
        table.ajax.reload();
    }

    //Data Load
    function loadStudents(){
        $.get('{{ route("students.data") }}', function(data){
            console.log(data);
            table.clear().rows.add(data).draw(false);
        });
    }
    loadStudents();
    //Add button
    $('#addBtn').click(function(){
        $('#modalTitle').text('Add Student');
        $('#student_id').val('');
        $('#name').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#roll_no').val('');
        $('#class_id').val('');
        $('#nameError').text('');
        $('#emailError').text('');
        $('#rollError').text('');
        $('#classError').text('');
        modal.show();
    });

    //save button
    $('#saveBtn').click(function(){
        let id = $('#student_id').val();
        let name = $('#name').val();
        let  email = $('#email').val();
        let phone = $('#phone').val();
        let  roll_no = $('#roll_no').val();
        let  class_id = $('#class_id').val();

        let url = id ? '/students/' + id : '/students/';

        $('#nameError').text('');
        $('#emailError').text('');
        $('#rollError').text('');
        $('#classError').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: { name, email, phone, roll_no, class_id, _method: id ? 'PUT' : 'POST'},
            success: function(res){
                if(res.success){
                    modal.hide();
                    loadStudents();
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
                    if(errors.name) $('#nameError').text(errors.name[0]);
                    if(errors.email) $('#emailError').text(errors.email[0]);
                    if(errors.roll_no) $('#rollError').text(errors.roll_no[0]);
                    if(errprs.class_id) $('#classError').text(errors.class_id[0]);
                }
            }
        });
    });

    //Edit Button
    $(document).on('click', '.editBtn' , function(){
        let id = $(this).data('id');

        $.get('/students/' + id + '/edit', function(data){
            $('#modalTitle').text('Edit Student');
            $('#student_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#roll_no').val(data.roll_no);
            $('#class_id').val(data.class_id);
            $('#nameError').text('');
            $('#emailError').text('');
            $('#rollError').text('');
            $('#classError').text('');
            modal.show();
        });
    });

    //Delete Button
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');

        Swal.fire({
            title: 'Do You Want To Delete it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '/students/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE'},
                    success: function(res){
                        if(res.success){
                            loadStudents();
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
