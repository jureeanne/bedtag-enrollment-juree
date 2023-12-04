<?php
require '../../includes/session.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Old Students  | BED Taguig</title>

  <?php include '../../includes/links.php'; ?>

</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <?php include '../../includes/navbar.php' ?>

    <?php include '../../includes/sidebar.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">List of Old Students </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"></a></li>
                <li class="breadcrumb-item active"></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">List of Old Students </h3>
            <div class="card-tools">
                <!-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-md">Set Payment Status</button> -->
            </div>
          </div>
          <div class="card-body">
            <form method="GET">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search student">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                  <th>Image</th>
                  <th>Student ID</th>
                  <th>Fullname</th>
                  <th>Strand</th>
                  <th>Grade Level</th>
                  <th>Student Type</th>
                  <th>Date Applied</th>
                  <th>Remark</th>
                  <th>Option</th>
                </tr>
            </thead>
            <tbody class="border-bottom">

                <?php
                  if (isset($_GET['search'])) {
                    $search = addslashes($_GET['search']);

                    $get_enrolled_stud = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname
                            FROM tbl_schoolyears AS sy
                            LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
                            LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
                            LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
                            LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
                            LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id
                            WHERE remark = 'Approved'
                            AND stud_type = 'Old'
                            AND ay.academic_year = '$_SESSION[active_acadyear]'
                            AND (sem.semester = '$_SESSION[active_semester]' OR sy.semester_id = '0')


                            AND (student_fname LIKE '%$_GET[search]%'
                            OR student_mname LIKE '%$_GET[search]%'
                            OR student_lname  LIKE '%$_GET[search]%'
                            OR grade_level  LIKE '%$_GET[search]%'
                            OR stud_no LIKE '%$_GET[search]%')



                            ORDER BY sy.student_id DESC
                            ") or die(mysqli_error($conn));

                while ($row = mysqli_fetch_array($get_enrolled_stud)) {
                    $id = $row['student_id'];
                    $sy_id = $row['sy_id'];
                    $_SESSION['stud_no'] = $row['stud_no'];
                    $_SESSION['orig_id'] = $row['student_id'];
                    $glvl_id = $row['grade_level_id'];
                
                ?>
                <tr>
                <td>

            <?php
                $_SESSION['orig_id'] = $row['student_id'];
                if (!empty(base64_encode($row['img']))) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '"class="img zoom " alt="User image"style="height: 80px; width: 100px">';
                } else {
                    echo ' <img src="../../../assets/img/red_user.jpg" class="img zoom"alt="User image" style="height: 80px; width: 100px">';
                }    
            ?>
                </td>
                <td><?php echo $row['stud_no']; ?></td>
                                                    <td><?php echo $row['fullname']; ?></td>
                                                    <?php if (empty($row['strand_def'])) {
            echo '<td>Grade School</td>';
        } else {
            echo '<td>' . $row['strand_def'] . '</td>';
        }?>
                                                    <td><?php echo $row['grade_level']; ?></td>
                                                    <td><?php echo $row['stud_type']; ?></td>
                                                    <td><?php echo $row['date_enrolled']; ?></td>
                                                    <td>
                                                        <p class="bg-gray-light rounded p-1 mb-0 text-center">
                                                            <i>
                                                                <?php echo $row['remark']; ?>
                                                            </i>
                                                        </p>
                                                    </td>
                                                    <td>


                                                        <a href="../bed-forms/pre-en-data.php?<?php echo 'stud_id=' . $id; ?>"
                                                            type="button" class=" btn bg-purple text-sm p-2 mb-2"><i
                                                                class="fa fa-eye"></i>
                                                            Pre-Enroll
                                                        </a>
                                                        
                                                        
                                                        <?php if (!empty($glvl_id)) {?>
                                                <a href="../bed-forms/accounting-laspi-shs.php?<?php echo 'stud_id=' . $id . '&glvl_id=' . $glvl_id; ?>"
                                                    type="button" class=" btn btn-success text-sm p-2 mb-2"><i
                                                        class="fa fa-eye"></i>
                                                    Reg Form Main
                                                </a>
                                                <?php } else {?>
                                                <a href="../bed-forms/accounting-laspi-shs.php?<?php echo 'stud_id=' . $id; ?>"
                                                    type="button" class=" btn btn-success text-sm p-2 mb-2"><i
                                                        class="fa fa-eye"></i>
                                                    Reg Form Main
                                                </a>
                                                <?php }?>

                                                <?php if (!empty($glvl_id)) {?>
                                                <a href="../bed-forms/bed-accountingSHS.php?<?php echo 'stud_id=' . $id . '&glvl_id=' . $glvl_id; ?>"
                                                    type="button" class=" btn btn-warning text-sm p-2 mb-2"><i
                                                        class="fa fa-eye"></i>
                                                    Accounting Form
                                                </a>
                                                <?php } else {?>
                                                <a href="../bed-forms/bed-accountingSHS.php?<?php echo 'stud_id=' . $id; ?>"
                                                    type="button" class=" btn btn-warning text-sm p-2 mb-2"><i
                                                        class="fa fa-eye"></i>
                                                    Accounting Form
                                                </a>
                                                <?php }?>
                                                        
                                                        
                                                        

                                                        <?php if (!empty($glvl_id)) {?>
                                                        <a href="../bed-forms/all_formsSH.php?<?php echo 'stud_id=' . $id . '&glvl_id=' . $glvl_id; ?>"
                                                            type="button" class=" btn bg-maroon text-sm p-2 mb-2"><i
                                                                class="fa fa-eye"></i>
                                                            Reg Form
                                                        </a>
                                                        <?php } else {?>
                                                        <a href="../bed-forms/all_formsSH.php?<?php echo 'stud_id=' . $id; ?>"
                                                            type="button" class=" btn bg-maroon text-sm p-2 mb-2"><i
                                                                class="fa fa-eye"></i>
                                                            Reg Form
                                                        </a>
                                                        <?php }?>

                                                    </td>
                                                </tr>
                                                <?php
}
}

?>
                </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer"></div>
          <!-- /.card-footer-->
        </div>
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include '../../includes/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include '../../includes/script.php'; ?>
</body>

</html>