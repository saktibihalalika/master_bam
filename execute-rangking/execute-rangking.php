<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>

<div class="section">
	<div class="section-header">
		<h1>Hasil Perhitungan</h1>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<a href="../perangkingan/perangkingan.php" class="btn btn-info">Kembali</a>
						</div>
					</div>
					<p><br /></p>
					<h4>Matriks Keputusan (Nilai Awal)</h4>
					<div class="table-responsive">
						<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
							<thead style="text-align:center">
								<tr>
									<th>No</th>
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
									</tr>
								<?php
								}
								?>
								<!-- Baris Nilai Terbesar -->
								<tr>
									<td colspan="2" style="text-align: center;"><strong>Nilai Terbesar</strong></td>
									<?php
									foreach ($kriterias as $kriteria) {
										$maxValue = null;
										foreach ($alternatifs as $alternatif) {
											$nilaiPenilaian = isset($penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']])
												? $penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']]
												: null;
											if ($nilaiPenilaian !== null && ($maxValue === null || $nilaiPenilaian > $maxValue)) {
												$maxValue = $nilaiPenilaian;
											}
										}
										echo '<td>' . htmlspecialchars($maxValue) . '</td>';
									}
									?>
								</tr>
								<!-- Baris Nilai Terkecil -->
								<tr>
									<td colspan="2" style="text-align: center;"><strong>Nilai Terkecil</strong></td>
									<?php
									foreach ($kriterias as $kriteria) {
										$minValue = null;
										foreach ($alternatifs as $alternatif) {
											$nilaiPenilaian = isset($penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']])
												? $penilaianMap[$alternatif['id_alternatif']][$kriteria['id_kriteria']]
												: null;
											if ($nilaiPenilaian !== null && ($minValue === null || $nilaiPenilaian < $minValue)) {
												$minValue = $nilaiPenilaian;
											}
										}
										echo '<td>' . htmlspecialchars($minValue) . '</td>';
									}
									?>
								</tr>
							</tbody>
						</table>
					</div>

					<p><br /></p>
					<h4>Bobot Kriteria</h4>
					<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
						<thead style="text-align:center">
							<tr>
								<th>-</th>
								<?php
								// Ambil nama-nama kriteria untuk ditampilkan sebagai header tabel
								$stmtKriteria = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmtKriteria->execute();
								$kriterias = [];
								$resultKriteria = $stmtKriteria->get_result();
								while ($row = $resultKriteria->fetch_assoc()) {
									$kriterias[] = $row;
									echo "<th>{$row['nama_kriteria']}</th>";
								}
								?>
							</tr>
						</thead>
						<tbody style="text-align:center">
							<tr>
								<td><strong>Bobot</strong></td>
								<?php
								// Menampilkan bobot dari masing-masing kriteria
								foreach ($kriterias as $kriteria) {
									echo "<td>{$kriteria['bobot_kriteria']}</td>";
								}
								?>
							</tr>
							<tr>
								<td style="text-align: center;"><strong>Benefit / Cost</strong></td>
								<?php
								// Menampilkan bobot dari masing-masing kriteria
								foreach ($kriterias as $kriteria) {
									echo "<td>{$kriteria['tipe_kriteria']}</td>";
								}
								?>
							</tr>
						</tbody>
					</table>

					<p><br /></p>
					<h4>Nilai Utility</h4>
					<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
						<thead style="text-align:center">
							<tr>
								<th>No</th>
								<th>Alternatif</th>
								<?php
								// Ambil nama-nama kriteria untuk ditampilkan sebagai header tabel
								$stmtKriteria = $connection->prepare("SELECT * FROM tbl_kriteria");
								$stmtKriteria->execute();
								$kriterias = [];
								$resultKriteria = $stmtKriteria->get_result();
								while ($row = $resultKriteria->fetch_assoc()) {
									$kriterias[] = $row;
									echo "<th>{$row['nama_kriteria']}</th>";
								}
								?>
							</tr>
						</thead>
						<tbody style="text-align:center">
							<?php
							// Ambil semua data alternatif
							$stmtAlternatif = $connection->prepare("SELECT * FROM tbl_alternatif");
							$stmtAlternatif->execute();
							$resultAlternatif = $stmtAlternatif->get_result();
							$nox = 1;

							// Hitung skor total setiap alternatif dan simpan dalam array
							while ($alternatif = $resultAlternatif->fetch_assoc()) {
								$totalScore = 0;
								$altId = $alternatif['id_alternatif'];

								echo "<tr><td>{$nox}</td><td style='text-align:left;'>{$alternatif['nama_alternatif']}</td>";
								$nox++;

								foreach ($kriterias as $kriteria) {
									// Ambil nilai min, max, dan tipe kriteria
									$stmtMinMax = $connection->prepare("SELECT MIN(nilai_awal) AS min_val, MAX(nilai_awal) AS max_val FROM tbl_penilaian WHERE id_kriteria = ?");
									$stmtMinMax->bind_param("i", $kriteria['id_kriteria']);
									$stmtMinMax->execute();
									$resultMinMax = $stmtMinMax->get_result();
									$minMaxRow = $resultMinMax->fetch_assoc();
									$minValue = $minMaxRow['min_val'];
									$maxValue = $minMaxRow['max_val'];

									// Ambil nilai awal untuk alternatif dan kriteria tertentu
									$stmtPenilaian = $connection->prepare("SELECT nilai_awal FROM tbl_penilaian WHERE id_alternatif = ? AND id_kriteria = ?");
									$stmtPenilaian->bind_param("ii", $altId, $kriteria['id_kriteria']);
									$stmtPenilaian->execute();
									$resultPenilaian = $stmtPenilaian->get_result();
									$nilaiAwalRow = $resultPenilaian->fetch_assoc();

									// Lewati jika nilai_awal tidak ditemukan
									if (!$nilaiAwalRow) {
										echo "<td>-</td>"; // Tampilkan tanda kosong atau indikator
										continue;
									}

									$nilaiAwal = $nilaiAwalRow['nilai_awal'];

									// Hitung nilai utility berdasarkan tipe kriteria
									if ($kriteria['tipe_kriteria'] == 'Cost') {
										$nilaiUtility = ($maxValue - $nilaiAwal) / ($maxValue - $minValue);
									} else {
										$nilaiUtility = ($nilaiAwal - $minValue) / ($maxValue - $minValue);
									}

									// Tampilkan nilai utility
									echo "<td>{$nilaiUtility}</td>";

									// Update nilai_utility di tabel penilaian
									$stmtUpdate = $connection->prepare("UPDATE tbl_penilaian SET nilai_utility = ? WHERE id_alternatif = ? AND id_kriteria = ?");
									$stmtUpdate->bind_param("dii", $nilaiUtility, $altId, $kriteria['id_kriteria']);
									$stmtUpdate->execute();
								}
							}


							?>
						</tbody>
					</table>

					<p><br /></p>
					<h4>Perhitungan Nilai</h4>
					<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
						<thead style="text-align:center">
							<tr>
								<th>Ranking</th>
								<th>Alternatif</th>
								<th>Total Nilai Akhir</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody style="text-align:center">
							<?php
							// Ambil semua data alternatif
							$stmtAlternatif = $connection->prepare("SELECT * FROM tbl_alternatif");
							$stmtAlternatif->execute();
							$resultAlternatif = $stmtAlternatif->get_result();
							$rankingData = [];

							// Hitung skor total setiap alternatif dan simpan dalam array
							while ($alternatif = $resultAlternatif->fetch_assoc()) {
								$totalScore = 0;
								$altId = $alternatif['id_alternatif'];

								foreach ($kriterias as $kriteria) {
									// Ambil nilai utility dan kalikan dengan bobot
									$stmtPenilaian = $connection->prepare("SELECT nilai_utility FROM tbl_penilaian WHERE id_alternatif = ? AND id_kriteria = ?");
									$stmtPenilaian->bind_param("ii", $altId, $kriteria['id_kriteria']);
									$stmtPenilaian->execute();
									$resultPenilaian = $stmtPenilaian->get_result();
									$nilaiUtilityRow = $resultPenilaian->fetch_assoc();
									$nilaiUtility = $nilaiUtilityRow ? $nilaiUtilityRow['nilai_utility'] : 0;

									$totalutility = $nilaiUtility * $kriteria['bobot_kriteria'];
									$totalScore += $totalutility;
								}

								// Tentukan status berdasarkan skor total
								$status = '';
								if ($totalScore >= 0.80) {
									$status = "Sangat Layak";
								} elseif ($totalScore >= 0.55) {
									$status = "Layak";
								} elseif ($totalScore >= 0.35) {
									$status = "Dipertimbangkan";
								} elseif ($totalScore <= 0.35) {
									$status = "Tidak Layak";
								} else {
									$status = "Salah Perhitungan";
								}

								// Update hasil dan keterangan dalam tabel alternatif
								$stmtUpdateAlternatif = $connection->prepare("UPDATE tbl_perangkingan SET total_perhitungan = ?, keterangan = ? WHERE id_alternatif = ?");
								$stmtUpdateAlternatif->bind_param("dsi", $totalScore, $status, $altId);
								$stmtUpdateAlternatif->execute();

								// Simpan ke rankingData untuk urutan peringkat
								$rankingData[] = [
									'id_alternatif' => $altId,
									'nama_alternatif' => $alternatif['nama_alternatif'],
									'total_score' => $totalScore,
									'status' => $status,
								];
							}

							// Urutkan berdasarkan skor tertinggi
							usort($rankingData, function ($a, $b) {
								return $b['total_score'] <=> $a['total_score'];
							});

							// Tampilkan ranking berdasarkan skor
							$rank = 1;
							foreach ($rankingData as $data) {
								echo "<tr>";
								echo "<td>{$rank}</td>";
								echo "<td style='text-align:center;''>{$data['nama_alternatif']}</td>";
								echo "<td>{$data['total_score']}</td>";
								echo "<td>{$data['status']}</td>";
								echo "</tr>";
								$rank++;
							}
							?>
						</tbody>
					</table>
					<p><br /></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require_once '../layout/_bottom.php';
?>
<script src="../assets/js/page/modules-datatables.js"></script>