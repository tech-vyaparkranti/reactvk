@extends('layouts.dashboardLayout')
@section('title', 'Packages')
@section('content')

    <x-content-div heading="Manage Packages">
        <x-card-element header="Add Packages Data ">
            <x-form-element method="POST" enctype="multipart/form-data" id="submitForm" action="javascript:">
                <x-input type="hidden" name="id" id="id" value=""></x-input>
                <x-input type="hidden" name="action" id="action" value="insert"></x-input>

                <x-input-with-label-element id="title" label="Title" name="title"
                    required></x-input-with-label-element>
                <x-select-with-label id="category" name="category" label="Select Category" required="true">
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Half Yearly">Half Yearly</option>
                </x-select-with-label>
                <x-select-with-label id="package_class" name="package_class" label="Select Package Class" required="true">
                    <option value="Basic">Basic</option>
                    <option value="Premium">Premium</option>
                    <option value="Standard">Standard</option>
                </x-select-with-label>
                <x-text-area-with-label id="content" label="Package DEtails" name="package_details"
                    required></x-text-area-with-label>
                <x-input-with-label-element id="price" label="Price" name="price"
                    required></x-input-with-label-element>
                <x-input-with-label-element id="position" label="Position" name="position"
                    required></x-input-with-label-element>
                <x-select-with-label id="blog_status" name="status" label="Select Status" required="true">
                    <option value="1">Live</option>
                    <option value="0">Disabled</option>
                </x-select-with-label>

                <x-form-buttons></x-form-buttons>
            </x-form-element>

        </x-card-element>

        <x-card-element header="Packages Data">
            <x-data-table>

            </x-data-table>
        </x-card-element>
    </x-content-div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#content').summernote({
            placeholder: 'ElementText',
            tabsize: 2,
            height: 100
        });
        let site_url = '{{ url('/') }}';
        let table = "";
        $(function() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    url: "{{ route('packageData') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        title: "Sr.No."
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: 'Action'
                    },
                    
                    {
                        data: 'title',
                        name: 'title',
                        title: 'Title'
                    },
                    {
                        data: 'package_details',
                        name: 'package_details',
                        title: 'Package Details'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        title: 'Category'
                    },
                    {
                        data: 'package_class',
                        name: 'package_class',
                        title: 'Package Class'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        title: 'Price'
                    },
                    {
                        data: 'position',
                        name: 'position',
                        title: 'Position'
                    },

                ],
                order: [
                    [2, "desc"]
                ]
            });

        });
        $(document).on("click", ".edit", function() {
            let row = $.parseJSON(atob($(this).data("row")));
            if (row['id']) {
                $("#id").val(row['id']);
                $("#title").val(row['title']);
                $("#category").val(row['category']);
                $("#package_class").val(row['package_class']);
                $("#price").val(row['price']);
                $("#position").val(row['position']);

                $("#blog_status").val(row['status']);
                $("#action").val("update");
                $("#content").val(row['package_details']);
                $('#content').summernote('destroy');
                $('#content').summernote({
                    focus: true
                });
                scrollToDiv();
            } else {
                errorMessage("Something went wrong. Code 101");
            }
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
                            url: '{{ route('savePackages') }}',
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


        $(document).ready(function() {
            $("#submitForm").on("submit", function() {
                var form = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('savePackages') }}',
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            successMessage(response.message, "reload");
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
    </script>
    @include('Dashboard.include.dataTablesScript')
@endsection
