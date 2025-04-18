@extends('layouts.dashboardLayout')
@section('title', 'Manage Growth Journey')
@section('content')

    <x-dashboard-container container_header="Manage Growth Journey Of Our Team">
        <x-card>
            <x-card-header>Add Growth Journey Of Our Team</x-card-header>
            <x-card-body>
                <x-form>
                    <x-input type="hidden" name="id" id="id" value=""></x-input>
                    <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                    <x-input-with-label-element name="title" id="title" type="text" label="Title of Department"
                        placeholder="Title of Department"></x-input-with-label-element>
                    <x-input-with-label-element name="icon" id="image" type="file" label="Upload Images"
                        placeholder="Images" accept="image"></x-input-with-label-element>
                    
                    <x-select-label-group required name="experience_level" id="experience_level" label_text="Experience Level">
                        <option value="Entry Level">Entry Level</option>
                        <option value="Mid Level">Mid Level</option>
                        <option value="Senior Level">Senior Level</option>
                        <option value="Leadership">Leadership</option>
                    </x-select-label-group>
                    <x-text-area-with-label id="content" label="Short Description" name="short_description"
                    required></x-text-area-with-label>
                    <x-text-area-with-label id="requirement" label="Key Skills" name="skills"
                        required></x-text-area-with-label>
                    <x-input-with-label-element name="position" id="position" type="number" label="Position"
                        placeholder="Position"></x-input-with-label-element>

                    <x-select-label-group required name="status" id="view_status" label_text="Status">
                        <option value="1">Visibile</option>
                        <option value="0">Hidden</option>
                    </x-select-label-group>
                    <x-form-buttons></x-form-buttons>
                </x-form>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-header>GrowthJourney Data</x-card-header>
            <x-card-body>
                <x-data-table></x-data-table>
            </x-card-body>
        </x-card>
    </x-dashboard-container>
@endsection

@section('script')
    <script type="text/javascript">
        $('#content').summernote({
            placeholder: 'Growth Journey Description',
            tabsize: 2,
            height: 100
        });
        $('#requirement').summernote({
            placeholder: 'Skills',
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
                    url: "{{ route('getGrowth') }}",
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
                        data: '{{ \App\Models\GrowthJourney::ICON }}',
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
                        title: "Upload Image"
                    },
                    
                    {
                        data: '{{ \App\Models\GrowthJourney::TITLE }}',
                        name: '{{ \App\Models\GrowthJourney::TITLE }}',
                        title: "GrowthJourney TITLE"
                    },
                    {
                        data: '{{ \App\Models\GrowthJourney::EX_LEVEL }}',
                        name: '{{ \App\Models\GrowthJourney::EX_LEVEL }}',
                        title: "Experience Level"
                    },
                    {
                        data: '{{ \App\Models\GrowthJourney::SHORT_DESCRIPTION }}',
                        name: '{{ \App\Models\GrowthJourney::SHORT_DESCRIPTION }}',
                        title: "short Description"
                    },
                    {
                        data: '{{ \App\Models\GrowthJourney::SKILL }}',
                        name: '{{ \App\Models\GrowthJourney::SKILL }}',
                        title: "Skills"
                    },
                    {
                        data: '{{ \App\Models\GrowthJourney::POSITION }}',
                        name: '{{ \App\Models\GrowthJourney::POSITION }}',
                        title: "Sorting"
                    },
                   
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#image").attr("required", false);
                $("#experience_level").val(row['experience_level']);
                $("#position").val(row['position']);

                $("#view_status").val(row['status']);
                $("#content").val(row['short_description']);
                $('#content').summernote('destroy');
                $('#content').summernote({
                    focus: true
                });

                $("#requirement").val(row['skills']);
                $('#requirement').summernote('destroy');
                $('#requirement').summernote({
                    focus: true
                });
                $("#action").val("update");
                scrollToDiv();
            }

        });
        $(document).ready(function() {
            $("#submit_form").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('saveGrowth') }}',
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
                            url: '{{ route('saveGrowth') }}',
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
