$(document).ready(function() {
  // $('#studentsTable').DataTable().ajax.reload();

  $("#projects").change(function(){
    var selectedValue = $(this).val();
    if (selectedValue==5) {
     $("#otherproject").removeAttr("hidden");
    }else{
    $("#otherproject").attr("hidden", true);
    $("#otherprojectdescription").val('');
    }
  });
  $("#service").change(function(){
    var selectedValue = $(this).val();
    if (selectedValue==8) {
     $("#otherjob").removeAttr("hidden");
    }else{
    $("#otherjob").attr("hidden", true);
    $("#otherjobdescription").val('');
    }
  });
  getRequestStatus();



  function getRequestStatus(){
    $.ajax({
        url: '../controllers/requestController.php',
        type: 'POST',
        data: {
      action: 'select-per-user-request'
  },
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            var data = JSON.parse(JSON.stringify(response));
            $("#approvalCount").html(data.Pending);
            $("#pendingCount").html(data.Pending);
            $("#auxiliaryCount").html(data.Auxiliary);
            $("#councilorCount").html(data.Councilor);
            $("#finalApprovalCount").html(data.FinalApproval);
            $("#approvedCount").html(data.Approved);
            $("#rejectedCount").html(data.Rejected);

            // if (data.success) {
            //     // Show the session expired prompt using SweetAlert2
            //     clearInterval(timer);
            //     Swal.fire({
            //         title: 'Session Expired!',
            //         text: data.message,
            //         icon: 'warning',
            //         showCancelButton: false,
            //         confirmButtonText: 'Confirm'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             // Reload the page
            //             location.reload();
            //         }
            //     });
            // }
        }
    });
  }


  $('#requestForm').submit(function(event) {
    event.preventDefault();
    var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
        Swal.fire({
          title: "Submitted!",
          text: "Request On Queued!",
          icon: "success"
        }).then(() => {
          location.reload();
        });
      } else {
        Swal.fire({
          title: "Error!",
          text: data.message,
          icon: "error"
        });
      }
    };

    var errorCallback = function(xhr, status, error) {
      var errorMessage = xhr.responseText;
      console.log('AJAX request error:', errorMessage);
      Swal.fire({
        title: "Error!",
        text: data.message,
        icon: "error"
      });
    };
    var formData = new FormData(this);
    formData.append("action", "insert");
    $.ajax({
      url: '../controllers/requestController.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: successCallback,
      error: errorCallback
    });
  });

  var table=$('#requestsTable').DataTable({
  processing: true,

  ajax: {
    url: "../controllers/requestController.php",
    type: "POST",
    data: { action: 'select-all-requests-user'},
    dataType: 'json',
    dataSrc: '',
    cache: false
  },
  columns: [
    { title: 'Request ID', data: "rid", visible: true,
    render: function(data, type, row, meta) {
        return '<a href="#" class="request_id text-primary"  data-id="' + data + '">' + data + '</a>';
      }
    },
    { title: 'Service Requested', data: "project_name", visible: true},
    { title: 'Job Requested', data: "category_name", visible: true},
    { title: 'Is_rejected', data: "is_rejected", visible: false},
    { title: 'Approved By', data: "status_name", visible: true},
    { title: 'Next Approver', data: "next_approver_name", visible: true},
    {
    title: 'Status',
    data: 'level',
    className: 'text-center',
    visible: true,
    render: function (data, type, row) {
      let badgeClass, statusText;

      if (data == 0 && row.is_rejected == 0) {
        badgeClass = 'bg-warning text-secondary'; // Bootstrap warning color for "Pending" status
        statusText = 'Pending';
      } else if (data==4){
        badgeClass = 'bg-success'; // Bootstrap success color for "In-Progress" status
        statusText = 'Approved';
      } else if (row.is_rejected == 1) {
        badgeClass = 'bg-danger'; // Bootstrap danger color for "Rejected" status
        statusText = 'Rejected';
      }else {
        badgeClass = 'bg-primary'; // Bootstrap success color for "In-Progress" status
        statusText = 'In-Progress';
      }

      // Creating a badge with Bootstrap classes
      return `<span class="badge ${badgeClass}">${statusText}</span>`;
    }
  },
    { title: 'Created On', data: "created_on", visible: true},
    // { title: 'Updated On', data: "updated_on", visible: true},
  ],
  // order: [[1, 'desc']],
  createdRow: function (row, data, dataIndex) {
    // if (data.is_rejected == '1') {
    //     $(row).addClass('text-danger');
    // }

  }
  });


  $('#requestsTable tbody').on('click', '.request_id', function () {
    var data = $(this).data('id');
    var closestRow = $(this).closest('tr');
    var table = $('#requestsTable').DataTable();
    var rowData = table.row(closestRow).data();

    $('#requestModal').modal('show');

    $('#requestModal_id').html(data);
    $('#requestFormUpdate').find('select[name="project_id"]').val(rowData.project_id);
    $('#requestFormUpdate').find('select[name="job_id"]').val(rowData.job_id);
    $('#requestFormUpdate').find('textarea[name="description"]').val(rowData.description);
    $('#requestFormUpdate').find('textarea[name="jobdescription"]').val(rowData.jobdescription);
    $('#requestFormUpdate').find('textarea[name="projectdescription"]').val(rowData.projectdescription);
    $('#requestFormUpdate').find('input[name="level"]').val(rowData.level);
    var createdDate = new Date(rowData.created_on);

// Format the date as "MMMM DD, YYYY h:mm:ss A"
var formattedDate = createdDate.toLocaleString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    second: 'numeric',
    hour12: true
});
  $('#request_timeline ul').append('<li class="timeline-item mb-5">' +
    '<span class="timeline-icon bg-success">' +
    '<i class="fas fa-info text-white fa-sm fa-fw"></i>'+
    '</span>'+
    '<h5 class="fw-bold">Request Created</h5>'+
    '<p class="text-muted mb-2 fw-bold">'+createdDate+'</p>'+
    '<p class="text-muted">'+
    '</p>'+
  '</li>');

    if(rowData.level==4 || rowData.is_rejected==1){
      $('#requestFormUpdate').find('select[name="approval_status"]').attr("hidden", true);
      $('#approval_label').attr("hidden", true);
     }else{
        $('#requestFormUpdate').find('select[name="approval_status"]').removeAttr("hidden");
        $('#approval_label').removeAttr("hidden");
    }
    var approvalStatusArray = ["Auxiliary Approval", "Councilor Approval", "Auxiliary Final Approval", "Approved"];
    var approvalStatusIcon = ["check", "times", "sync"];
    var statusColor = ['secondary','success','danger'];


    for (var i = 1; i <= 4; i++) {
      $('#request_timeline ul').append('<li class="timeline-item mb-5">' +
        '<span class="timeline-icon bg-'+ (rowData.is_rejected == 1 ? statusColor[2] : (rowData.level > i-1 ? statusColor[1] : statusColor[0]))  +'">' +
        '<i class="fas fa-'+ (rowData.is_rejected == 1 ? approvalStatusIcon[1] : (rowData.level > i-1 ? approvalStatusIcon[0] : approvalStatusIcon[2])) +' text-white fa-sm fa-fw"></i>' +
        '</span>' +
        '<h5 class="fw-bold">'+ approvalStatusArray[i-1] +'</h5>' +
        '<p class="text-muted mb-2 fw-bold"></p>' +
        '<p class="text-muted">' +
        '</p>' +
        '</li>'
      );
    }




  });

  $('#requestModal').on('hidden.bs.modal', function () {
    $('#request_timeline ul').empty();
});

  $('#requestFormUpdate').submit(function(event) {
    event.preventDefault();
    var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
        Swal.fire({
          title: "Updated!",
          text: "Request Status Updated!",
          icon: "success"
        }).then(() => {
          location.reload();
        });
      } else {
        Swal.fire({
          title: "Error!",
          text: data.message,
          icon: "error"
        });
      }
    };

    var errorCallback = function(xhr, status, error) {
      var errorMessage = xhr.responseText;
      console.log('AJAX request error:', errorMessage);
      Swal.fire({
        title: "Error!",
        text: data.message,
        icon: "error"
      });
    };
    var formData = new FormData(this);
    formData.append("action", "update");
    formData.append("rid", $('#requestModal_id').html());
    $.ajax({
      url: '../controllers/requestController.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: successCallback,
      error: errorCallback
    });
  });

  var timer = setInterval(function() {
      getRequestStatus();
  }, 10000); // 10 seconds interval

});