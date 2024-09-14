<?php
$branch_card_header = 'Register';

$first_name = '';
$last_name = '';
$phone_no = '';
$email = '';
$city = '';
$state = '';
$address = '';
$pin_no = '';
$pan_no = '';
$username = '';
$status = 'ACTIVE';
$isUsernameDisabled = "";

$action_url = base_url('support_admin/save');

if (isset($user_dtls) && !empty($user_dtls->id)) {
  $branch_card_header = 'Edit';

  $first_name = $user_dtls->first_name;
  $last_name = $user_dtls->last_name;
  $phone_no = $user_dtls->phone_no;
  $email = $user_dtls->email;
  $city = $user_dtls->city;
  $state = $user_dtls->state;
  $address = $user_dtls->address;
  $pin_no = $user_dtls->pin_no;
  $pan_no = $user_dtls->pan_no;
  $status = $user_dtls->status;
  $username = $user_dtls->username;
  $isUsernameDisabled = "disabled";
  $action_url = base_url("support_admin/save/{$user_dtls->id}");
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Branch <?= $branch_card_header; ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Branch <?= $branch_card_header ?></li>
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
              <h3 class="card-title "><?= $branch_card_header; ?></h3>
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
                    <label for="username">Username</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('username', false); ?>" name="username" value="<?= set_value('username', $username) ?>" <?=$isUsernameDisabled?>>
                    <?= set_form_error('username'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('password', false); ?>" name="password" value="">
                    <?= set_form_error('password'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="phone_no">Mobile No</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('phone_no', false); ?>" name="phone_no" value="<?= set_value('phone_no', $phone_no) ?>">
                    <?= set_form_error('phone_no'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('email', false); ?>" name="email" value="<?= set_value('email', $email) ?>">
                    <?= set_form_error('email'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="pan_no">Pan No</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('pan_no', false); ?>" name="pan_no" value="<?= set_value('pan_no', $pan_no) ?>">
                    <?= set_form_error('pan_no'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('city', false); ?>" name="city" value="<?= set_value('city', $city) ?>">
                    <?= set_form_error('city'); ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="pin_no">Pin no</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('pin_no', false); ?>" name="pin_no" value="<?= set_value('pin_no', $pin_no) ?>">
                    <?= set_form_error('pin_no'); ?>
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
                    <label for="status">Status</label>
                    <?php
                    $statusArr = ['ACTIVE' => 'Active', 'INACTIVE' => 'Inactive'];
                    echo form_dropdown('status', $statusArr, set_value('status', $status), "class='form-control form-control-sm'");
                    ?>
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


  });
</script>