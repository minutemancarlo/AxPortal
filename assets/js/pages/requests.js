$(document).ready(function() {
  // $('#studentsTable').DataTable().ajax.reload();
 $.fn.dataTable.ext.errMode = 'throw';
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

  $('#approval_status').change(function () {
             if ($(this).val() === '0') {
               $("#reject_description_container").attr("hidden", false);
               $("#reject_description").attr("disabled", false);
               $("#reject_description").val('');
             }else{
               $("#reject_description_container").attr("hidden", true);
               $("#reject_description").attr("disabled", true);
               $("#reject_description").val('');
             }
         });


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
            // $("#approvalCount").html(data.Pending);
            $("#pendingCount").html(data.Pending);
            $("#auxiliaryCount").html(data.Auxiliary);
            $("#councilorCount").html(data.Councilor);
            // $("#finalApprovalCount").html(data.FinalApproval);
            $("#approvedCount").html(data.FinalApproval);
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

      var loadingSwal;

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
              text: "An error occurred during the request.",
              icon: "error"
          });
      };

      var formData = new FormData(this);
      formData.append("action", "insert");

      // Use willOpen callback to customize behavior before Swal is displayed
      loadingSwal = Swal.fire({
          title: 'Loading...',
          allowOutsideClick: false,
          showCancelButton: false,
          showConfirmButton: false,
          willOpen: () => {
              Swal.showLoading();
          }
      });

      $.ajax({
          url: '../controllers/requestController.php',
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          success: function(response) {
              successCallback(response);
          },
          error: function(xhr, status, error) {
              errorCallback(xhr, status, error);
          },
          complete: function() {
              // Close Swal in the complete callback if needed
              if (loadingSwal) {
                  loadingSwal.close();
              }
          }
      });
  });

  let count=0;
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
    { title: 'Name', data: "name", visible: false},
    { title: 'reject_description', data: "reject_description", visible: false},
    { title: 'created_on', data: "created_on", visible: false},
    { title: 'feedback', data: "feedback", visible: false},
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
      } else if (data==3){
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
  order: [[7, 'desc']],
  createdRow: function (row, data, dataIndex) {

      var statusColumn = $('td:eq(5)', row);
      var statusHtml = $('span', statusColumn).html();

switch ($("#roleid").val()) {
  case "0":
    if (statusHtml=="Pending" || statusHtml=="In-Progress"    ) {
      count++;
    }
    break;
  case "1":
    if (statusHtml=="Pending" || statusHtml=="In-Progress") {
      count++;
    }
    break;
  case "3":
    if (statusHtml=="Pending" || statusHtml=="In-Progress") {
      count++;
    }
    break;
}
  $("#approvalCount").html(count);
  }
  });



  $('#requestsTable tbody').on('click', '.request_id', function () {
    var data = $(this).data('id');
    var closestRow = $(this).closest('tr');
    var table = $('#requestsTable').DataTable();
    var rowData = table.row(closestRow).data();

    $('#requestModal').modal('show');

    $('#requestModal_id').html(data);
    $('#r_name').html(rowData.name);
    var createdDate = new Date(rowData.created_on);

// Format the date as "MMMM DD, YYYY h:mm:ss A"
var formattedDate = createdDate.toLocaleString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
});
    $('#r_date').html(formattedDate);
    $('#requestFormUpdate').find('select[name="project_id"]').val(rowData.project_id);
    $('#requestFormUpdate').find('select[name="job_id"]').val(rowData.job_id);
    $('#requestFormUpdate').find('textarea[name="description"]').val(rowData.description);
    $('#requestFormUpdate').find('textarea[name="jobdescription"]').val(rowData.jobdescription);
    $('#requestFormUpdate').find('textarea[name="projectdescription"]').val(rowData.projectdescription);
    $('#requestFormUpdate').find('input[name="level"]').val(rowData.level);
    $('#requestFormUpdate').find('textarea[name="reject_description"]').val(rowData.reject_description);
    $('#requestFormUpdate #requestFeedback').find('textarea[name="feedback"]').val(rowData.feedback);
    $('#feedback').val(rowData.feedback);

    // feedbackLabel
    // feedback
    // submitBtn

    if(r=="2"){  // if user
      if(rowData.level=="0" && rowData.is_rejected=="0"){
        $('#divFeedback').prop('hidden',true); // feedback div
      }else if(rowData.level=="3" || rowData.is_rejected=="1"){
        if(rowData.feedback===null || rowData.feedback==''){
          $("#feedback").attr("disabled",false); //textarea
          $('#submitBtn').prop('hidden',false); //button
          $('#divFeedback').prop('hidden',false); // feedback div
        }else{
          $("#feedback").attr("disabled",true); //textarea
          $('#submitBtn').prop('hidden',true); //button
          $('#divFeedback').prop('hidden',false); // feedback div
        }
      }else{
        $('#divFeedback').prop('hidden',true); // feedback div
      }
    }else{
      $("#feedback").attr("disabled",true); //textarea
      $('#submitBtn').prop('hidden',true); //button
      if(rowData.level=="0" && rowData.is_rejected=="0"){
        $('#divFeedback').prop('hidden',true); // feedback div
      }else if(rowData.level=="3" || rowData.is_rejected=="1"){
        if(rowData.feedback===null || rowData.feedback==''){
          $('#divFeedback').prop('hidden',true); // feedback div
        }else{
          $('#divFeedback').prop('hidden',false); // feedback div
        }
      }else{
        if(rowData.feedback===null || rowData.feedback==''){
          $('#divFeedback').prop('hidden',true); // feedback div
        }else{
          $('#divFeedback').prop('hidden',false); // feedback div
        }
      }
    }
    // $("#feedback").attr("disabled",false);
    // $('#submitBtn').prop('hidden',true);
    // $("#feedbackLabel").prop("hidden",false);


  //   if(r=="2"){ //if user
  //     $("#feedback").attr("disabled",false);
  //     if(rowData.feedback!=''){
  //
  //       $('#feedback').attr('disabled',true);
  //       $('#submitBtn').prop('hidden',true);
  //
  //     }else{
  //       $('#feedback').attr('disabled',false);
  //       $('#submitBtn').attr('hidden',false);
  //     }
  //   if(willShow){
  //     $("#requestFeedback").prop("hidden",true);
  //     $("#feedback").prop("hidden",true);
  //     $("#submitBtn").prop("hidden",true);
  //     $("#feedbackLabel").prop("hidden",true);
  //   }else{
  //     $("#requestFeedback").prop("hidden",false);
  //     $("#feedback").prop("hidden",false);
  //     $("#submitBtn").prop("hidden",false);
  //     $("#feedbackLabel").prop("hidden",false);
  //   }
  // }else{
  //   $("#feedback").attr("disabled",true);
  //   $("#submitBtn").attr("hidden",true);
  // }
    // if(rowData.is_rejected==1||rowData.level==3){
    //   if($('#feedback').val()==''){
    //     $('#feedback').attr("disabled", false);
    //     $('#submitBtn').attr("hidden", false);
    //   }else{
    //     $('#feedback').attr("disabled", true);
    //     $('#submitBtn').attr("hidden", true);
    //   }
    //   $('#requestFeedback').show();
    // }else{
    //   $('#requestFeedback').hide();
    // }


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
    '<p class="text-muted mb-2 fw-bold">'+formattedDate+'</p>'+
    '<p class="text-muted">'+
    '</p>'+
  '</li>');
 $("#reject_description_container").attr("hidden", true);
    if(rowData.level==3 || rowData.is_rejected==1){
      $('#requestFormUpdate').find('select[name="approval_status"]').attr("hidden", true);
      $('#approval_label').attr("hidden", true);
      $('#submit').attr("hidden", true);
      if(rowData.is_rejected==1){
      $("#reject_description").attr("disabled", true);
      $("#reject_description_container").attr("hidden", false);
    }else{
      $("#reject_description_container").attr("hidden", true);
    }


     }else{
        $('#requestFormUpdate').find('select[name="approval_status"]').removeAttr("hidden");
        $('#approval_label').removeAttr("hidden");
        $('#submit').removeAttr("hidden");

    }
    var approvalStatusArray = ["Auxiliary", "Chancellor", "Auxiliary Final Approval", "Approved"];
    var approvalStatusIcon = ["check", "times", "sync"];
    var statusColor = ['secondary','success','danger'];
    let responseDataArray = [];
    $.ajax({
      url: '../controllers/requestController.php',
      type: 'POST',
      data: {action: "select-by-request", rid: $("#requestModal_id").html()}, // corrected selector
      dataType: 'json',
      success: function(response) {


        for (var i = 0; i < response.length; i++) {
           responseDataArray.push(response[i]);
       }
       for (var i = 1; i <= 3; i++) {
         // rowData.is_rejected == 1 && rowData.level > i ? statusColor[2] : (rowData.level>i-1? statusColor[1] )
         var createdDate = null;
         var isNull = false;
        if (responseDataArray[i - 1] && responseDataArray[i - 1].updated_on) {
            createdDate = new Date(responseDataArray[i - 1].updated_on);
            isNull = false;
        }else{
          createdDate = new Date(null);
          isNull = true;
        }

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
           '<span class="timeline-icon bg-'+ (rowData.is_rejected == 1 && rowData.level>i ? statusColor[2] : (rowData.level >= i ? statusColor[1] : (rowData.is_rejected== 1?statusColor[2]:statusColor[0])))  +'">' +
           '<i class="fas fa-'+ (rowData.is_rejected == 1 && rowData.level>i ? approvalStatusIcon[1] : (rowData.level >= i ? approvalStatusIcon[0] : (rowData.is_rejected == 1?approvalStatusIcon[1]:approvalStatusIcon[2]))) +' text-white fa-sm fa-fw"></i>' +
           '</span>' +
           '<h5 class="fw-bold">'+ approvalStatusArray[i-1] +'</h5>' +
           '<p class="text-muted mb-2 fw-bold">'+ (isNull?'':formattedDate) +'</p>' +
           '<p class="text-muted"> ' + (isNull?'':"Updated By: "+responseDataArray[i - 1].name) +
           '</p>' +
           '</li>'
         );

       }

      },
      error: function(xhr, status, error) {

      }
  });

  });



  // $('#requestModal').on('show.bs.modal', function (e) {
  //
  //
  //  });



  $('#requestModal').on('hidden.bs.modal', function () {
    $('#request_timeline ul').empty();
});

