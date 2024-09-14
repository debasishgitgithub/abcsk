<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Branch List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Branch</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <?= get_message(); ?>
      <!-- Default box -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title ">Title</h3>
        </div>
        <div class="card-body table-responsive p-2">
          <table class="table table-striped text-center " id="tbl_blogs">
            <thead>
              <tr>
                <th style="width: 1%">S/L</th>
                <th>Owner Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </section>

  <!-- /.content -->
</div>
<a href="<?= base_url("support_admin/save") ?>" class="btn btn-danger fix-add-btn " title="New Registration"><i class="fa fa-plus"></i></a>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {
    let table = $('#tbl_blogs').DataTable({
      responsive: true,
      autoWidth: false,
      serverSide: false,
      "ajax": {
        "url": "<?= base_url('support_admin/get_all') ?>",
        "type": "get",
        // "data": function(d) {
        //   d.registration_no = $('#registration_no').val();
        //   d.from = $('#from').val();
        //   d.to = $('#to').val();
        // },
        "dataSrc": function(d) {
          if (d.code == 200) {
            return d.data.map((v, i) => {
              // var dateObj = new Date(v.datetime);
              // var yyyy = dateObj.getFullYear();
              // var mm = String(dateObj.getMonth() + 1).padStart(2, '0');
              // var dd = String(dateObj.getDate()).padStart(2, '0');
              let action_btns = {
                'edit': `<a href="<?= base_url("support_admin/save/"); ?>${v.id}" class="btn btn-primary" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>`,
                'delete': `<button class="btn btn-danger dlt_blog" data-id="${v.id}"  title="Delete" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>`
              };
              return [
                ++i,
                v.full_name,
                v.username,
                v.email,
                v.status == 'ACTIVE' ? `<span class="badge badge-success">Active</span>` : `<span class="badge badge-warning">Inactive</span>`,
                `<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">${Object.values(action_btns).join('')}</div>`
              ];
            });
          } else if (d.code == 203) {
            toastr.error(d.message);
          }
          return [];
        },
      }
    });

    // $('body').on('click', '.dlt_blog', function() {
    //   const blog_id = $(this).data('id');
    //   if (confirm('Are you Sure ?')) {
    //     $.ajax({
    //       url: `<?= base_url("blog/delete/") ?>${blog_id}`,
    //       type: 'post',
    //       dataType: 'json',
    //       beforeSend: function() {
    //         $('.loading').show();
    //       },
    //       success: function(data) {
    //         $('.loading').hide();
    //         if (data.code == 200) {
    //           toastr.success(data.message);
    //           table.ajax.reload();
    //         } else {
    //           toastr.error(data.message);
    //         }
    //       },
    //       error: function() {
    //         $('.loading').hide();
    //         toastr.error("Something is wrong");
    //       }
    //     });
    //   }
    // });

  });
</script>