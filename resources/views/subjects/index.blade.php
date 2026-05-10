@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Subjects</h2>
    <button class="btn btn-primary" id="addBtn">+ Add Subject</button>
</div>

<table class="table table-bordered" id="subjectsTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Subject Name</th>
            <th>Code</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

{{-- Modal --}}
<div class="modal fade" id="subjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="subject_id">

                <div class="mb-3">
                    <label>Subject Name</label>
                    <input type="text" id="name" class="form-control" placeholder="e.g. Mathematics">
                    <span class="text-danger" id="nameError"></span>
                </div>

                <div class="mb-3">
                    <label>Subject Code (Optional)</label>
                    <input type="text" id="code" class="form-control" placeholder="e.g. MTH-101">
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

    let modal = new bootstrap.Modal(document.getElementById('subjectModal'));

    //Table Init
    let table = $('#subjectsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("subjects.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name'},
            { data: 'code', name: 'code', defaultContent: '-'},
            { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    function loadSubjects(){
        table.ajax.reload();
    }

    //Data Load
    function loadSubjects(){
        $.get('{{ route("subjects.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
    loadSubjects();

    //Add Button
    $('#addBtn').click(function(){
        $('#modalTitle').text('Add Subject');
        $('#subject_id').val('');
        $('#name').val('');
        $('#code').val('');
        $('#nameError').text('');
        modal.show();
    });

    //Save Button Add + Edit
    $('#saveBtn').click(function(){
        let id = $('#subject_id').val();
        let name = $('#name').val();
        let code = $('#code').val();

        let url = id ? '/subjects/' + id : '/subjects/';

        $('#nameError').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: { 
                name: name,
                code: code,
                _method: id ? 'PUT' : 'POST'
            },
            success: function(res){
                if(res.success){
                    modal.hide();
                    loadSubjects();
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
                    if(errors.name){
                        $('#nameError').text(errors.name[0]);
                    }
                }
            }
        });
    });

    //Edit Button
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');

        $.get('/subjects/' + id + '/edit', function(data){
            $('#modalTitle').text('Edit Subject');
            $('#subject_id').val(data.id);
            $('#name').val(data.name);
            $('#code').val(data.code);
            $('#nameError').text('');
            modal.show();
        });
    });

    //DeleteButton
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');

        Swal.fire({
            title: 'Do you want to Delete it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '/subjects/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    success: function(res){
                        if(res.success){
                            loadSubjects();

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },error: function(err){
                        console.log('Error:', err);
                    }
                });
            }
        });
    });

</script>
    
@endpush
