@extends('layouts.dashboardLayout')
@section('title', 'Review Data')
@section('content')

    <x-dashboard-container container_header="Manage Review Data">
        <x-card>
            <x-card-header>Review Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
        let site_url = '{{ url('/') }}';
        var table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('reviewDataTable') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    [0, 'desc']
                ],
                columns: [ 
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "Action"
                    },
                    {
                        data: 'first_name',
                        name: 'first_name',
                       title: "First Name",
                    },
                    {
                        data: 'last_name',
                        name: 'last_name',
                       title: "Last Name",
                    },  
                    {
                        data: 'phone',
                        name: 'phone',
                       title: "Phone Number",
                    }, 
                    {
                        data: 'comments',
                        name: 'comments',
                       title: "Comments",
                    },                     
                    
                    {
                        data: 'review',
                        name: 'review',
                        title: "Given Review",
                    },
                    {
                        data: 'blog_id',
                        name: 'blog_id',
                        title: "Blog ID",
                    }
                ]
            });

        });
         
        function Disable(id) {
            changeAction(id, "disable", "This item will be disabled!", "Yes, disable it!");
        }

        function Enable(id) {
            changeAction(id, "enable", "This item will be enabled!", "Yes, enable it!");
        }

        function changeAction(id, action, text, confirmButtonText) {
            if (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('changeReview') }}',
                            data: {
                                id: id,
                                action: action,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status) {
                                    successMessage(response.message, true);
                                    table.ajax.reload();
                                } else {
                                    errorMessage(response.message);
                                }
                            },
                            
                            failure: function(response) {
                                errorMessage(response.message);
                            }
                        });
                    }
                });
            } else {
                errorMessage("Something went wrong. Code 102");
            }
        }

    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
