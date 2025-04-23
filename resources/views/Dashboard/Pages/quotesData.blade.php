@extends('layouts.dashboardLayout')
@section('title', 'Quotes Data')
@section('content')

    <x-dashboard-container container_header="Manage Quotes Data">
        <x-card>
            <x-card-header>SEO Data</x-card-header>
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
                    url: "{{ route('quotesDataTable') }}",
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
                        data: 'phone',
                        name: 'phone',
                       title: "Phone Number",
                    }, 
                    {
                        data: 'location',
                        name: 'location',
                       title: "Location",
                    },                     
                   
                    {
                        data: 'message',
                        name: 'message',
                        title: "Message",
                        width: "20%"
                    }
                ]
            });

        });
         
         
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
