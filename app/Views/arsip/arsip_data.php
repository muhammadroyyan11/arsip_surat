<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $title ?></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Table With Full Features</h3>
                        <div class="pull-right">
                            <a href="/fileUploads" class="btn btn-primary">Add</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="arsip_tb" class="table table-bordered table-striped" width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nama Surat</th>
                                <th>Nomor Surat</th>
                                <th>Jenis Surat</th>
                                <th>File Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be filled dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeBtn">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('arsip/arsip_js.php') ?>