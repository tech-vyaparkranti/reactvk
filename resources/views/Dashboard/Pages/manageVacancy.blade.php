@extends('layouts.dashboardLayout')
@section('title', 'Manage Vacancy')
@section('content')

    <x-dashboard-container container_header="Manage Vacancy">
        <x-card>
            <x-card-header>Add Vacancy Items</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element name="title" id="title" type="text" label="Vacancy Title"
                        placeholder="Vacancy Title"></x-input-with-label-element>
                    <x-input-with-label-element name="department" id="department" type="text" label="Department"
                        placeholder="Department"></x-input-with-label-element>
                    <x-input-with-label-element name="location" id="location" type="text" label="Location"
                        placeholder="Location"></x-input-with-label-element>
                    <x-select-label-group required name="job_type" id="job_type" label_text="Job Type">
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Internship">Internship</option>
                    </x-select-label-group>
                    <x-text-area-with-label id="content" label="Vacancy Description" name="description"
                    required></x-text-area-with-label>
                    <x-text-area-with-label id="requirement" label="Requirement" name="requirement"
                        required></x-text-area-with-label>
                    <x-text-area-with-label id="benefits" label="Benefits" name="benefits"
                        required></x-text-area-with-label>

                    <x-select-label-group required name="status" id="view_status" label_text="Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>Vacancy Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
        $('#content').summernote({
            placeholder: 'Vacancy Description',
            tabsize: 2,
            height: 100
        });
        $('#requirement').summernote({
            placeholder: 'Requirement',
            tabsize: 2,
            height: 100
        });
        $('#benefits').summernote({
            placeholder: 'Benefits',
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
                    url: "{{ route('getVacancy') }}",
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
                        data: '{{ \App\Models\Vacancy::TITLE }}',
                        name: '{{ \App\Models\Vacancy::TITLE }}',
                        title: "Vacancy TITLE"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::DEPARTMENT }}',
                        name: '{{ \App\Models\Vacancy::DEPARTMENT }}',
                        title: "DEPARTMENT"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::LOCATION }}',
                        name: '{{ \App\Models\Vacancy::LOCATION }}',
                        title: "Location"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::JOB_TYPE }}',
                        name: '{{ \App\Models\Vacancy::JOB_TYPE }}',
                        title: "Job Type"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::DESCRIPTION }}',
                        name: '{{ \App\Models\Vacancy::DESCRIPTION }}',
                        title: "Vacancy Description"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::REQUIREMENT }}',
                        name: '{{ \App\Models\Vacancy::REQUIREMENT }}',
                        title: "Requirement"
                    },
                    {
                        data: '{{ \App\Models\Vacancy::BENEFITS }}',
                        name: '{{ \App\Models\Vacancy::BENEFITS }}',
                        title: "Benefits"
                    },
                   
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#job_type").val(row['job_type']);
                $("#department").val(row['department']);
                $("#location").val(row['location']);

                $("#view_status").val(row['status']);
                $("#content").val(row['description']);
                $('#content').summernote('destroy');
                $('#content').summernote({
                    focus: true
                });

                $("#requirement").val(row['requirement']);
                $('#requirement').summernote('destroy');
                $('#requirement').summernote({
                    focus: true
                });
                $("#benefits").val(row['benefits']);
                $('#benefits').summernote('destroy');
                $('#benefits').summernote({
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
                    url: '{{ route('saveVacancy') }}',
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
                            url: '{{ route('saveVacancy') }}',
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
