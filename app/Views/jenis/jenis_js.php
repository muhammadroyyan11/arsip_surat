<script>
    $(document).ready(function () {
        $('#jenis_tb').DataTable({
            "processing": true, // Show processing indicator
            "serverSide": true, // Server-side processing
            "ajax": {
                "url": "<?= site_url('/jenis/getdata') ?>", // URL to fetch data
                "type": "POST" // Use POST request
            },
            "columns": [{
                "render": function (data, type, row, meta) {
                    return meta.row + 1; // Generate row count starting from 1
                },
            }, // Mapping to your model's column names
                {
                    "data": "jenis_name"
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
            url: '<?= site_url('/jenis/getrow') ?>', // API endpoint to get the specific row data
            type: 'POST',
            data: {
                id: id
            },
            success: function (response) {
                console.log(response.jenis_name);
                // Assuming response contains the row data
                $('#jenis_name').val(response.jenis_name); // Populate field 1
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
                // Make an AJAX request to delete the jenis
                $.ajax({
                    url: '<?= site_url('/jenis/delete') ?>', // API endpoint to delete the jenis
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
                            $('#jenis_tb').DataTable().ajax.reload();
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

        var jenisName = $('#addjenisName').val(); // Get the jenis name

        // Make an AJAX request to add the jenis
        $.ajax({
            url: '<?= site_url('/jenis/add') ?>', // API endpoint to add the jenis
            type: 'POST',
            data: {
                jenis_name: jenisName
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

                        $('#jenis_tb').DataTable().ajax.reload(); // Replace 'yourDataTableId' with the actual ID
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
            url: '<?= site_url('/jenis/update') ?>', // API endpoint for updating the data
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

                    $('#jenis_tb').DataTable().ajax.reload();
                });
            },
            error: function () {
                alert('Error saving changes');
            }
        });
    });
</script>   