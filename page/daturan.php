<?php

session_start();
$con = mysqli_connect("sql209.epizy.com", "epiz_29132838", "vFeo4yUAVpGWIT", "epiz_29132838_metabolik");

if( !isset($_SESSION["login"]) ) {
    header("Location: ../../login/login_admin.php");
    exit;
}

require '../koneksi.php';

$jumlahdataperhalaman = 10;
$jumlahdata = count(query("SELECT*FROM penyakit"));
$jumlahhalaman = ceil($jumlahdata/$jumlahdataperhalaman);
$halamanaktif = (isset($_GET["halaman"])) ? $_GET["halaman"]:1;
$awaldata = ($jumlahdataperhalaman * $halamanaktif) - $jumlahdataperhalaman;
$list_data = '';
$q = "select * from penyakit order by kode_penyakit LIMIT $awaldata, $jumlahdataperhalaman";
$q = mysqli_query($con, $q);
$no = 0;

if (mysqli_num_rows($q) > 0) {
    while ($r = mysqli_fetch_array($q)) {
      $no++;


        $id = $r['kode_penyakit'];
        $gejala = array();
        $qgejala = "select * from basispengetahuan where kode_penyakit='$id'"; //ambil data gejala dari tabel rule
        $qgejala = mysqli_query($con, $qgejala);
        while ($rgejala = mysqli_fetch_array($qgejala)) { //perulangan untuk menampung data gejala
            $r_gejala = mysqli_fetch_array(mysqli_query($con, "select kode_gejala from gejala where kode_gejala='" . $rgejala['kode_gejala'] . "'"));
            $gejala[] = $r_gejala['kode_gejala'];
        }
        $daftar_gejala = implode(" - ", $gejala); //satukan data gejala dan tambahkan pemisah "-"
        $list_data .= '
		<tr>
		<td>'.$no.'</td>
		<td>' . $r['nama_penyakit'] . '</td>
		<td>' . $daftar_gejala . '</td>
		<td>
		<a href="tambah_aturan.php?id_penyakit='. $r['id_penyakit'] .'" class="btn btn-success btn-xs" title="Ubah"><i class="fa fa-edit"></i> Ubah</a> &nbsp;
		</tr>';

    }
}

//tombol cari diklik
if ( isset($_POST["cari_rule"]) ) {
  $drule=cari_rule($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistem Pakar Metabolik</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendor/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../vendor/assets/vendors/css/vendor.bundle.base.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="../vendor/assets/css/style.css">
    
    <!-- End layout styles -->
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.html"> <h3>SP METABOLIK</h3> </a>
          <a class="navbar-brand brand-logo-mini" href="index.html"> <h3>SPM</h3> </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="../vendor/assets/images/faces/face1.jpg" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"> <?php echo $_SESSION["username"]; ?> </p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="../../login/logout.php">
                  <i class="mdi mdi-logout mr-2 text-primary"></i>Log Out </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="../vendor/assets/images/faces-clipart/pic-3.png" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2"> <?php echo $_SESSION["username"]; ?> </span>
                  <span class="text-secondary text-small"> <?php echo $_SESSION["role"]; ?> </span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index_dokter.php">
                <span class="menu-title">Beranda</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dpenyakit.php">
                <span class="menu-title">Data Penyakit</span>
                <i class="mdi mdi-file-check menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dgejala.php">
                <span class="menu-title">Data Gejala</span>
                <i class="mdi mdi-file-document menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dpangan.php">
                <span class="menu-title">Data Bahan Pangan</span>
                <i class="mdi mdi-file-document menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="daturan.php">
                <span class="menu-title">Data Aturan</span>
                <i class="mdi mdi-settings menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="riwayat_diagnosa.php">
                <span class="menu-title">Riwayat Diagnosa</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="edit_profil_dokter.php?id=<?= $_SESSION["id_user"];?>">
                <span class="menu-title">Lihat Profil</span>
                <i class="mdi mdi-autorenew menu-icon"></i>
              </a>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Dashboard
              </h3>
            </div>
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body text-center">
                    <h4 class="card-title">Data Aturan</h4>
                    <hr>
                    
                    <nav class="navbar navbar-light ">
                    <a class=""></a>
                      <form class="form-inline" action="" method="post">
                        <input class="form-control mr-sm-2" type="text" placeholder="Nama Penyakit.." name="keyword"  autocomplete="off" autofocus>
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="cari_rule">Cari</button>
                      </form>
                    </nav><br>

                        <table class="table table-bordered">
                            <tr class="text-center text-light" style="background-color:#2D6187;">
                            <th>No.</th>
                            <th>Nama Penyakit</th>
                            <th>kode Gejala</th>
                            <th>Aksi</th>
                            </tr>

                            <tbody>
                    <?php echo $list_data; ?>
                </tbody>
                        </table> <br><br>

                        <!--navigasi-->
                        <div class="container">
                          <div class="row">
                            <div class="col-md-6 offset-md-5">
                              <nav aria-label="Page navigation example">
                                <ul class="pagination">                                  
                                  <li class="page-item">
                                    <?php if($halamanaktif>1) :?>
                                      <a class="page-link" href="?halaman=<?= $halamanaktif-1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                      </a>
                                    <?php endif ;?>
                                    <?php for($i=1; $i<=$jumlahhalaman; $i++) :?>
                                    <?php if($i==$halamanaktif) : ?>
                                      <a class="page-link" href="?halaman=<?= $i;?>" style="font-weight: bold; color: grey;"> <?= $i; ?> </a>
                                    <?php else :?>
                                      <li class="page-item"><a class="page-link" href="?halaman=<?= $i;?>"> <?= $i; ?> </a></li>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                      </li>
                                      <li class="page-item">
                                        <?php if($halamanaktif<$jumlahhalaman) :?>
                                        <a class="page-link" href="?halaman=<?= $halamanaktif+1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        </a>
                                        <?php endif ;?>
                                    </li>
                                  </ul>
                              </nav>
                            </div>
                          </div>
                        </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid clearfix">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ?? bootstrapdash.com 2020</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates </a> from Bootstrapdash.com</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../vendor/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../vendor/assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../vendor/assets/js/off-canvas.js"></script>
    <script src="../vendor/assets/js/hoverable-collapse.js"></script>
    <script src="../vendor/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../vendor/assets/js/dashboard.js"></script>
    <script src="../vendor/assets/js/todolist.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>