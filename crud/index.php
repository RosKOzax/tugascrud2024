<?php
    // Koneksi
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "db_crud";

    // Buat Koneksi
    $koneksi = mysqli_connect($server, $username, $password, $database) or die(mysqli_error($koneksi));

    // Kode Otomatis
    $isitabel = mysqli_query($koneksi, "SELECT kode FROM tb_barang ORDER BY kode DESC LIMIT 1");
    $dataterbaru = mysqli_fetch_array($isitabel);
    
    if ($dataterbaru) {
        // Ambil bagian angka dari kode, misalnya dari BRG-1 atau BRG-100 menjadi angka setelah strip
        $no_terakhir = (int)substr($dataterbaru['kode'], strpos($dataterbaru['kode'], '-') + 1);
        // Tambah 1 untuk kode berikutnya
        $no = $no_terakhir + 1;
        // Format kode baru tanpa batasan digit
        $kodebaru = "BRG-" . $no;
    } else {
        // Jika belum ada data, kode dimulai dari BRG-1
        $kodebaru = "BRG-1";
    }

    // Jika Disimpan
    if (isset($_POST["submit"])) {
        // Data Diedit
        if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
            $edit = mysqli_query($koneksi, "UPDATE tb_barang SET kode = '$_POST[kodebarang]', nama = '$_POST[namabarang]', asal = '$_POST[asalbarang]', jumlah = '$_POST[jumlahbarang]', satuan = '$_POST[unit]', tanggal_diterima = '$_POST[tanggalditerima]' WHERE id = '$_GET[id]'");
            if ($edit) {
                echo "<script>
                        alert('Berhasil Edit Data!');
                        document.location='index.php';
                    </script>";
            } else {
                echo "<script>
                        alert('Gagal Edit Data!');
                        document.location='index.php';
                    </script>";
            }
        } else {
            // Data Disimpan
            $simpan = mysqli_query($koneksi, "INSERT INTO tb_barang(kode, nama, asal, jumlah, satuan, tanggal_diterima) VALUES ('$_POST[kodebarang]', '$_POST[namabarang]', '$_POST[asalbarang]', '$_POST[jumlahbarang]', '$_POST[unit]', '$_POST[tanggalditerima]')") or die(mysqli_error($koneksi));

            if ($simpan) {
                echo "<script>
                        alert('Berhasil Simpan Data!');
                        document.location='index.php';
                    </script>";
            } else {
                echo "<script>
                        alert('Gagal Simpan Data!');
                        document.location='index.php';
                    </script>";
            }
        }
    }

    // Deklarasi Variabel Penampung Data yang Akan Diedit
    $vkode = $kodebaru;
    $vnama = "";
    $vasal = "";
    $vjumlah = "";
    $vsatuan = "";
    $vtanggal_diterima = "";

    // Pengujian Tombol Edit / Hapus Ketika DiKlik
    if (isset($_GET['hal'])) {
        // Pengujian Jika Edit Data
        if ($_GET['hal'] == 'edit') {
            // Tampilkan Data Yang Akan Diedit
            $tampil = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id = '$_GET[id]'");
            $data = mysqli_fetch_array($tampil);
            if ($data) {
                $vkode = $data["kode"];
                $vnama = $data["nama"];
                $vasal = $data["asal"];
                $vjumlah = $data["jumlah"];
                $vsatuan = $data["satuan"];
                $vtanggal_diterima = $data["tanggal_diterima"];
            }
        } else if ($_GET['hal'] == 'hapus') {
            $hapus = mysqli_query($koneksi, "DELETE FROM tb_barang WHERE id = '$_GET[id]'");

            if ($hapus) {
                echo "<script>
                        alert('Berhasil Hapus Data!');
                        document.location='index.php';
                    </script>";
            } else {
                echo "<script>
                        alert('Gagal Hapus Data!');
                        document.location='index.php';
                    </script>";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-header {
            background: linear-gradient(135deg, #5a9, #58d);
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .card-footer {
            background: #f8f9fa;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control:hover, .form-select:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn {
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .table-striped > tbody > tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="p-3">
    <div class="container">
        <h2 class="text-center mb-2">Data Inventaris</h2>
        <h3 class="text-secondary text-center mb-4">PT.Serbaguna Aqil Jaya</h3>
        <div class="card col-lg-8 mx-auto mb-4">
            <div class="card-header text-center">Form Data Barang</div>
            <form method="POST" class="card-body px-3 py-1">
                <div class="mb-3">
                    <label for="#kode-barang" class="form-label">Kode Barang</label>
                    <input type="text" class="form-control" name="kodebarang" value="<?= $vkode ?>" id="kode-barang" placeholder="Kode Barang" />
                </div>
                <div class="mb-3">
                    <label for="#nama-barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="namabarang" value="<?= $vnama ?>" id="nama-barang" placeholder="Nama Barang" />
                </div>
                <div class="mb-3">
                    <label for="#asal-barang" class="form-label">Asal Barang</label>
                    <select name="asalbarang" id="asal-barang" class="form-select">
                        <option value="<?= $vasal ?>"><?= $vasal ? $vasal : 'Pilih Asal Barang' ?></option>
                        <option value="Sumatera">Sumatera</option>
                        <option value="Jawa">Jawa</option>
                        <option value="Kalimantan">Kalimantan</option>
                        <option value="Sulawesi">Sulawesi</option>
                        <option value="Papua">Papua</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="#jumlah-barang" class="form-label">Jumlah Barang</label>
                        <input type="text" class="form-control" name="jumlahbarang" value="<?= $vjumlah ?>" id="jumlah-barang" placeholder="Jumlah Barang" />
                    </div>
                    <div class="col-md-6">
                        <label for="#unit-barang" class="form-label">Unit Barang</label>
                        <select name="unit" id="unit-barang" class="form-select">
                            <option value="<?= $vsatuan ?>"><?= $vsatuan ? $vsatuan : 'Pilih Unit Barang' ?></option>
                            <option value="Kg">Kg</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Box">Box</option>
                            <option value="Pack">Pack</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="#tanggal-diterima" class="form-label">Tanggal Diterima</label>
                    <input type="date" name="tanggalditerima" value="<?= $vtanggal_diterima ?>" id="tanggal-diterima" class="form-control" />
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                    <button class="btn btn-danger" name="reset" type="reset">Kosongkan</button>
                </div>
            </form>
        </div>

        <div class="card col-12">
            <div class="card-header">Data Barang</div>
            <div class="card-body">
            <div class="card-body">
                    <div class="container-fluid d-flex align-items-center justify-content-center">
                        <div class="container-fluid d-flex align-items-center justify-content-center">
                            <form method="POST" class="col-8 my-2 text-center d-flex align-items-center justify-content-center">
                                <input type="search" name="cari" id="cari" value="<?= @$_POST['cari']?>" class="form-control" placeholder="Cari Barang">
                                <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                                <button class="btn btn-danger" name="breset" type="submit">Reset</button>
                            </form>
                        </div>
                    </div>
                <table class="table table-striped table-hover table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Asal Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Diterima</th>
                        <th>Perbarui</th>
                    </tr>
                    <?php
                        $no = 1;
                        if (isset($_POST['bcari'])){
                            $keyword = $_POST['cari'];
                            $isitabel = "SELECT * FROM tb_barang WHERE kode LIKE '%$keyword%' OR nama LIKE '%$keyword%' ORDER BY id DESC";
                        } else {
                            $isitabel = "SELECT * FROM tb_barang ORDER BY id DESC";
                        }
                        
                        $tampilkan = mysqli_query($koneksi, $isitabel);
                        while ($data = mysqli_fetch_array($tampilkan)) :
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['kode'] ?></td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['asal'] ?></td>
                        <td><?= $data['jumlah'] . ' ' . $data['satuan'] ?></td>
                        <td><?= $data['tanggal_diterima'] ?></td>
                        <td>
                            <a href="index.php?hal=edit&id=<?= $data['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?hal=hapus&id=<?= $data['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
