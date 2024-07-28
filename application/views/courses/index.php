<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Courses List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Courses</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Modal -->
  <div class="modal fade" id="view_img_modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Blog images</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card card-primary">
            <div class="card-body">
              <div class="row appendPlaceImg">
                <!-- <div class="col-sm-2">
                  <a href="https://via.placeholder.com/1200/FFFFFF.png?text=1" data-toggle="lightbox" data-title="sample 1 - white" data-gallery="gallery">
                    <img src="https://via.placeholder.com/300/FFFFFF?text=1" class="img-fluid mb-2" alt="white sample">
                  </a>
                </div> -->
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Understood</button>
        </div>
      </div>
    </div>
  </div>

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
          <table class="table table-striped text-center " id="tbl_courses">
            <thead>
              <tr>
                <th style="width: 1%">S/L</th>
                <th>Full name</th>
                <th>Short name</th>
                <th>Duration (month)</th>
                <th>Fees</th>
                <th>Status</th>
                <th>Created On</th>
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
<a href="<?= base_url("courses/save") ?>" class="btn btn-danger fix-add-btn " title="New Courses"><i class="fa fa-plus"></i></a>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {
    let table = $('#tbl_courses').DataTable({
      responsive: true,
      autoWidth: false,
      serverSide: false,
      "ajax": {
        "url": "<?= base_url('courses/get_all') ?>",
        "type": "get",
        // "data": function(d) {
        //   d.registration_no = $('#registration_no').val();
        // },
        "dataSrc": function(d) {
          if (d.code == 200) {
            return d.data.map((v, i) => {
              // var dateObj = new Date(v.datetime);
              // var yyyy = dateObj.getFullYear();
              // var mm = String(dateObj.getMonth() + 1).padStart(2, '0');
              // var dd = String(dateObj.getDate()).padStart(2, '0');
              let action_btns = {
                'edit': `<a href="<?= base_url("courses/save/"); ?>${v.id}" class="btn btn-primary" title="Edit courses" data-toggle="tooltip"><i class="fas fa-edit"></i></a>`,
                'delete': `<button class="btn btn-danger dlt_courses" data-id="${v.id}"  title="Delete Courses" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></button>`
              };
              return [
                ++i,
                v.full_name,
                v.short_name,
                v.duration_in_month,
                v.fees,
                // `<button class="btn btn-success btn-sm view_img" data-id="${v.id}"  title="View Image" data-toggle="tooltip"><i class="fas fa-images"></i></button>`,
                parseInt(v.status) ? `<span class="badge badge-success">Active</span>` : `<span class="badge badge-warning">Inactive</span>`,
                v.created_at,
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


    $('body').on('click', '.dlt_courses', function() {
      const course_id = $(this).data('id');
      if (confirm('Are you Sure ?')) {
        $.ajax({
          url: `<?= base_url("courses/delete/") ?>${course_id}`,
          type: 'post',
          dataType: 'json',
          beforeSend: function() {
            $('.loading').show();
          },
          success: function(data) {
            $('.loading').hide();
            if (data.code == 200) {
              toastr.success(data.message);
              table.ajax.reload();
            } else {
              toastr.error(data.message);
            }
          },
          error: function() {
            $('.loading').hide();
            toastr.error("Something is wrong");
          }
        });
      }
    });

  });
</script>