<?= $this->extend("layout/template");?>


<?= $this->section("content");?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pegawai</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">DataTables</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Data Pegawai</h3>
                            <div class="card-tools">
                                <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i> -->
                                </button>
                            </div>

                        </div>

                        <div class="card-body">
                            <form action="/pegawai/updateData/<?=$pegawai->id?>" method="post">
                                <div class="form-group">
                                    <label for="">Nama Pegawai</label>
                                    <input value="<?=$pegawai->nama_pegawai?>" name="nama_pegawai" type="text" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <input value="<?=$pegawai->alamat_pegawai?>" name="alamat_pegawai" type="text" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="id_kategori" id="" class="form-control">
                                        <?php foreach($kategori as $k):?>
                                        <option value="<?=$k['id'] ?>"><?=$k['nama_kategori'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Edit Data</button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
    </section>
</div>

<?=$this->endSection(); ?>