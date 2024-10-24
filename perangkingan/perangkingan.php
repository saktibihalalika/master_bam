<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>

<div class="section">
	<div class="section-header">
		<h1>Perangkingan</h1>
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
								<a href="perangkingan.php" class="btn btn-info">Kembali</a>
							</div>
					</div>
					<p></p>
					<?php
							if (isset($_POST['simpan'])) {
								// Mendapatkan nilai alternatif dari POST
								$alt = $_POST['alt']; // Pastikan ini berisi nilai yang valid
								// Menyiapkan pernyataan untuk mengambil kriteria
								$stmtx4 = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmtx4->execute();

								// Mengambil hasil kriteria
								while ($rowx4 = $stmtx4->fetch()) {
									// Mengambil ID kriteria
									$idkri = $rowx4['id_kriteria'];

									// Memeriksa apakah ada input kriteria dan alternatif yang valid
									if (isset($_POST['kri'][$idkri]) && isset($_POST['altkri'][$idkri])) {
										$kri = $_POST['kri'][$idkri]; // ID alternatif
										$altkri = $_POST['altkri'][$idkri]; // Nilai sub-kriteria

										// Menyiapkan pernyataan untuk menyimpan penilaian
										$stmt2 = $connection->prepare("INSERT INTO tbl_penilaian (id_penilaian, nilai_utility, id_alternatif, id_kriteria) VALUES (?, ?, ?, ?)");
										// Pastikan tipe data sesuai; menggunakan bind_param dan menambahkan id_penilaian sebagai parameter.
										// Anda perlu memberikan nilai untuk id_penilaian; misalnya, dengan menggunakan auto-increment di database
										$id_penilaian = null; // Atau sesuaikan dengan logika yang diinginkan

										// Menggunakan bind_param; perhatikan tipe datanya
										$stmt2->bind_param("dsii", $id_penilaian, $alt, $kri, $idkri);

										// Menjalankan pernyataan
										$stmt2->execute();
									}
								}
							}
							if (isset($_POST['update'])) {
								// Mendapatkan nilai alternatif dari POST
								$alt = $_POST['alt']; // ID alternatif yang ingin diupdate
								// Menyiapkan pernyataan untuk mengambil kriteria
								$stmtx4 = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmtx4->execute();

								// Mengambil hasil kriteria
								while ($rowx4 = $stmtx4->fetch()) {
									// Mengambil ID kriteria
									$idkri = $rowx4['id_kriteria'];

									// Memeriksa apakah ada input kriteria dan alternatif yang valid
									if (isset($_POST['kri'][$idkri]) && isset($_POST['altkri'][$idkri])) {
										$kri = $_POST['kri'][$idkri]; // ID sub-kriteria
										$altkri = $_POST['altkri'][$idkri]; // Nilai sub-kriteria yang baru

										// Menyiapkan pernyataan untuk memperbarui penilaian
										$stmt2 = $connection->prepare("UPDATE tbl_penilaian SET nilai_utility = ? WHERE id_alternatif = ? AND id_sub_kriteria = ?");

										// Menggunakan bind_param untuk mengikat nilai ke parameter
										$stmt2->bind_param("dii", $altkri, $alt, $kri); // d untuk double, i untuk integer

										// Menjalankan pernyataan
										$stmt2->execute();
									}
								}
							}
					?>
					<form method="post">
						<table cellpadding="8" class="w-100">
							<tr><input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
							</tr>

							<tr>
								<td>Nama Alternatif</td>
								<td><select name="alt" class="form-control">
										<option value="<?php echo isset($_GET['alt']) ? $_GET['alt'] : ''; ?>"><?php echo isset($_GET['alt']) ? $_GET['alt'] : ''; ?></option>
										<?php
										$stmt3 = $connection->prepare("select * from tbl_alternatif");
										$stmt3->execute();
										while ($row3 = $stmt3->fetch()) {
										?>
											<option value="<?php echo $row3['id_alternatif'] ?>"><?php echo $row3['nama_alternatif'] ?></option>
										<?php
										}
										?>
									</select></td>
							</tr>

							<?php
							$stmt4 = $connection->prepare("select * from tbl_kriteria");
							$stmt4->execute();
							$no = 1;
							while ($row4 = $stmt4->fetch()) {
							?>
								<div class="row cells3">
									<div class="cell"><input type="hidden" name="kri[<?php echo $row4['id_kriteria'] ?>]" value="<?php echo $row4['id_kriteria'] ?>"><?php echo $no++ ?>.
										<?php echo $row4['nama_kriteria'] ?></div>
									<div class="cell colspan2">
										<div class="form-control select full-size">
											<select name="altkri[<?php echo $row4['id_kriteria'] ?>]">
												<?php
												$stmt5 = $connection->prepare("select * from tbl_sub_kriteria where id_kriteria='" . $row4['id_kriteria'] . "'");
												$stmt5->execute();
												while ($row5 = $stmt5->fetch()) {
												?>
													<option value="<?php echo $row5['nilai_sub_kriteria'] ?>"><?php echo $row5['nama_sub_kriteria'] ?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php
							}
							?>
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
							if (isset($_GET['alt'])) {
								$stmt = $connection->prepare("delete from tbl_penilaian where id_alternatif='" . $_GET['alt'] . "'");
								if ($stmt->execute()) {
				?>
						<script type="text/javascript">
							location.href = 'perangkingan.php'
						</script>
				<?php
								}
							}
						} else {
				?>
				<div class="col-12">
					<a href="execute-rangking.php" class="btn btn-success">Eksekusi Perangkingan</a>
					<a href="?page=form" class="btn btn-primary">Tambah</a>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
					<thead>
						<tr>
							<th width="50">No</th>
							<th>Alternatif</th>
							<?php
							$stmt2 = $connection->prepare("select * from tbl_kriteria");
							$stmt2->execute();
							while ($row2 = $stmt2->fetch()) {
							?>
								<th><?php echo $row2['nama_kriteria'] ?></th>
							<?php
							}
							?>
							<th width="140">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$stmt = $connection->prepare("select * from tbl_alternatif");
							$nox = 1;
							$stmt->execute();
							while ($row = $stmt->fetch()) {
						?>
							<tr>
								<td><?php echo $nox++ ?></td>
								<td><?php echo $row['nama_alternatif'] ?></td>
								<?php
								$stmt3 = $connection->prepare("select * from tbl_kriteria");
								$stmt3->execute();
								while ($row3 = $stmt3->fetch()) {
								?>
									<td>
										<?php
										$stmt4 = $connection->prepare("select * from tbl_penilaian where id_kriteria='" . $row3['id_kriteria'] . "' and id_alternatif='" . $row['id_alternatif'] . "'");
										$stmt4->execute();
										while ($row4 = $stmt4->fetch()) {
											echo $row4['nilai_penilaian'];
										?>
											<!--<a href="?page=form&alt=<?php echo $row['id_alternatif'] ?>&kri=<?php echo $row3['id_kriteria'] ?>&nilai=<?php echo $row4['nilai_penilaian'] ?>" style="color:orange"><span class="mif-pencil icon"></span></a>-->
										<?php
										}
										?>
									</td>
								<?php
								}
								?>
								<td class="align-center">
									<a href="?page=hapus&alt=<?php echo $row['id_alternatif'] ?>" class="btn btn-danger"><span class="mif-cancel icon"></span> Hapus</a>
								</td>
							</tr>
						<?php
							}
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