<script>
    $(document).ready(function () {
        $('#department_tb').DataTable({
            "processing": true, // Show processing indicator
            "serverSide": true, // Server-side processing
            "ajax": {
                "url": "<?= site_url('/department/getdata') ?>", // URL to fetch data
                "type": "POST" // Use POST request
            },
            "columns": [{
                "render": function (data, type, row, meta) {
                    return meta.row + 1; // Generate row count starting from 1
                },
            }, // Mapping to your model's column names
                {
                    "data": "department_name"
                },
                {
                    "data": "actions",
                    "orderable": false,
                    "searchable": false
                } // Action buttons
            ]
        });
    });

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id'); // Get the row ID from the button's data attribute

        // Fetch the row data via AJAX (if needed) or directly from the DataTable row
        $.ajax({
            url: '<?= site_url('/department/getrow') ?>', // API endpoint to get the specific row data
            type: 'POST',
            data: {
                id: id
            },
            success: function (response) {
                console.log(response.department_name);
                // Assuming response contains the row data
                $('#department_name').val(response.department_name); // Populate field 1
                $('#editId').val(response.id); // Store the row ID in a hidden field

                // Show the modal
                $('#editModal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        // Show SweetAlert confirmation dialog

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make an AJAX request to delete the department
                $.ajax({
                    url: '<?= site_url('/department/delete') ?>', // API endpoint to delete the department
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Show SweetAlert success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Reload the DataTable
                            $('#department_tb').DataTable().ajax.reload();
                        } else {
                            // Show SweetAlert error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!'
                        });
                    }
                });
            }
        });
    });

    $('#addForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        var departmentName = $('#addDepartmentName').val(); // Get the department name

        // Make an AJAX request to add the department
        $.ajax({
            url: '<?= site_url('/department/add') ?>', // API endpoint to add the department
            type: 'POST',
            data: {
                department_name: departmentName
            },
            success: function (response) {
                if (response.status === 'success') {
                    // Show SweetAlert success message
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        $('#addForm')[0].reset();

                        $('#department_tb').DataTable().ajax.reload(); // Replace 'yourDataTableId' with the actual ID
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong!'
                });
            }
        });
    });

    $('#saveEditBtn').on('click', function () {
        var formData = $('#editForm').serialize(); // Serialize the form data

        console.log(formData);

        // Send the updated data to the server
        $.ajax({
            url: '<?= site_url('/department/update') ?>', // API endpoint for updating the data
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#editModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    $('#addForm')[0].reset();

                    $('#department_tb').DataTable().ajax.reload();
                });
            },
            error: function () {
                alert('Error saving changes');
            }
        });
    });
</script>   