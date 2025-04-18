@extends('layouts.dashboardLayout')
@section('title', 'Manage Technology')
@section('content')

    <x-dashboard-container container_header="Manage Technology">
        <x-card>
            <x-card-header>Add Technology Items</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element name="image" id="local_image" type="file" label="Upload Technology Icon"
                        placeholder="Technology Icon" accept="image/*" multiple></x-input-with-label-element>
                    <x-input-with-label-element div_class="hidden col-md-4 col-sm-12 mb-3" div_id="old_image_div"
                        type="image" name="old_image" id="old_image" placeholder="Old Icon"
                        label="Old Icon"></x-input-with-label-element>
                    <x-input-with-label-element name="tech_name" id="tech_name" type="text" label="Technology Name"
                        placeholder="Technology Name"></x-input-with-label-element>
                    <x-select-label-group required name="category" id="filter_category" label_text="Technology Category">
                        <option value="Frontend">Frontend</option>
                        <option value="Backend">Backend</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Cloud & Dev Ops">Cloud & Dev Ops</option>
                        <option value="Design">Design</option>
                        <option value="Ai & Data Science">Ai & Data Science</option>
                    </x-select-label-group>
                    <x-text-area-with-label id="content" label="Technology Short Description" name="description"
                    required></x-text-area-with-label>

                    <x-input-with-label-element type="number" id="position" name="sorting" placeholder="Sorting"
                        label="Sorting Number"></x-input-with-label-element>

                    <x-select-label-group required name="status" id="view_status" label_text="Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Technology Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
    $('#content').summernote({
            placeholder: 'ElementText',
            tabsize: 2,
            height: 100
        });
        let site_url = '{{ url('/') }}';
        var table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getTechnology') }}",
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
                        data: '{{ \App\Models\Technology::IMAGE }}',
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
                        title: "Image Local"
                    },
                    {
                        data: '{{ \App\Models\Technology::TECH_NAME }}',
                        name: '{{ \App\Models\Technology::TECH_NAME }}',
                        title: "Technology Name"
                    },
                    {
                        data: '{{ \App\Models\Technology::CATEGORY }}',
                        name: '{{ \App\Models\Technology::CATEGORY }}',
                        title: "Category"
                    },
                    {
                        data: '{{ \App\Models\Technology::DESCRIPTION }}',
                        name: '{{ \App\Models\Technology::DESCRIPTION }}',
                        title: "Technology Short Description"
                    },
                    {
                        data: '{{ \App\Models\Technology::POSITION }}',
                        name: '{{ \App\Models\Technology::POSITION }}',
                        title: "Position"
                    },
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#filter_category").val(row['category']);
                $("#position").val(row['sorting']);
                $("#tech_name").val(row['tech_name']);
                $("#view_status").val(row['status']);
                $("#content").val(row['description']);
                $('#content').summernote('destroy');
                $('#content').summernote({
                    focus: true
                });
                $("#action").val("update");
                $("#old_image").prop('src', row['image']);
                $("#old_image_div").removeClass("hidden");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveTechnology') }}',
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
                            url: '{{ route('saveTechnology') }}',
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
