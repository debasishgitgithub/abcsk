<?php
$student_card_header = 'Register';

$first_name = '';
$last_name = '';
$father_name = '';
$mobile_no = '';
$email = '';
$city = '';
$state = '';
$aadhaar_no = '';
$pin = '';
$address = '';
$course_id = '';
$username = '';
$installation_type = 'installment';
$status = 'ACTIVE';
$course_start_date = '';
$course_end_date = '';
$isUsernameDisabled = "";

$action_url = base_url('student/register');

if (isset($student_dtls) && !empty($student_dtls->id)) {
  $student_card_header = 'Edit';

  $first_name = $student_dtls->first_name;
  $last_name = $student_dtls->last_name;
  $father_name = $student_dtls->father_name;
  $mobile_no = $student_dtls->mobile_no;
  $email = $student_dtls->email;
  $city = $student_dtls->city;
  $state = $student_dtls->state;
  $aadhaar_no = $student_dtls->aadhaar_no;
  $pin = $student_dtls->pin;
  $address = $student_dtls->address;
  $course_id = $student_dtls->course_id;
  $installation_type = $student_dtls->installation_type;
  $status = $student_dtls->status;
  $username = $student_dtls->username;
  $enrollment_id = $student_dtls->enrollment_id;
  $course_start_date = $student_dtls->course_start_date;
  $course_end_date = $student_dtls->course_end_date;
  $isUsernameDisabled = "disabled";

  $action_url = base_url("student/save/{$student_dtls->id}");
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Student <?= $student_card_header; ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Student <?= $student_card_header ?></li>
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
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="first_name">First name</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('first_name', false); ?>" name="first_name" value="<?= set_value('first_name', $first_name) ?>">
                    <?= set_form_error('first_name'); ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="last_name">Last name</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('last_name', false); ?>" name="last_name" value="<?= set_value('last_name', $last_name) ?>">
                    <?= set_form_error('last_name'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="father_name">Father's name</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('father_name', false); ?>" name="father_name" value="<?= set_value('father_name', $father_name) ?>">
                    <?= set_form_error('father_name'); ?>
                  </div>
                  <?php
                  if (isset($enrollment_id)) : ?>
                    <div class="form-group col-md-6">
                      <label for="enrollment_id">Enrollment Id</label>
                      <input type="text" class="form-control form-control-sm" name="enrollment_id" value="<?= $enrollment_id ?>" disabled>
                    </div>
                  <?php
                  endif; ?>
                  <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('username', false); ?>" name="username" value="<?= set_value('username', $username) ?>" <?= $isUsernameDisabled ?>>
                    <?= set_form_error('username'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('password', false); ?>" name="password" value="">
                    <?= set_form_error('password'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="mobile_no">Mobile No</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('mobile_no', false); ?>" name="mobile_no" value="<?= set_value('mobile_no', $mobile_no) ?>">
                    <?= set_form_error('mobile_no'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('email', false); ?>" name="email" value="<?= set_value('email', $email) ?>">
                    <?= set_form_error('email'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="aadhaar_no">Aadhaar No</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('aadhaar_no', false); ?>" name="aadhaar_no" value="<?= set_value('aadhaar_no', $aadhaar_no) ?>">
                    <?= set_form_error('aadhaar_no'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('city', false); ?>" name="city" value="<?= set_value('city', $city) ?>">
                    <?= set_form_error('city'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="pin">Pin no</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('pin', false); ?>" name="pin" value="<?= set_value('pin', $pin) ?>">
                    <?= set_form_error('pin'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="state">State</label>
                    <?php
                    $state_list = ['WB' => 'West Bengal'];
                    $error_class = set_form_error('state', false);
                    echo form_dropdown("state", $state_list, set_value('state', $state), "class='form-control form-control-sm {$error_class}'");
                    echo set_form_error('state');
                    ?>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="address">Address</label>
                    <textarea class="form-control form-control-sm <?= set_form_error('address', false); ?>" name="address"><?= set_value('address', $address) ?></textarea>
                    <?= set_form_error('address'); ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="installation_type">Installation type</label>
                    <?php
                    $installation_type_arr = ['one_time' => 'One time', 'installment' => 'Installment'];
                    $error_class = set_form_error('installation_type', false);
                    echo form_dropdown("installation_type", $installation_type_arr, set_value('installation_type', $installation_type), "class='form-control form-control-sm {$error_class}'");
                    echo set_form_error('installation_type');
                    ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="category_name_id">Courses</label>
                    <?php
                    $courses_list = ['' => 'select couses'] + array_column($courses_list, 'short_name', 'id');
                    $error_class = set_form_error('course_id', false);
                    echo form_dropdown("course_id", $courses_list, set_value('course_id', $course_id), "class='form-control form-control-sm {$error_class}' id='course_id'");
                    echo set_form_error('course_id');
                    ?>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="course_start_date">Course Start Date</label>
                    <input type="date" class="form-control form-control-sm <?= set_form_error('course_start_date', false); ?>" name="course_start_date" value="<?= set_value('course_start_date', $course_start_date) ?>" readonly>
                    <?= set_form_error('course_start_date'); ?>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="course_end_date">Course End Date</label>
                    <input type="date" class="form-control form-control-sm <?= set_form_error('course_end_date', false); ?>" name="course_end_date" value="<?= set_value('course_end_date', $course_end_date) ?>" readonly>
                    <?= set_form_error('course_end_date'); ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <?php
                    $statusArr = ['ACTIVE' => 'Active', 'INACTIVE' => 'Inactive'];
                    echo form_dropdown('status', $statusArr, set_value('status', $status), "class='form-control form-control-sm'");
                    ?>
                  </div>

                  <div class="form-group">
                    <label for="mp_admit_card">Select MP Admit Card</label>
                    <input type="file" class="form-control-file <?= set_form_error('mp_admit_card', false); ?>" name="mp_admit_card" multiple>
                    <?= set_form_error('mp_admit_card'); ?>
                  </div>

                  <div class="form-group">
                    <label for="profile_image">Select Profile image</label>
                    <input type="file" class="form-control-file <?= set_form_error('profile_image', false); ?>" name="profile_image" multiple>
                    <?= set_form_error('profile_image'); ?>
                  </div>

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
    $('body').on('change', '#course_id', function() {
      const course_id = $(this).val();
      let course_start_date_elem = $('input[name="course_start_date"]');
      let course_end_date_elem = $('input[name="course_end_date"]');

      if (course_id) {
        $.ajax({
          url: `<?= base_url("courses/get_session_date/") ?>${course_id}`,
          type: 'get',
          dataType: 'json',
          beforeSend: function() {
            $('.loading').show();
          },
          success: function(data) {
            $('.loading').hide();
            if (data.code == 200) {
              course_start_date_elem.val(data.data.course_start_date);
              course_end_date_elem.val(data.data.course_end_date);
            } else {
              course_start_date_elem.val('');
              course_end_date_elem.val('');
              toastr.error(data.message);
            }
          },
          error: function() {
            $('.loading').hide();
            toastr.error("Something is wrong");
          }
        });
      } else {
        course_start_date_elem.val('');
        course_end_date_elem.val('');
      }
    });

  });
</script>