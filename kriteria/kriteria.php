<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>

<section class="section">
  <div class="section-header">
    <h1>Kriteria</h1>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <?php
            if ($page == 'form') {
            ?>
              <div class="col-12">
                <a href="kriteria.php" class="btn btn-info">Kembali</a>
              </div>
          </div>
          <p></p>
          <?php
              if (isset($_POST['simpan'])) {
                // Persiapan statement untuk query SUM
                $stmt = $connection->prepare("SELECT SUM(bobot_kriteria) AS bbtk FROM tbl_kriteria");

                // Eksekusi statement SELECT
                if ($stmt->execute()) {
                  // Bind hasil ke variabel
                  $stmt->bind_result($bbtk);
                  $stmt->fetch();

                  // Tutup statement pertama setelah selesai mengambil hasil
                  $stmt->close();

                  // Validasi bobot input tidak lebih dari 100
                  if ($_POST['bobot'] <= 100) {
                    $bbt = $_POST['bobot'] / 100; // Konversi bobot ke format desimal
                    $bbtk = $bbt + $bbtk;   // Jumlahkan bobot yang baru dengan total bobot yang ada

                    // Pastikan total bobot tidak lebih dari 1
                    if ($bbtk <= 1) {
                      // Dapatkan data dari form
                      $nama = $_POST['nama'];
                      $bobot = $_POST['bobot'] / 100;

                      // Persiapan statement untuk INSERT
                      $stmt2 = $connection->prepare("INSERT INTO tbl_kriteria (nama_kriteria, bobot_kriteria) VALUES (?, ?)");

                      // Bind parameter untuk nama dan bobot
                      $stmt2->bind_param("sd", $nama, $bobot);  // "s" untuk string, "d" untuk double/float

                      // Eksekusi statement INSERT
                      if ($stmt2->execute()) {
                        // Redirect ke halaman kriteria.php jika berhasil
                        echo "<script type='text/javascript'>
                        location.href = 'kriteria.php';
                    </script>";
                      } else {
                        // Tampilkan pesan error jika gagal menyimpan data
                        echo "<script type='text/javascript'>
                        alert('Gagal menyimpan data');
                    </script>";
                      }

                      // Tutup statement INSERT setelah eksekusi
                      $stmt2->close();
                    } else {
                      // Jika total bobot lebih dari 1
                      echo "<script type='text/javascript'>
                    alert('Bobot haruslah 100% jika dijumlahkan semua kriteria');
                </script>";
                    }
                  } else {
                    // Jika bobot yang diinput lebih dari 100
                    echo "<script type='text/javascript'>
                alert('Maaf nilai bobot maksimal 100');
            </script>";
                  }
                } else {
                  echo "<script type='text/javascript'>
            alert('Gagal mengeksekusi query untuk bobot kriteria');
        </script>";
                }
              }
          ?>



          <form method="post">
            <table cellpadding="8" class="w-100">
              <tr><input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
              </tr>

              <tr>
                <td>Nama Kriteria</td>
                <td><input type="text" class="form-control" name="nama" placeholder="" value="<?php echo isset($_GET['nama']) ? $_GET['nama'] : ''; ?>"></td>
              </tr>

              <tr>
                <td>Bobot Kriteria (%)</td>
                <td><input type="text" class="form-control" name="bobot" placeholder="" value="<?php echo isset($_GET['bobot']) ? $_GET['bobot'] * 100 : ''; ?>"></td>
              </tr>
              <?php
              if (isset($_GET['id'])) {
              ?>
                <tr>
                  <td>
                    <input class="btn btn-danger" type="submit" name="update" value="Update">
                  </td>
                </tr>
              <?php
              } else {
              ?>
                <tr>
                  <td>
                    <input class="btn btn-primary" type="submit" name="simpan" value="Simpan">
                    <input class="btn btn-danger" type="reset" name="batal" value="Bersihkan">
                  </td>
                </tr>
              <?php
              }
              ?>
            </table>
          </form>

        <?php
            } else if ($page == 'hapus') {
        ?>
          <div class="col-12">
          </div>
        </div>
        <?php
              if (isset($_GET['id'])) {
                $stmt = $connection->prepare("delete from tbl_kriteria where id_kriteria='" . $_GET['id'] . "'");
                if ($stmt->execute()) {
        ?>
            <script type="text/javascript">
              location.href = 'kriteria.php'
            </script>
        <?php
                }
              }
            } else {
        ?>
        <div class="col-12">
          <a href="?page=form" class="btn btn-primary">Tambah</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
          <thead>
            <tr>
              <th width="50">ID</th>
              <th>Kriteria</th>
              <th width="50">Bobot</th>
              <th width="240">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
              // Persiapkan dan eksekusi query
              $stmt = $connection->prepare("SELECT * FROM tbl_kriteria");
              $stmt->execute();

              // Ambil hasil
              $result = $stmt->get_result(); // Mendapatkan hasil dari eksekusi

              $no = 1; // Inisialisasi nomor urut
              if ($result->num_rows > 0) { // Cek apakah ada data
                while ($row = $result->fetch_assoc()) { // Mengambil data sebagai array asosiatif
            ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $row['nama_kriteria']; ?></td>
                  <td><?php echo $row['bobot_kriteria']; ?></td>
                  <td class="align-center">
                    <a href="?page=form&id=<?php echo $row['id_kriteria']; ?>&nama=<?php echo urlencode($row['nama_kriteria']); ?>&bobot=<?php echo $row['bobot_kriteria']; ?>" class="btn btn-warning">
                      <span class="mif-pencil icon"></span> Ubah
                    </a>
                    <a href="?page=hapus&id=<?php echo $row['id_kriteria']; ?>" class="btn btn-danger">
                      <span class="mif-cancel icon"></span> Hapus
                    </a>
                  </td>
                </tr>
            <?php
                }
              } else {
                // Jika tidak ada data
                echo "<tr><td colspan='4'>Data tidak ditemukan</td></tr>";
              }

              // Tutup statement
              $stmt->close();
            ?>
          </tbody>
        </table>
      </div>
      <p><br /></p>
    </div>
  </div>
</section>
</div>
</div>
<?php
            }
            require_once '../layout/_bottom.php';
?>
<script src="../assets/js/page/modules-datatables.js"></script>