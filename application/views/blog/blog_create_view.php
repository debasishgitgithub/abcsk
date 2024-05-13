<?php
$blog_card_header = 'Create';
$title = '';
$short_content = '';
$content = '';
$category_id = '';
$status = '1';
$action_url = base_url('blog/save');

if (isset($blg_dtls) && !empty($blg_dtls->id)) {
  $blog_card_header = 'Update';
  $title = $blg_dtls->title;
  $short_content = $blg_dtls->short_content;
  $content = $blg_dtls->content;
  $category_id = $blg_dtls->category_id;
  $status = $blg_dtls->status;
  $action_url = base_url("blog/save/{$blg_dtls->id}");
}

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
              <h3 class="card-title "><?= $blog_card_header; ?></h3>
            </div>
            <div class="card-body table-responsive p-3">
              <!-- create update form  -->
              <form method="post" action="<?= $action_url; ?>" enctype="multipart/form-data">

                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" class="form-control form-control-sm <?= set_form_error('title', false); ?>" name="title" placeholder="Blog Title" value="<?= set_value('title', $title) ?>">
                  <?= set_form_error('title'); ?>
                </div>

                <div class="form-group">
                  <label for="category_name_id">Select Category</label>
                  <?php
                  $category_list = ['' => 'select category'] + array_column($category_dtls, 'name', 'id');
                  $error_class = set_form_error('category_id', false);
                  echo form_dropdown("category_id", $category_list, set_value('category_id', $category_id), "class='form-control form-control-sm {$error_class}'");
                  echo set_form_error('category_id');
                  ?>
                </div>

                <div class="form-group">
                  <label for="short_content">Short Content</label>
                  <textarea class="form-control form-control-sm <?= set_form_error('short_content', false); ?>" name="short_content" rows="2"><?= set_value('short_content', $short_content) ?></textarea>
                  <?= set_form_error('short_content'); ?>
                </div>

                <div class="form-group">
                  <label for="content">Content</label>
                  <textarea class="form-control form-control-sm <?= set_form_error('content', false); ?>" name="content" rows="3"><?= set_value('content', $content) ?></textarea>
                  <?= set_form_error('content'); ?>
                </div>

                <div class="form-group">
                  <label for="blogimage">Select Images</label>
                  <input type="file" class="form-control-file <?= set_form_error('blogimage[]', false); ?>" name="blogimage[]" multiple>
                  <?= set_form_error('blogimage[]'); ?>
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