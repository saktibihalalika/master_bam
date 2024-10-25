<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
$page = isset($_GET['page']) ? $_GET['page'] : "";
?>

<div class="section">
	<div class="section-header">
		<h1>Hasil Eksekusi Perangkingan</h1>
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
					<table class="table table-striped table-hover cell-hover border table-bordered dataTable" data-role="datatable" data-searching="true">
						<thead>
							<tr>
								<th width="50">No</th>
								<th>Alternatif</th>
								<?php
								$stmt2x = $connection->prepare("select * from tbl_kriteria");
								$stmt2x->execute();
								while ($row2x = $stmt2x->fetch()) {
								?>
									<th><?php echo $row2x['nama_kriteria'] ?></th>
								<?php
								}
								?>
								<th>Hasil</th>
								<th>Ranking</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>-</td>
								<td>Bobot</td>
								<?php
								$stmt2x1 = $connection->prepare("select * from tbl_kriteria");
								$stmt2x1->execute();
								while ($row2x1 = $stmt2x1->fetch()) {
								?>
									<td><?php echo $row2x1['bobot_kriteria'] ?></td>
								<?php
								}
								?>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
							<?php
							$stmtx = $connection->prepare("select * from tbl_alternatif");
							$noxx = 1;
							$stmtx->execute();
							while ($rowx = $stmtx->fetch()) {
							?>
								<tr>
									<td><?php echo $noxx++ ?></td>
									<td><?php echo $rowx['nama_alternatif'] ?></td>
									<?php
									$stmt3x = $connection->prepare("select * from tbl_kriteria");
									$stmt3x->execute();
									while ($row3x = $stmt3x->fetch()) {
									?>
										<td>
											<?php
											$stmt4x = $connection->prepare("select * from tbl_penilaian where id_kriteria='" . $row3x['id_kriteria'] . "' and id_alternatif='" . $rowx['id_alternatif'] . "'");
											$stmt4x->execute();
											while ($row4x = $stmt4x->fetch()) {
												$ida = $row4x['id_alternatif'];
												$idk = $row4x['id_kriteria'];
												echo $kal = $row4x['nilai_utility'] * $row3x['bobot_kriteria'];
												$stmt2x3 = $connection->prepare("update tbl_penilaian set bobot_alternatif_kriteria=? where id_alternatif=? and id_kriteria=?");
												$stmt2x3->bindParam(1, $kal);
												$stmt2x3->bindParam(2, $ida);
												$stmt2x3->bindParam(3, $idk);
												$stmt2x3->execute();
											}
											?>
										</td>
									<?php
									}
									?>
									<td>
										<?php
										$stmt3x2 = $connection->prepare("select sum(bobot_alternatif_kriteria) as bak from tbl_penilaian where id_alternatif='" . $rowx['id_alternatif'] . "'");
										$stmt3x2->execute();
										$row3x2 = $stmt3x2->fetch();
										$ideas = $rowx['id_alternatif'];
										echo $hsl = $row3x2['bak'];
										if ($hsl >= 80) {
											$ket = "Sangat Layak";
										} else if ($hsl >= 55) {
											$ket = "Layak";
										} else if ($hsl >= 35) {
											$ket = "Dipertimbangkan";
										} else {
											$ket = "Tidak Layak";
										}
										$stmt2x3y = $connection->prepare("update tbl_alternatif set hasil_alternatif=?, ket_alternatif=? where id_alternatif=?");
										$stmt2x3y->bindParam(1, $hsl);
										$stmt2x3y->bindParam(2, $ket);
										$stmt2x3y->bindParam(3, $ideas);
										$stmt2x3y->execute();
										?>
									</td>
									<td>
										<?php
										// Hitung perangkingan (misalnya, urutan alternatif berdasarkan total bobot)
										$stmt_rank = $connection->prepare("SELECT id_alternatif FROM tbl_penilaian GROUP BY id_alternatif ORDER BY sum(bobot_alternatif_kriteria) DESC");
										$stmt_rank->execute();
										$rank = 1;
										while ($row_rank = $stmt_rank->fetch()) {
											if ($rowx['id_alternatif'] == $row_rank['id_alternatif']) {
												echo $rank; // Output peringkat
												break;
											}
											$rank++;
										}
										?>
									</td>
									<td>
										<?php
										if ($hsl >= 80) {
											$ket2 = "Sangat Layak";
										} else if ($hsl >= 55) {
											$ket2 = "Layak";
										} else if ($hsl >= 35) {
											$ket2 = "Dipertimbangkan";
										} else {
											$ket2 = "Tidak Layak";
										}
										echo $ket2;
										?>
									</td>
								</tr>
							<?php
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