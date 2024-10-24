<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>

<div class="section">
	<div class="section-header">
		<h1>Alternatif</h1>
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
								<a href="alternatif.php" class="btn btn-info">Kembali</a>
							</div>
					</div>
					<p></p>
					<?php
							if (isset($_POST['simpan'])) {
								$nama = $_POST['nama'];

								// Menggunakan prepared statement untuk mencegah SQL injection
								$stmt2 = $connection->prepare("INSERT INTO tbl_alternatif (nama_alternatif) VALUES (?)");
								$stmt2->bind_param("s", $nama); // Menggunakan bind_param untuk tipe data string

								if ($stmt2->execute()) {
									echo "<script type='text/javascript'>location.href = 'alternatif.php';</script>";
								} else {
									echo "<script type='text/javascript'>alert('Gagal menyimpan data');</script>";
								}
							}

							if (isset($_POST['update'])) {
								$id = $_POST['id'];
								$nama = $_POST['nama'];

								// Menggunakan prepared statement untuk mencegah SQL injection
								$stmt2 = $connection->prepare("UPDATE tbl_alternatif SET nama_alternatif=? WHERE id_alternatif=?");
								$stmt2->bind_param("si", $nama, $id); // Menggunakan bind_param untuk tipe data string dan integer

								if ($stmt2->execute()) {
									echo "<script type='text/javascript'>location.href = 'alternatif.php';</script>";
								} else {
									echo "<script type='text/javascript'>alert('Gagal mengubah data');</script>";
								}
							}
					?>

					<form method="post">
						<table cellpadding="8" class="w-100">
							<tr><input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
							</tr>

							<tr>
								<td>Nama Alternatif</td>
								<td><input type="text" class="form-control" name="nama" placeholder="" value="<?php echo isset($_GET['nama']) ? $_GET['nama'] : ''; ?>"></td>
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
								$stmt = $connection->prepare("delete from tbl_alternatif where id_alternatif='" . $_GET['id'] . "'");
								if ($stmt->execute()) {
				?>
						<script type="text/javascript">
							location.href = 'alternatif.php'
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
							<th>Alternatif</th>
							<th width="240">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$stmt = $connection->prepare("SELECT * FROM tbl_alternatif");
							$stmt->execute();
							$result = $stmt->get_result(); // Mendapatkan hasil dari query

							$no = 1; // Inisialisasi nomor urut
							if ($result->num_rows > 0) { // Cek apakah ada data
								while ($row = $result->fetch_assoc()) { // Mengambil data sebagai array asosiatif
						?>
								<tr>
									<td><?php echo $no++; ?></td>
									<td><?php echo htmlspecialchars($row['nama_alternatif']); ?></td> <!-- Mengamankan output -->
									<td class="align-center">
										<a href="?page=form&id=<?php echo $row['id_alternatif']; ?>&nama=<?php echo urlencode($row['nama_alternatif']); ?>" class="btn btn-warning">
											<span class="mif-pencil icon"></span> Edit
										</a>
										<a href="?page=hapus&id=<?php echo $row['id_alternatif']; ?>" class="btn btn-danger">
											<span class="mif-cancel icon"></span> Hapus
										</a>
									</td>
								</tr>
						<?php
								}
							} else {
								echo "<tr><td colspan='3'>Tidak ada data alternatif ditemukan.</td></tr>"; // Pesan jika tidak ada data
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
</div>
</div>
</div>

<?php
						}
						require_once '../layout/_bottom.php';
?>
<script src="../assets/js/page/modules-datatables.js"></script>