@extends('layouts.app')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Classes</h2>
    <button class="btn btn-primary" id="addClassBtn">+ Add Class</button>
</div>

<table class="table table-bordered" id="classesTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Class Name</th>
            <th>Section</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

{{-- Modal --}}
<div class="modal fade" id="classModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="class_id">

                <div class="mb-3">
                    <label>Class Name</label>
                    <input type="text" id="name" class="form-control" placeholder="e.g. Class 1">
                    <span class="text-danger" id="nameError"></span>
                </div>

                <div class="mb-3">
                    <label>Section (Optional)</label>
                    <input type="text" id="section" class="form-control" placeholder="e.g. A">
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
    //CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    let modal = new bootstrap.Modal(document.getElementById('classModal'));

    //Table Init

    let table = $('#classesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("classes.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name' },
            { data: 'section', name: 'section', defaultContent: '-'},
            { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    function loadClasses(){
        table.ajax.reload();
    }

    // Add Button Click
    $('#addClassBtn').click(function(){
        $('#modalTitle').text('Add Class');
        $('#class_id').val('');
        $('#name').val('');
        $('#section').val('');
        $('#nameError').text('');
        modal.show();
    });

    // Save Button Add + Edit

    $('#saveBtn').click(function(){

        let id = $('#class_id').val();
        let name = $('#name').val();
        let section = $('#section').val();

        let url = id ? '/classes/' + id : '/classes/';

        $('#nameError').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: { name: name,
                    section: section,
                    _method: id ? 'PUT' : 'POST'
                 },
            success: function(res) {
                if(res.success){
                    modal.hide();
                    loadClasses();
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
                console.log(err);
                if(err.status === 422){
                    let errors = err.responseJSON.errors;
                    if(errors.name){
                        $('#nameError').text(errors.name[0]);
                    }
                }
            }

        });
    });

    //Edit Button Click

    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');

        $.get('/classes/' + id + '/edit', function(data){
            console.log(data);
            
            $('#modalTitle').text('Edit Class');
            $('#class_id').val(data.id);
            $('#name').val(data.name);
            $('#section').val(data.section);
            $('#nameError').text('');
            modal.show();
        });
    });

    //Delete Button Click

    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');
        console.log('Delete ID:', id)

        Swal.fire({
            title: 'Do you want to Delete it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            console.log('Result:', result);
            if(result.isConfirmed){
                console.log('confirmed!');
                
                $.ajax({
                    url: '/classes/' + id,
                    type: 'POST',
                    data:{
                        _method: 'DELETE'

                    },
                    success: function(res){
                        console.log('Response:', res)
                        if(res.success){
                            loadClasses();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(err){
                        concole.log('Error:', err);
                    }
                });
            }
        });
    });

</script>
    
@endpush
