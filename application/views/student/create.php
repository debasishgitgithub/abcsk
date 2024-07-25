<?php
$student_card_header = 'Register';
$title = '';
$short_content = '';
$content = '';
$category_id = '';
$status = '1';
$action_url = base_url('student/register');

// if (isset($blg_dtls) && !empty($blg_dtls->id)) {
//   $student_card_header = 'Update';
//   $title = $blg_dtls->title;
//   $short_content = $blg_dtls->short_content;
//   $content = $blg_dtls->content;
//   $category_id = $blg_dtls->category_id;
//   $status = $blg_dtls->status;
//   $action_url = base_url("blog/save/{$blg_dtls->id}");
// }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Blank Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= portal_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Blog</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <!-- alert message show here  -->
      <?= get_message(); ?>

      <div class="row">
        <div class="col-md-12">

          <!-- Default box -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title "><?= $student_card_header; ?></h3>
            </div>
            <div class="card-body table-responsive p-3">
              <!-- create update form  -->
              <form method="post" action="<?= $action_url; ?>" enctype="multipart/form-data">

                <div class="form-group">
                  <label for="first_name">First name</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('first_name', false); ?>" name="first_name"  value="<?= set_value('first_name', $first_name) ?>">
                  <?= set_form_error('first_name'); ?>
                </div>

                <div class="form-group">
                  <label for="last_name">Last name</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('last_name', false); ?>" name="last_name"  value="<?= set_value('last_name', $last_name) ?>">
                  <?= set_form_error('last_name'); ?>
                </div>
                <div class="form-group">
                  <label for="father_name">Father's name</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('father_name', false); ?>" name="father_name"  value="<?= set_value('father_name', $father_name) ?>">
                  <?= set_form_error('father_name'); ?>
                </div>
                <div class="form-group">
                  <label for="mobile_no">Mobile No</label>
                  <input type="number" class="form-control form-control-sm <?= set_form_error('mobile_no', false); ?>" name="mobile_no"  value="<?= set_value('mobile_no', $mobile_no) ?>">
                  <?= set_form_error('mobile_no'); ?>
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('email', false); ?>" name="email"  value="<?= set_value('email', $email) ?>">
                  <?= set_form_error('email'); ?>
                </div>
                <div class="form-group">
                  <label for="city">City</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('city', false); ?>" name="city"  value="<?= set_value('city', $city) ?>">
                  <?= set_form_error('city'); ?>
                </div>

                <div class="form-group">
                  <label for="category_name_id">State</label>
                  <?php
                  $state_list = ['WB' => 'West Bengal'];
                  $error_class = set_form_error('state', false);
                  echo form_dropdown("state", $state_list, set_value('state', $state), "class='form-control form-control-sm {$error_class}'");
                  echo set_form_error('state');
                  ?>
                </div>

                <div class="form-group">
                  <label for="category_name_id">Courses</label>
                  <?php
                  $courses_list = ['' => 'select couses'] + array_column($courses_list, 'short_name', 'id');
                  $error_class = set_form_error('course_id', false);
                  echo form_dropdown("course_id", $courses_list, set_value('course_id', $course_id), "class='form-control form-control-sm {$error_class}'");
                  echo set_form_error('course_id');
                  ?>
                </div>

              

                <div class="form-group">
                  <label for="status">Status</label>
                  <?php
                  $statusArr = ['0' => 'Inactive', '1' => 'Active'];
                  echo form_dropdown('status', $statusArr, set_value('status', $status), "class='form-control form-control-sm'");
                  ?>
                </div>

                <button type="submit" class="btn btn-primary float-right m-2">Submit</button>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>

  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {


  });
</script>