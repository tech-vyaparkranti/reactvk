@extends('layouts.dashboardLayout')
@section('title', 'Applied Position Data')
@section('content')

    <x-dashboard-container container_header="Manage Applied Position Data">
        <x-card>
            <x-card-header>Applied Position Data</x-card-header>
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
                    url: "{{ route('appliedDataTable') }}",
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
                        data: 'id',
                        name: 'id',
                         title: "ID",
                    },
                    {
                        data: 'name',
                        name: 'name',
                       title: "Name",
                    }, 
                    {
                        data: 'email',
                        name: 'email',
                       title: "Email",
                    },  
                    {
                        data: 'phone',
                        name: 'phone',
                       title: "Phone Number",
                    }, 
                    {
                        data: 'department',
                        name: 'department',
                       title: "Department",
                    },                     
                    {
                        data: 'position_analytics',
                        name: 'position_analytics',
                        title: "Position Analytics",
                    },
                    {
                        data: 'cover_letter',
                        name: 'cover_letter',
                        title: "Cover Letter",
                    },
                    {
                        data: 'resume',
                        name: 'resume',
                        title: "Resume",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, meta) {
                            if (data) {
                                return `<a href="${data}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> View Resume
                                </a>`;
                            } else {
                                return 'No Resume';
                            }
                        }
                    }
                ]
            });

        });
         
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
