<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$kriteria = mysqli_query($connection, "SELECT COUNT(*) FROM tbl_kriteria");
$subkriteria = mysqli_query($connection, "SELECT COUNT(*) FROM tbl_sub_kriteria");
$alternatif = mysqli_query($connection, "SELECT COUNT(*) FROM tbl_alternatif");

$total_kriteria = mysqli_fetch_array($kriteria)[0];
$total_subkriteria = mysqli_fetch_array($subkriteria)[0];
$total_alternatif = mysqli_fetch_array($alternatif)[0];
?>

<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <div class="column">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-danger">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Kriteria</h4>
            </div>
            <div class="card-body">
              <?= $total_kriteria ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Sub Kriteria</h4>
            </div>
            <div class="card-body">
              <?= $total_subkriteria ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="far fa-file"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Alternatif</h4>
            </div>
            <div class="card-body">
              <?= $total_alternatif ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
    </div>
  </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>