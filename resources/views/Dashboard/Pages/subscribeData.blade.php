@extends('layouts.dashboardLayout')
@section('title', 'Subscribers Data')
@section('content')

    <x-dashboard-container container_header="Manage Subscribers Data">
        <x-card>
            <x-card-header>Subscribers Data</x-card-header>
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
                    url: "{{ route('SubscribeDataTable') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    // [1, 'desc']
                    [0, 'desc']
                ],
                columns: [ 
                    {
                        data: 'id',
                        name: 'id',
                         title: "ID",
                        width: "5%"
                    },
                    {
                        data: 'email',
                        name: 'email',
                       title: "Email",
                        width: "15%"
                    },                     
                   
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                        title: "Ip Address",
                        width: "20%"
                    }
                ]
            });

        });
         
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
