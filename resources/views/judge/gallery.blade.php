@extends('layout.main') @section('content')
    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if($errors->has('image'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('image') }}</div>
    @endif
    @if($errors->has('email'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('email') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section>
{{--        @if(in_array("users-add", $all_permission))--}}
            <div class="container-fluid">
                <a href="{{route('musician.upload', $lims_employee_data->id)}}" class="btn btn-info"><i class="dripicons-plus"></i> Upload File</a>
            </div>
{{--        @endif--}}
        <div class="">
            <div class="row m-5">
                @foreach($lims_employee_gallery as $employee_gallery)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                @if($employee_gallery->type == 'image')
                                    <img src="{{asset('images/employee/'.$employee_gallery->file)}}" class="img-fluid">
                                @elseif($employee_gallery->type == 'video')
                                    <video width="320" height="240" controls>
                                        <source src="{{asset('images/employee/'.$employee_gallery->file)}}" type="video/mp4">
                                        <source src="{{asset('images/employee/'.$employee_gallery->file)}}" type="video/ogg">
                                        Your browser does not support the video tag.
                                        @elseif($employee_gallery->type == 'audio')
                                            <audio controls>
                                                <source src="{{asset('public/employee/data/'.$employee_gallery->file)}}" type="audio/ogg">
                                                <source src="{{asset('public/employee/data/'.$employee_gallery->file)}}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                                @elseif($employee_gallery->type == 'link')
                                                    <iframe src="{{$employee_gallery->file}}" width="400" height="290"></iframe>
                                                @elseif($employee_gallery->type == 'short')
                                                    <iframe src="{{$employee_gallery->file}}" width="320" height="240"></iframe>
                                                @else
                                                    <p>File not supported</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <script type="text/javascript">

        $("ul#people").siblings('a').attr('aria-expanded','true');
        $("ul#people").addClass("show");
        $("ul#people #employee-menu").addClass("active");

        var employee_id = [];
        var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        $(document).on('click', '.edit-btn', function() {
            $("#editModal input[name='employee_id']").val( $(this).data('id') );
            $("#editModal input[name='name']").val( $(this).data('name') );
            $("#editModal select[name='department_id']").val( $(this).data('department_id') );
            $("#editModal input[name='email']").val( $(this).data('email') );
            $("#editModal input[name='phone_number']").val( $(this).data('phone_number') );
            $("#editModal input[name='address']").val( $(this).data('address') );
            $("#editModal input[name='city']").val( $(this).data('city') );
            $("#editModal input[name='country']").val( $(this).data('country') );
            $('.selectpicker').selectpicker('refresh');
        });

        $('#employee-table').DataTable( {
            "order": [],
            'language': {
                'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
                "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
                "search":  '{{trans("file.Search")}}',
                'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
                }
            },
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': [0, 1, 6]
                },
                {
                    'render': function(data, type, row, meta){
                        if(type === 'display'){
                            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                        }

                        return data;
                    },
                    'checkboxes': {
                        'selectRow': true,
                        'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                    },
                    'targets': [0]
                }
            ],
            'select': { style: 'multi',  selector: 'td:first-child'},
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"row"lfB>rtip',
            buttons: [
                {
                    extend: 'pdf',
                    text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        stripHtml: false
                    },
                    customize: function(doc) {
                        for (var i = 1; i < doc.content[1].table.body.length; i++) {
                            if (doc.content[1].table.body[i][0].text.indexOf('<img src=') !== -1) {
                                var imagehtml = doc.content[1].table.body[i][0].text;
                                var regex = /<img.*?src=['"](.*?)['"]/;
                                var src = regex.exec(imagehtml)[1];
                                var tempImage = new Image();
                                tempImage.src = src;
                                var canvas = document.createElement("canvas");
                                canvas.width = tempImage.width;
                                canvas.height = tempImage.height;
                                var ctx = canvas.getContext("2d");
                                ctx.drawImage(tempImage, 0, 0);
                                var imagedata = canvas.toDataURL("image/png");
                                delete doc.content[1].table.body[i][0].text;
                                doc.content[1].table.body[i][0].image = imagedata;
                                doc.content[1].table.body[i][0].fit = [30, 30];
                            }
                        }
                    },
                },
                {
                    extend: 'csv',
                    text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        format: {
                            body: function ( data, row, column, node ) {
                                if (column === 0 && (data.indexOf('<img src=') != -1)) {
                                    var regex = /<img.*?src=['"](.*?)['"]/;
                                    data = regex.exec(data)[1];
                                }
                                return data;
                            }
                        }
                    },
                },
                {
                    extend: 'print',
                    text: '<i title="print" class="fa fa-print"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        stripHtml: false
                    },
                },
                {
                    text: '<i title="delete" class="dripicons-cross"></i>',
                    className: 'buttons-delete',
                    action: function ( e, dt, node, config ) {
                        if(user_verified == '1') {
                            employee_id.length = 0;
                            $(':checkbox:checked').each(function(i){
                                if(i){
                                    employee_id[i-1] = $(this).closest('tr').data('id');
                                }
                            });
                            if(employee_id.length && confirm("Are you sure want to delete?")) {
                                $.ajax({
                                    type:'POST',
                                    url:'musician/deletebyselection',
                                    data:{
                                        employeeIdArray: employee_id
                                    },
                                    success:function(data){
                                        alert(data);
                                    }
                                });
                                dt.rows({ page: 'current', selected: true }).remove().draw(false);
                            }
                            else if(!employee_id.length)
                                alert('No employee is selected!');
                        }
                        else
                            alert('This feature is disable for demo!');
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i title="column visibility" class="fa fa-eye"></i>',
                    columns: ':gt(0)'
                },
            ],
        } );
    </script>
@endsection
