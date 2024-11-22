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
								// Get selected alternative ID from POST
								$alt = $_POST['alt']; // This should be the ID of the selected alternative

								// Preparing to fetch criteria once
								$stmt_kriteria = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmt_kriteria->execute();
								$result_kriteria = $stmt_kriteria->get_result();

								// Loop over each criterion and process associated utility values
								while ($row_kriteria = $result_kriteria->fetch_assoc()) {
									$idkri = $row_kriteria['id_kriteria'];

									// Check if the sub-criteria (utility value) input exists for this criterion
									if (isset($_POST['kri'][$idkri])) {
										$id_sub_kriteria = $_POST['kri'][$idkri]; // This should be the ID of the selected sub-criteria

										// Fetch the actual 'nilai_sub_kriteria' from 'tbl_sub_kriteria' using the selected sub-criteria ID
										$stmt_sub_kriteria = $connection->prepare("SELECT nilai_sub_kriteria FROM tbl_sub_kriteria WHERE id_sub_kriteria = ?");
										$stmt_sub_kriteria->bind_param("i", $id_sub_kriteria); // Binding sub-criteria ID
										$stmt_sub_kriteria->execute();
										$stmt_sub_kriteria->bind_result($nilai_awal); // Binding the result (nilai_sub_kriteria)
										$stmt_sub_kriteria->fetch(); // Fetch the result
										$stmt_sub_kriteria->close(); // Close the sub-kriteria statement

										// Now we have the 'nilai_awal', prepare an INSERT statement to save it into tbl_penilaian
										$stmt2 = $connection->prepare("INSERT INTO tbl_penilaian (id_penilaian, nilai_awal, id_alternatif, id_kriteria) VALUES (?, ?, ?, ?)");
										$id_penilaian = null; // Assuming auto-increment or nullable for ID

										// Bind parameters: null ID, fetched utility value, alternative ID, criterion ID
										$stmt2->bind_param("dsii", $id_penilaian, $nilai_awal, $alt, $idkri);

										// Execute the prepared statement
										$stmt2->execute();

										// Close the prepared statement to prevent memory leaks
										$stmt2->close();
									}
								}

								// Close criteria statement
								$stmt_kriteria->close();

								// Optional: add success message or redirect
								echo "<p>Data penilaian berhasil disimpan.</p>";
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
										$stmt2 = $connection->prepare("UPDATE tbl_penilaian SET nilai_awal = ? WHERE id_alternatif = ? AND id_kriteria = ?");

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
									$stmt2 = $connection->prepare("UPDATE tbl_penilaian SET nilai_awal = ? WHERE id_alternatif = ? AND id_kriteria = ?");

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
									$stmt5 = $connection->prepare("SELECT * FROM tbl_sub_kriteria WHERE id_kriteria = ?");
									$stmt5->bind_param("i", $row4['id_kriteria']);
									$stmt5->execute();
									$result5 = $stmt5->get_result();

									// Build options for sub-criteria
									$subOptions = '';
									while ($row5 = $result5->fetch_assoc()) {
										$subOptions .= '<option value="' . htmlspecialchars($row5['id_sub_kriteria'], ENT_QUOTES) . '">' . htmlspecialchars($row5['nama_sub_kriteria'], ENT_QUOTES) . '</option>';
									}
								?>
									<tr>
										<td>
											<input type="hidden" name="kri[<?= $id_kriteria ?>]" value="<?= $id_kriteria ?>">
											<?= $no++ . '. ' . $nama_kriteria; ?>
										</td>
										<td>
											<select name="kri[<?= $id_kriteria ?>]" class="form-control">
												<?php
												// Fetch sub-criteria for the current criterion
												$stmt5 = $connection->prepare("SELECT * FROM tbl_sub_kriteria WHERE id_kriteria = ?");
												$stmt5->bind_param("i", $id_kriteria);
												$stmt5->execute();
												$result5 = $stmt5->get_result();
												while ($row5 = $result5->fetch_assoc()) {
													echo '<option value="' . htmlspecialchars($row5['id_sub_kriteria'], ENT_QUOTES) . '">' . htmlspecialchars($row5['nama_sub_kriteria'], ENT_QUOTES) . '</option>';
												}
												?>
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
					<a href="../execute-rangking/execute-rangking.php" class="btn btn-success">Lihat Hasil Perhitungan</a>
					<a href="?page=form" class="btn btn-primary">Tambah</a>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
					<thead style="text-align:center">
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
					<tbody style="text-align:center">
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
								$penilaianMap[$penilaian['id_alternatif']][$penilaian['id_kriteria']] = $penilaian['nilai_awal'];
							}

							$nox = 1;
							foreach ($alternatifs as $alternatif) {
						?>
							<tr>
								<td><?php echo $nox++; ?></td>
								<td style="text-align:left"><?php echo htmlspecialchars($alternatif['nama_alternatif']); ?></td>
								<?php
								foreach ($kriterias as $kriteria) {
									$nilaiPenilaian = isset($penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']])
										? $penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']]
										: '-'; // Default value if no evaluation exists
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