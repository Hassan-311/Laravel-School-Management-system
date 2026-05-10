@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Teachers</h2>
    <button class="btn btn-primary" id="addBtn">+ Add Teacher</button>
</div>

<table class="table table-bordered" id="teachersTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

{{-- Modal --}}
<div class="modal fade" id="teacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="teacher_id">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Teacher's Name">
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

    let modal = new bootstrap.Modal(document.getElementById('teacherModal'))

    //Table init
    let table = $('#teachersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("teachers.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name'},
            { data: 'email', email: 'email'},
            { data: 'phone', name: 'phone', defaultContent: '-'},
            { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    function loadTeachers(){
        table.ajax.reload();
    }

    //Data Load
    function loadTeachers(){
        $.get('{{ route("teachers.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
    loadTeachers();

    //Add Button
    $('#addBtn').click(function(){
        $('#modalTitle').text('Add Teacher');
        $('#teacher_id').val('');
        $('#name').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#nameError').text('');
        $('#emailError').text('');
        modal.show();
    });
    //Save Button
    $('#saveBtn').click(function(){
        let id = $('#teacher_id').val();
        let name = $('#name').val();
        let email = $('#email').val();
        let phone = $('#phone').val();

        let url = id ? '/teachers/' + id : '/teachers/';

        $('#nameError').text('');
        $('emailError').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                name: name,
                email: email,
                phone: phone,
                _method: id ? 'PUT' : 'POST'
            },
            success: function(res){
                if(res.success){
                    modal.hide();
                    loadTeachers();

                    Swal.fire({
                        icon: 'success',
                        title: 'Done!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },error: function(err){
                if(err.status === 422){
                    let errors = err.responseJSON.errors;
                    if(errors.name) $('#nameError').text(errors.name[0]);
                    if(errors.email) $('#emailError').text(errors.email[0]);
                }
            }
        });
    });

    //Edit Button
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');

        $.get('/teachers/' + id + '/edit', function(data){
            $('#modalTitle').text('Edit Teacher');
            $('#teacher_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#nameError').text('');
            $('#emailError').text('');
            modal.show();
        });
    });

    //Delete Button
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');

        Swal.fire({
            title: 'Delete karna chahte ho?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '/teachers/' + id,
                    type: 'POST',
                    data: {_method: 'DELETE'},
                    success: function(res){
                        if(res.success){
                            loadTeachers();

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
