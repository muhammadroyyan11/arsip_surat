<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Table With Full Features</h3>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" id="addUserBtn">
                                Add User
                            </button>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="users_tb" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.row -->

        <!-- /.tab-pane -->
</div>
</aside>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="form-group">
                        <label for="addDepartmentName">Department Name</label>
                        <input type="text" class="form-control" id="addDepartmentName" name="department_name"
                               placeholder="Enter Department Name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit User -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Add/Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3 mt-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3 mt-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small style="color: red">* Leave empty if not changing password (edit mode)</small>
                    </div>

                    <div class="mb-3 mt-2">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-control" required>
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <!-- Hidden field for storing the user ID -->
                    <input type="hidden" id="editId" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php //= $this->include('department/department_js.php') ?>

<script>
    $(document).ready(function () {
        var table = $('#users_tb').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= site_url('/users/getDatatables') ?>", // Your DataTable data URL
                "type": "POST"
            },
            "columns": [
                {"data": "id"}, // Id column
                {"data": "name"}, // Username column
                {"data": "email"}, // Username column
                {"data": "department_name"}, // Department column
                {"data": "actions", "orderable": false, "searchable": false} // Action buttons
            ]
        });

        $('#users_tb tbody').on('click', '.edit-btn', function () {
            var data = table.row($(this).parents('tr')).data(); // Get the row data
            $('#editModalLabel').text('Edit User');


            // Populate the modal fields with row data
            $('#editId').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);

            // Load department options via AJAX
            $.ajax({
                url: "<?= site_url('/users/getDepartment') ?>", // The URL to fetch department data
                type: "GET",
                success: function (response) {
                    var select = $('#department_id');
                    select.empty(); // Clear existing options

                    // Loop through the departments and append options
                    $.each(response, function (key, department) {
                        var selected = data.id_department == department.id ? 'selected' : ''; // Pre-select if it matches
                        select.append('<option value="' + department.id + '" ' + selected + '>' + department.department_name + '</option>');
                    });
                },
                error: function () {
                    alert('Failed to load departments');
                }
            });

            // Open the modal
            $('#editModal').modal('show');
        });

        $('#addUserBtn').on('click', function () {
            $('#editForm')[0].reset(); // Reset form fields
            $('#editId').val(''); // Clear the hidden ID field
            $('#editModalLabel').text('Add User'); // Set modal title for "Add" action

            $.ajax({
                url: "<?= site_url('/users/getDepartment') ?>", // The URL to fetch department data
                type: "GET",
                success: function (response) {
                    var select = $('#department_id'); // Ensure this ID matches the modal field ID
                    select.empty(); // Clear existing options

                    select.append('<option value="">Select Department</option>'); // Default placeholder option

                    $.each(response, function (key, department) {
                        select.append('<option value="' + department.id + '">' + department.department_name + '</option>');
                    });
                },
                error: function () {
                    alert('Failed to load departments');
                }
            });

            $('#editModal').modal('show'); // Show modal
        });

        function loadDepartments() {
            $.ajax({
                url: "<?= site_url('users/getDepartment') ?>", // URL to fetch department data
                type: "GET",
                success: function (response) {
                    var select = $('#departement_id');
                    select.empty(); // Clear existing options
                    select.append('<option value="">Select Department</option>');
                    $.each(response, function (key, department) {
                        select.append('<option value="' + department.id + '" ' + selected + '>' + department.department_name + '</option>');
                    });
                }
            });
        }

        $('#users_tb').on('click', '.delete-btn', function () {
            var userId = $(this).data('id'); // Get the user ID from the delete button

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an AJAX request to delete the user
                    $.ajax({
                        url: "<?= site_url('/users/delete') ?>/" + userId, // Adjust the URL accordingly
                        type: "DELETE", // Use DELETE method for deleting
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'The user has been deleted.',
                                    'success'
                                );

                                // Reload the DataTable after deletion
                                $('#users_tb').DataTable().ajax.reload(null, false); // false to prevent page reset
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'There was an issue deleting the user.',
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'Failed to delete the user due to a server error.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Handle save button click
        $('#saveEditBtn').on('click', function () {
            var formData = $('#editForm').serialize();
            var is_edit = $('editId').val();
            // alert(is_edit)

            var id = $('#editId').val(); // Get the hidden ID field

            var url = id ? "<?= site_url('/users/update') ?>" : "<?= site_url('/users/add') ?>"; // URL to create or update
            $.ajax({
                url: url, // URL to send data for updating
                type: "POST",
                data: formData,
                success: function (response) {
                    // Close the modal
                    $('#editModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        $('#addForm')[0].reset();

                        $('#users_tb').DataTable().ajax.reload();
                    });

                    // Reload the DataTable
                    table.ajax.reload();
                },
                error: function (xhr, status, error) {
                    // Handle error
                    alert('Error updating data');
                }
            });

        });
    });
</script>
