<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>
<div class="section">
  <div class="section-header">
    <h1>Sub Kriteria</h1>
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
                <a href="subkriteria.php" class="btn btn-info">Kembali</a>
              </div>
          </div>
          <p></p>
          <?php
              if (isset($_POST['simpan'])) {
                // Ambil data dari form
                $nama = $_POST['nama'];
                $nilai = $_POST['nilai'];
                $kriteria = $_POST['kriteria'];

                // Persiapan statement untuk INSERT
                $stmt2 = $connection->prepare("INSERT INTO tbl_sub_kriteria (nama_sub_kriteria, nilai_sub_kriteria, id_kriteria) VALUES (?, ?, ?)");

                // Bind parameter (s = string, i = integer/double)
                $stmt2->bind_param("sdi", $nama, $nilai, $kriteria);

                // Eksekusi statement
                if ($stmt2->execute()) {
                  echo "<script type='text/javascript'>location.href = 'subkriteria.php';</script>";
                } else {
                  echo "<script type='text/javascript'>alert('Gagal menyimpan data');</script>";
                }

                // Tutup statement setelah selesai
                $stmt2->close();
              }

              if (isset($_POST['update'])) {
                // Ambil data dari form
                $id = $_POST['id'];
                $nama = $_POST['nama'];
                $nilai = $_POST['nilai'];
                $kriteria = $_POST['kriteria'];

                // Persiapan statement untuk UPDATE
                $stmt2 = $connection->prepare("UPDATE tbl_sub_kriteria SET nama_sub_kriteria = ?, nilai_sub_kriteria = ?, id_kriteria = ? WHERE id_sub_kriteria = ?");

                // Bind parameter (s = string, i = integer/double)
                $stmt2->bind_param("sdii", $nama, $nilai, $kriteria, $id);

                // Eksekusi statement
                if ($stmt2->execute()) {
                  echo "<script type='text/javascript'>location.href = 'subkriteria.php';</script>";
                } else {
                  echo "<script type='text/javascript'>alert('Gagal mengubah data');</script>";
                }

                // Tutup statement setelah selesai
                $stmt2->close();
              }
          ?>

          <form method="post">
            <table cellpadding="8" class="w-100">
              <tr><input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
              </tr>

              <tr>
                <td>Nama Sub Kriteria</td>
                <td><input type="text" class="form-control" name="nama" placeholder="" value="<?php echo isset($_GET['nama']) ? $_GET['nama'] : ''; ?>"></td>
              </tr>

              <tr>
                <td>Nilai Sub Kriteria</td>
                <td><input type="text" class="form-control" name="nilai" placeholder="" value="<?php echo isset($_GET['nilai']) ? $_GET['nilai'] : ''; ?>"></td>
              </tr>

              <tr>
                <td>Nama Kriteria</td>
                <td>
                  <select class="form-control" name="kriteria">
                    <option value="">Pilih Kriteria</option> <!-- Menambahkan pilihan default -->
                    <?php
                    // Persiapkan dan eksekusi query untuk mengambil kriteria
                    $stmt3 = $connection->prepare("SELECT * FROM tbl_kriteria");
                    $stmt3->execute();
                    $result3 = $stmt3->get_result(); // Mendapatkan hasil dari query

                    // Menampilkan opsi dari hasil query
                    while ($row3 = $result3->fetch_assoc()) {
                      // Mengatur nilai dan menampilkan nama kriteria
                      $selected = (isset($_GET['kriteria']) && $_GET['kriteria'] == $row3['id_kriteria']) ? 'selected' : ''; // Memeriksa apakah kriteria yang dipilih
                    ?>
                      <option value="<?php echo $row3['id_kriteria']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($row3['nama_kriteria']); ?> <!-- Mengamankan output -->
                      </option>
                    <?php
                    }
                    // Tutup statement
                    $stmt3->close();
                    ?>
                  </select>

                </td>
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
                $stmt = $connection->prepare("delete from tbl_sub_kriteria where id_sub_kriteria='" . $_GET['id'] . "'");
                if ($stmt->execute()) {
        ?>
            <script type="text/javascript">
              location.href = 'subkriteria.php'
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
          <thead style="text-align:center">
            <tr>
              <th width="50">No</th>
              <th>Kriteria</th>
              <th>Sub Kriteria</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody style="text-align:center">
            <?php
              $stmt = $connection->prepare("SELECT * FROM tbl_kriteria");
              $stmt->execute();
              $result = $stmt->get_result(); // Mendapatkan hasil dari query

              $no = 1; // Inisialisasi nomor urut
              if ($result->num_rows > 0) { // Cek apakah ada data
                while ($row = $result->fetch_assoc()) { // Mengambil data sebagai array asosiatif
            ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td style="text-align:left"><?php echo $row['nama_kriteria']; ?></td>
                  <td>
                    <?php
                    // Ambil sub-kriteria berdasarkan id_kriteria
                    $stmt2 = $connection->prepare("SELECT * FROM tbl_sub_kriteria WHERE id_kriteria = ?");
                    $stmt2->bind_param("i", $row['id_kriteria']); // Bind parameter untuk keamanan
                    $stmt2->execute();
                    $result2 = $stmt2->get_result(); // Mendapatkan hasil dari sub-query

                    if ($result2->num_rows > 0) { // Cek apakah ada sub-kriteria
                      while ($row2 = $result2->fetch_assoc()) { // Mengambil data sub-kriteria
                    ?>
                        <div style="padding-bottom: 22px;">
                          <?php echo $row2['nilai_sub_kriteria']; ?> - <?php echo $row2['nama_sub_kriteria']; ?>
                        </div>
                    <?php
                      }
                    } else {
                      echo "Tidak ada sub-kriteria.";
                    }
                    // Tutup statement sub-query
                    $stmt2->close();
                    ?>
                  </td>
                  <td>
                    <?php
                    // Ambil kembali sub-kriteria berdasarkan id_kriteria untuk tombol
                    $stmt2 = $connection->prepare("SELECT * FROM tbl_sub_kriteria WHERE id_kriteria = ?");
                    $stmt2->bind_param("i", $row['id_kriteria']); // Bind parameter untuk keamanan
                    $stmt2->execute();
                    $result2 = $stmt2->get_result(); // Mendapatkan hasil dari sub-query

                    if ($result2->num_rows > 0) {
                      while ($row2 = $result2->fetch_assoc()) {
                    ?>
                        <div style="padding-bottom: 9px;">
                          <a href="?page=form&id=<?php echo $row2['id_sub_kriteria']; ?>&nama=<?php echo urlencode($row2['nama_sub_kriteria']); ?>&nilai=<?php echo $row2['nilai_sub_kriteria']; ?>&kriteria=<?php echo $row2['id_kriteria']; ?>" class="btn btn-warning" style="padding: 3px;">
                            <span> Ubah </span>
                          </a>
                          <a href="?page=hapus&id=<?php echo $row2['id_sub_kriteria']; ?>" class="btn btn-danger" style="padding: 3px;">
                            <span> Hapus </span>
                          </a>
                        </div>
                    <?php
                      }
                    }
                    $stmt2->close();
                    ?>
                  </td>
                </tr>

            <?php
                }
              } else {
                echo "<tr><td colspan='3'>Data kriteria tidak ditemukan.</td></tr>";
              }
              // Tutup statement utama
              $stmt->close();
            ?>

          </tbody>
        </table>
      </div>
      <p><br /></p>
    </div>
  </div>
</div>
</div>
</div>
<?php
            }
            require_once '../layout/_bottom.php';
?>
<script src="../assets/js/page/modules-datatables.js"></script>