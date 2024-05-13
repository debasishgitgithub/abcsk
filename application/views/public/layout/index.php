<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>[DISPLAY_TABLE_NAME]</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>


  <section class="content">
    <div class="container-fluid">
      <?= get_message(); ?>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-info">
        <div class="card-header py-2" data-card-widget="collapse">
          <span class="card-title font-weight-bold"><i class="fa fa-upload"></i> &nbsp; [DISPLAY_TABLE_NAME] list</span>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" title="" data-toggle="tooltip" data-original-title="Collapse">
              <i class="fas fa-plus"></i></button>
          </div>
        </div>
        <div class="card-body table-responsive p-2" style="display: block;">
          <table class="table table-striped text-center " id="data_table">
            <thead>
              <tr>
                <th style="width: 1%">S/L</th>
                [DATATABLE_HEADER]
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>


        </div>
      </div>
  </section>

  <!-- File modal -->
  <div class="modal fade" id="file_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">File list</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="file_append_place">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- add modal  -->
  <div class="modal fade" id="add_[TABLE_NAME]_modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add [DISPLAY_TABLE_NAME]</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="add_[TABLE_NAME]_form">
            <div class="row">
            
              [FORM_GROUP_ELEMENT_INSERT]
            
            </div>
          </form>
        </div>
        <div class="modal-footer d-block">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary float-right" id="[TABLE_NAME]_save_btn">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- update modal  -->
  <div class="modal fade" id="update_[TABLE_NAME]_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update [DISPLAY_TABLE_NAME]</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="[TABLE_NAME]_id" id="[TABLE_NAME]_id" value="">
          <form id="update_[TABLE_NAME]_form">
            <div class="row">
              
              [FORM_GROUP_ELEMENT_UPDATE]

            </div>
          </form>
        </div>
        <div class="modal-footer d-block">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary float-right" id="[TABLE_NAME]_update_btn">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <button class="btn btn-danger fix-add-btn" id="add_[TABLE_NAME]_btn" title="Add [DISPLAY_TABLE_NAME]"><i class="fa fa-plus"></i></button>