$('#requestFormUpdate').submit(function(event) {
  event.preventDefault();
  var loadingSwal;

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
          text: "An error occurred during the request.",
          icon: "error"
      });
  };

  var formData = new FormData(this);
  formData.append("action", "update");
  formData.append("rid", $('#requestModal_id').html());

  loadingSwal = Swal.fire({
      title: 'Loading...',
      allowOutsideClick: false,
      showCancelButton: false,
      showConfirmButton: false,
      willOpen: () => {
          Swal.showLoading();
      }
  });

  $.ajax({
      url: '../controllers/requestController.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function(response) {
          successCallback(response);
      },
      error: function(xhr, status, error) {
          errorCallback(xhr, status, error);
      },
      complete: function() {
          // Close Swal in the complete callback if needed
          if (loadingSwal) {
              loadingSwal.close();
          }
      }
  });
});

$("#submitBtn").click(function() {
  var loadingSwal;

  var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
          Swal.fire({
              title: "Updated!",
              text: "Request Feedback Updated!",
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
          text: "An error occurred during the request.",
          icon: "error"
      });
  };

  var formData = new FormData();
  formData.append("action", "feedbackUpdate");
  formData.append("rid", $('#requestModal_id').html());
formData.append("feedback", $('#feedback').val());
  loadingSwal = Swal.fire({
      title: 'Loading...',
      allowOutsideClick: false,
      showCancelButton: false,
      showConfirmButton: false,
      willOpen: () => {
          Swal.showLoading();
      }
  });

  $.ajax({
      url: '../controllers/requestController.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function(response) {
          successCallback(response);
      },
      error: function(xhr, status, error) {
          errorCallback(xhr, status, error);
      },
      complete: function() {
          // Close Swal in the complete callback if needed
          if (loadingSwal) {
              loadingSwal.close();
          }
      }
  });
});


var timer = setInterval(function() {
    getRequestStatus();
    count=0;
    getCurrentPage().then(function(currentPage) {
        table.ajax.reload(function () {
            table.page(currentPage).draw('page');
        });
    });

}, 10000);

function getCurrentPage() {
    return new Promise(function(resolve) {
        resolve(table.page());
    });
}// 10 seconds interval

});
