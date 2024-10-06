<!-- Content Wrapper. Contains page content -->
<style>
    .file-drop-area {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 100%;
        padding: 25px;
        border: 2px dashed #ccc;
        border-radius: 3px;
        transition: 0.2s;
    }

    .fake-btn {
        flex-shrink: 0;
        background-color: #007bff;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    .file-msg {
        font-size: 1.2em;
        margin-left: 15px;
    }

    .file-input {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-drop-area.is-active {
        background-color: rgba(0, 123, 255, 0.05);
        border-color: #007bff;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Uploads
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Surat</label>
                                    <input type="text" class="form-control" name="nama_surat" id="exampleInputEmail1" placeholder="Enter Nama Surat">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nomor Surat</label>
                                    <input type="text" class="form-control" name="nomor_surat" id="exampleInputEmail1" placeholder="Enter No Surat">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jenis Surat</label>
                                    <select name="jenis_surat" id="jenis_surat" class="form-control">
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        <?php foreach ($jenis_surat as $surat): ?>
                                            <option value="<?= $surat['id'] ?>"><?= $surat['jenis_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fileInput">File input (Drag & Drop)</label>
                                    <div class="file-drop-area" id="file-drop-area">
                                        <span class="fake-btn">Choose files</span>
                                        <span class="file-msg">or drag and drop files here</span>
                                        <input class="file-input" id="fileInput" type="file" name="file" multiple>
                                    </div>
                                    <p class="help-block">You can upload files by clicking or dragging here.</p>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </div>

        <!-- /.tab-pane -->
</div>
</aside>
<!-- /.control-sidebar -->

<script>
    const fileInput = document.getElementById('fileInput');
    const fileDropArea = document.getElementById('file-drop-area');

    fileInput.addEventListener('change', (e) => {
        const fileCount = e.target.files.length;
        const fileMsg = fileDropArea.querySelector('.file-msg');
        fileMsg.textContent = `${fileCount} file(s) selected`;
    });

    fileDropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileDropArea.classList.add('is-active');
    });

    fileDropArea.addEventListener('dragleave', () => {
        fileDropArea.classList.remove('is-active');
    });

    fileDropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        fileInput.files = e.dataTransfer.files;
        const fileCount = fileInput.files.length;
        const fileMsg = fileDropArea.querySelector('.file-msg');
        fileMsg.textContent = `${fileCount} file(s) selected`;
        fileDropArea.classList.remove('is-active');
    });


    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();  // Prevents the default form submission

            // Prepare form data
            var formData = new FormData(this);

            $.ajax({
                url: '/uploads/file',  // Replace with your actual upload route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Show SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'File and data uploaded successfully!'
                    }).then(() => {
                        // Refresh the page after SweetAlert is closed
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error uploading file: ' + xhr.responseText
                    });
                }
            });
        });
    });
</script>
<!-- ./wrapper -->