</div>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {
    var table = $("#data_table").DataTable({
      responsive: true,
      autoWidth: false,
      serverSide: false,
      processing: true,
      ajax: {
        url: '<?= base_url("[TABLE_NAME]_controller/get_all") ?>',
        type: 'GET',
        dataSrc: function(d) {
          if (d.code == 200) {
            return d.data.map((v, i) => {

              let all_buttons = {
                edit: `<button class="btn btn-sm btn-primary" id="edit_[TABLE_NAME]_btn" data-id="${v.[AUTO_FIELD_NAME]}"  
                                    data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>`,
                delete: `<button class="btn btn-sm btn-danger" id="delete_[TABLE_NAME]_btn" data-id="${v.[AUTO_FIELD_NAME]}"  
                                    data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>`
              };

              // let status_badge = v.status == true ? `<span class="badge badge-success"><i class="fa fa-check"></i> Active </span>` : `<span class="badge badge-warning"><i class="fa fa-check"></i> Inactive </span>`;

              return [
                (i + 1),
                [DATASRC_FIELD]
                `<div class="btn-group btn-group-sm">${Object.values(all_buttons).join('')}</div>`
              ]
            });
          }
          return [];
        }
      },
    });

    // show file
    $('body').on('click', '.file_btn', function() {
      $("#file_modal").modal('show');
      get($(this).data('id')).then((data) => {
        let field_name = $(this).data('field_name');
        if (data.code == 200) {

          $("#file_modal #file_append_place").html('');
          const fileLink = data.data[field_name].map((v, i) => {
            return `<div><a href="${v}" target="_blank">${basename(v)}</a></div>`
          });

          $("#file_modal #file_append_place").append(fileLink.join(''));
        } else {
          toastr.error(data.message);
        }
      }).catch((err) => {
        toastr.error(err);
      });
    });

    // delete [TABLE_NAME]
    $("body").on('click', '#delete_[TABLE_NAME]_btn', function() {
      const [TABLE_NAME]_id = $(this).data('id');
      if (confirm('Are you sure ?')) {
        $.ajax({
          type: 'POST',
          url: `<?= base_url('[TABLE_NAME]_controller/delete/') ?>${[TABLE_NAME]_id}`,
          contentType: "application/json",
          processData: false,
          beforeSend: function() {
            $('.loading').fadeIn(200);
          },
          success: function(data) {
            $('.loading').fadeOut(200);
            if (data.code == 200) {
              table.ajax.reload();
              toastr.success(data.message);
            } else {
              toastr.error(data.message);
            }
          },
          error: function(xhr, status, error) {
            $('.loading').fadeOut(200);
            toastr.error('Error uploading file');
          }
        });
      }
    });

    // add [TABLE_NAME]
    $("body").on('click', '#add_[TABLE_NAME]_btn', function() {
      $("#add_[TABLE_NAME]_form").trigger('reset');
      $('#add_[TABLE_NAME]_modal').modal('show');
      let validator = addFormValidation.insert();
      validator.resetForm();
      $('.form-control').removeClass('is-invalid').removeClass('is-valid');
    });

    // save [TABLE_NAME] 
    $("body").on('click', '#[TABLE_NAME]_save_btn', function() {
      $("#add_[TABLE_NAME]_form").submit();
    });

    // insert [TABLE_NAME]
    $("#add_[TABLE_NAME]_form").submit(async function(e) {
      e.preventDefault();
      var thisForm = $(this);

      if (!thisForm.valid()) {
        return false;
      }

      let formData = new FormData(thisForm[0]);

      [BASE64_HANDLER_INSERT]

      $.ajax({
        url: `<?= base_url("[TABLE_NAME]_controller/save") ?>`,
        method: 'POST',
        contentType: false, // Important for FormData
        processData: false, // Important for FormData
        data: formData,
        // dataType: 'json',
        beforeSend: function() {
          $('.loading').show();
        },
        success: function(res) {
          $('.loading').hide();
          if (res.code == 200) {
            toastr.success(res.message);
            thisForm.trigger('reset');
            table.ajax.reload();
            $("#add_[TABLE_NAME]_modal").modal('hide');
          } else {
            toastr.error('Something went wrong!!');
          }
        },
        error: function() {
          $('.loading').hide();
          toastr.error('error. Please try again later!')
        }
      });

    });

    // edit [TABLE_NAME] for model open and fetch data
    $("body").on('click', '#edit_[TABLE_NAME]_btn', function() {
      $("#update_[TABLE_NAME]_form").trigger('reset');
      $('#update_[TABLE_NAME]_modal').modal('show');
      let validator = addFormValidation.update();
      validator.resetForm();
      $('.form-control').removeClass('is-invalid').removeClass('is-valid');

      get($(this).data('id')).then((data) => {
        if (data.code == 200) {
          [SET_FIELD_VALUE]
        } else {
          toastr.error(data.message);
        }
      }).catch((err) => {
        toastr.error(err);
      });
    });

    $("body").on('click', '#[TABLE_NAME]_update_btn', function() {
      $("#update_[TABLE_NAME]_form").submit();
    });

    // update [TABLE_NAME]
    $("#update_[TABLE_NAME]_form").submit(async function(e) {
      try {
        e.preventDefault();
        var thisForm = $(this);

        if (!thisForm.valid()) {
          return false;
        }

        let formData = new FormData(thisForm[0]);
        const [TABLE_NAME]_id = $("#[TABLE_NAME]_id").val();

       [BASE64_HANDLER_UPDATE]

        $.ajax({
          url: `<?= base_url("[TABLE_NAME]_controller/save/") ?>${[TABLE_NAME]_id}`,
          method: 'POST',
          contentType: false, // Important for FormData
          processData: false, // Important for FormData
          data: formData,
          // dataType: 'json',
          beforeSend: function() {
            $('.loading').show();
          },
          success: function(res) {
            $('.loading').hide();
            if (res.code == 200) {
              toastr.success(res.message);
              thisForm.trigger('reset');
              table.ajax.reload();
              $("#update_[TABLE_NAME]_modal").modal('hide');
            } else {
              toastr.error('Something went wrong!!');
            }
          },
          error: function() {
            $('.loading').hide();
            toastr.error('error. Please try again later!')
          }
        });
      } catch (error) {
        toastr.error(error);
      }
    });

    const addFormValidation = {
      all: function() {
        this.insert();
        this.update();
      },
      insert: function() {
        return this.validate("#add_[TABLE_NAME]_form", {
          [INSERT_VALIDATION]
        });
      },
      update: function() {
        return this.validate("#update_[TABLE_NAME]_form", {
          [UPDATE_VALIDATION]
        });
      },
      validate: function(element, rules) {
        return $(element).validate({
          rules,
          errorElement: "div",
          errorPlacement: function(error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
              error.insertAfter(element.next("label"));
            } else {
              error.insertAfter(element);
            }
          },
          highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
          }
        });
      }
    };

    function get(id) {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: `<?= base_url("[TABLE_NAME]_controller/get/") ?>${id}`,
          method: 'GET',
          dataType: 'json',
          beforeSend: function() {
            $('.loading').show();
          },
          success: function(res) {
            $('.loading').hide();
            resolve(res);
          },
          error: function() {
            $('.loading').hide();
            reject('Something is Wrong');
          }
        });
      })
    }


    // load jquery validation
    addFormValidation.all();
  });
</script>