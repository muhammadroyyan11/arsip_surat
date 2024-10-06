<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url() ?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= session()->get('name')?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li><a href="/fileUploads"><i class="fa fa-book"></i> <span>Upload Surat</span></a></li>
<!--            <li><a href="/logs"><i class="fa fa-file-archive-o"></i> <span>Log Upload</span></a></li>-->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Kelola Surat</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <?php foreach ($jenis_surat as $surat): ?>
                        <li><a href="<?= base_url('arsip/surat/' . $surat['id']); ?>"><i class="fa fa-circle-o"></i> <?= $surat['jenis_name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li class="header">MASTER DATA</li>
            <li>
                <a href="/department">
                    <i class="fa fa-file"></i> <span>Data Department</span>
                </a>
            </li>
            <li>
                <a href="/jenis">
                    <i class="fa fa-key"></i> <span>Data Jenis Surat</span>
                </a>
            </li>
            <li>
                <a href="/users">
                    <i class="fa fa-users"></i> <span>Data Users</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
