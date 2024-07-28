<?php
$courses_card_header = 'Create';

$full_name = '';
$short_name = '';
$duration_in_month = '';
$fees = '';
$details = '';
$status = '1';

$action_url = base_url('courses/save');

if (isset($courses_dtls) && !empty($courses_dtls->id)) {
  $courses_card_header = 'Edit';
  // pp($courses_dtls);
  $full_name = $courses_dtls->full_name;
  $short_name = $courses_dtls->short_name;
  $duration_in_month = $courses_dtls->duration_in_month;
  $fees = $courses_dtls->fees;
  $details = $courses_dtls->details;
  $status = $courses_dtls->status;

  $action_url = base_url("courses/save/{$courses_dtls->id}");
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Courses <?= $courses_card_header; ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Courses <?= $courses_card_header ?></li>
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
              <h3 class="card-title "><?= $courses_card_header; ?></h3>
            </div>
            <div class="card-body table-responsive p-3">

              <!-- create update form  -->
              <form method="post" action="<?= $action_url; ?>" enctype="multipart/form-data">
                <div class="row">

                  <div class="form-group col-md-6">
                    <label for="full_name">Full name</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('full_name', false); ?>" name="full_name" value="<?= set_value('full_name', $full_name) ?>">
                    <?= set_form_error('full_name'); ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="short_name">Short name</label>
                    <input type="text" class="form-control form-control-sm <?= set_form_error('short_name', false); ?>" name="short_name" value="<?= set_value('short_name', $short_name) ?>">
                    <?= set_form_error('short_name'); ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="duration_in_month">Duration in month</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('duration_in_month', false); ?>" name="duration_in_month" value="<?= set_value('duration_in_month', $duration_in_month) ?>">
                    <?= set_form_error('duration_in_month'); ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="fees">Fees</label>
                    <input type="number" class="form-control form-control-sm <?= set_form_error('fees', false); ?>" name="fees" value="<?= set_value('fees', $fees) ?>">
                    <?= set_form_error('fees'); ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <?php
                    $statusArr = ['1' => 'Active', '0' => 'Inactive'];
                    echo form_dropdown('status', $statusArr, set_value('status', $status), "class='form-control form-control-sm'");
                    ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="details">Details</label>
                    <textarea class="form-control form-control-sm <?= set_form_error('details', false); ?>" name="details"><?= set_value('details', $details) ?></textarea>
                    <?= set_form_error('details'); ?>
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