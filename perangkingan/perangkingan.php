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
										$stmt2 = $connection->prepare("UPDATE tbl_penilaian SET nilai_utility = ? WHERE id_alternatif = ? AND id_kriteria = ?");

										// Menggunakan bind_param untuk mengikat nilai ke parameter
										$stmt2->bind_param("dii", $altkri, $alt, $kri); // d untuk double, i untuk integer

										// Menjalankan pernyataan
										$stmt2->execute();
									}
								}
							}
					?>
					<?php
							// Check if the form is submitted
							if ($_SERVER['REQUEST_METHOD'] === 'POST') {
								// Retrieve the selected alternative and sub-criteria values
								$altkri = $_POST['altkri'] ?? []; // Use null coalescing operator for safety
								$alt = $_POST['alt'] ?? null; // Selected alternative
								$id = $_POST['id'] ?? null; // ID from the GET request

								// Prepare and execute the update statement for each sub-criteria
								if ($alt && !empty($altkri)) {
									$stmt2 = $connection->prepare("UPDATE tbl_penilaian SET nilai_utility = ? WHERE id_alternatif = ? AND id_kriteria = ?");

									foreach ($altkri as $idkri => $value) {
										$stmt2->bind_param("dii", $value, $alt, $idkri); // Bind parameters
										$stmt2->execute(); // Execute the statement
									}
								}
							}
					?>

					<!-- HTML Form -->
					<form method="post">
						<div class="">
							<input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES); ?>">

							<table cellpadding="8" class="w-100">
								<tr>
									<td>Nama Alternatif</td>
									<td>
										<select name="alt" class="form-control">
											<option value="<?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>">
												<?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>
											</option>
											<?php
											// Fetch alternatives
											$stmt3 = $connection->prepare("SELECT * FROM tbl_alternatif");
											$stmt3->execute();
											$result3 = $stmt3->get_result(); // Use get_result() for fetching
											while ($row3 = $result3->fetch_assoc()) {
												echo '<option value="' . htmlspecialchars($row3['id_alternatif'], ENT_QUOTES) . '">' . htmlspecialchars($row3['nama_alternatif'], ENT_QUOTES) . '</option>';
											}
											?>
										</select>
									</td>
								</tr>

								<?php
								// Fetch alternatives
								$stmt3 = $connection->prepare("SELECT * FROM tbl_alternatif");
								$stmt3->execute();
								$result3 = $stmt3->get_result();

								$options = '';
								while ($row3 = $result3->fetch_assoc()) {
									$options .= '<option value="' . htmlspecialchars($row3['id_alternatif'], ENT_QUOTES) . '">' . htmlspecialchars($row3['nama_alternatif'], ENT_QUOTES) . '</option>';
								}
								?>

								</select>
								</td>
								</tr>

								<?php
								// Fetch criteria
								$stmt4 = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmt4->execute();
								$result4 = $stmt4->get_result();

								$no = 1;
								while ($row4 = $result4->fetch_assoc()) {
									$id_kriteria = htmlspecialchars($row4['id_kriteria'], ENT_QUOTES);
									$nama_kriteria = htmlspecialchars($row4['nama_kriteria'], ENT_QUOTES);

									// Fetch sub-criteria for the current criterion
									$stmt5 = $connection->prepare("SELECT * FROM tbl_kriteria WHERE id_kriteria = ?");
									$stmt5->bind_param("i", $row4['id_kriteria']);
									$stmt5->execute();
									$result5 = $stmt5->get_result();

									// Build options for sub-criteria
									$subOptions = '';
									while ($row5 = $result5->fetch_assoc()) {
										$subOptions .= '<option value="' . htmlspecialchars($row5['id_kriteria'], ENT_QUOTES) . '">' . htmlspecialchars($row5['nama_kriteria'], ENT_QUOTES) . '</option>';
									}
								?>
									<tr>
										<td>
											<input type="hidden" name="kri[<?= $id_kriteria ?>]" value="<?= $id_kriteria ?>">
											<?= $no++ . '. ' . $nama_kriteria; ?>
										</td>
										<td>
											<select name="altkri[<?= $id_kriteria ?>]" class="form-control">
												<option value="<?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>">
													<?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>
												</option><?= $subOptions; ?>
											</select>
										</td>
									</tr>
								<?php
								}
								?>
							</table>
							<tr>
								<td>
									<input class="btn btn-primary" type="submit" name="simpan" value="Simpan">
									<input class="btn btn-danger" type="reset" name="batal" value="Bersihkan">
								</td>
							</tr>
						</div>
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
					<a href="../execute-rangking/execute-rangking.php" class="btn btn-success">Eksekusi Perangkingan</a>
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
							// Prepare and execute the query to fetch criteria
							$stmt = $connection->prepare("SELECT nama_kriteria FROM tbl_kriteria");
							$stmt->execute();

							// Bind the result variable
							$stmt->bind_result($nama_kriteria);

							// Fetch and display the criteria in table headers
							while ($stmt->fetch()) {
								echo '<th>' . htmlspecialchars($nama_kriteria, ENT_QUOTES, 'UTF-8') . '</th>';
							}

							// Close the statement
							$stmt->close();
							?>
							<th width="140">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							// Fetch all alternatives
							$stmtAlternatif = $connection->prepare("SELECT * FROM tbl_alternatif");
							$stmtAlternatif->execute();
							$resultAlternatif = $stmtAlternatif->get_result();
							$alternatifs = $resultAlternatif->fetch_all(MYSQLI_ASSOC);

							// Fetch all criteria
							$stmtKriteria = $connection->prepare("SELECT * FROM tbl_kriteria");
							$stmtKriteria->execute();
							$resultKriteria = $stmtKriteria->get_result();
							$kriterias = $resultKriteria->fetch_all(MYSQLI_ASSOC);

							// Fetch all evaluations at once
							$stmtPenilaian = $connection->prepare("SELECT * FROM tbl_penilaian");
							$stmtPenilaian->execute();
							$resultPenilaian = $stmtPenilaian->get_result();
							$penilaians = $resultPenilaian->fetch_all(MYSQLI_ASSOC);

							// Create a mapping of evaluations for easier access
							$penilaianMap = [];
							foreach ($penilaians as $penilaian) {
								$penilaianMap[$penilaian['id_alternatif']][$penilaian['id_kriteria']] = $penilaian['nilai_penilaian'];
							}

							$nox = 1;
							foreach ($alternatifs as $alternatif) {
						?>
							<tr>
								<td><?php echo $nox++; ?></td>
								<td><?php echo htmlspecialchars($alternatif['nama_alternatif']); ?></td>
								<?php
								foreach ($kriterias as $kriteria) {
									$nilaiPenilaian = isset($penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']])
										? $penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']]
										: 'N/A'; // Default value if no evaluation exists
								?>
									<td>
										<?php echo htmlspecialchars($nilaiPenilaian); ?>
										<!--<a href="?page=form&alt=<?php echo $alternatif['id_alternatif']; ?>&kri=<?php echo $kriteria['id_kriteria']; ?>&nilai=<?php echo $nilaiPenilaian; ?>" style="color:orange"><span class="mif-pencil icon"></span></a>-->
									</td>
								<?php
								}
								?>
								<td class="align-center">
									<a href="?page=hapus&alt=<?php echo $alternatif['id_alternatif']; ?>" class="btn btn-danger">
										<span class="mif-cancel icon"></span> Hapus
									</a>
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