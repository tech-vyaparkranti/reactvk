@extends('layouts.dashboardLayout')
@section('title', 'Manage Gallery')
@section('content')

    <x-dashboard-container container_header="Manage Gallery">
        <x-card>
            <x-card-header>Add Gallery Items</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element name="icon" id="local_image" type="file" label="Upload Icon"
                        placeholder="Images" accept="image/*" ></x-input-with-label-element>
                    <x-input-with-label-element div_class="hidden col-md-4 col-sm-12 mb-3" div_id="old_image_div"
                        type="image" name="old_image" id="old_image" placeholder="Old Icon"
                        label="Old Icon"></x-input-with-label-element>
                    <x-input-with-label-element name="title" id="title" type="text" label="Title"
                        placeholder="Title"></x-input-with-label-element>
                    <x-text-area-with-label id="content" label="Short Description" name="short_description"
                        required></x-text-area-with-label>

                    <x-select-label-group required name="status" id="view_status" label_text="View Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Gallery Data</x-card-header>
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
                    url: "{{ route('getChooseNowCard') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                "scrollX": true,
                "order": [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "Action"
                    },
                    
                    {
                        data: 'icon',
                        render: function(data, type) {
                            let image = '';
                            if (data) {
                                image += '<img alt="Stored Image" src="' + data +
                                    '" class="img-thumbnail">';
                            }
                            return image;
                        },
                        orderable: false,
                        searchable: false,
                        title: "Icon"
                    },
                    {
                        data: 'title',
                        name: 'title',
                        title: "Title"
                    },
                    {
                        data: 'description',
                        name: 'description',
                        title: "Description"
                    },
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#description").val(row['description']);
                $("#title").val(row['title']);
                $("#view_status").val(row['status']);
                $("#action").val("update");
                $("#old_image").prop('src', row['icon']);
                $("#old_image_div").removeClass("hidden");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveChooseNowCard') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, true);
                            table.ajax.reload();
                        } else {
                            errorMessage(response.message);
                        }
                    },
                    error: function(xhr) {
                                if (xhr.status === 422) {
                                    const errors = xhr.responseJSON.errors;
                                    const allMessages = Object.values(errors).flat().join('<br>');
                                    errorMessage(allMessages);
                                } else {
                                    errorMessage("Something went wrong");
                                }
                            },
                    failure: function(response) {
                        errorMessage(response.message);
                    }
                });
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
                            url: '{{ route('saveChooseNowCard') }}',
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
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    const errors = xhr.responseJSON.errors;
                                    const allMessages = Object.values(errors).flat().join('<br>');
                                    errorMessage(allMessages);
                                } else {
                                    errorMessage("Something went wrong");
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
