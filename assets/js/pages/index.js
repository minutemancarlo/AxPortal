$(document).ready(function() {


$.fn.dataTable.ext.errMode = 'throw';
  var jobTable=$('#serviceTable').DataTable({
  processing: true,
  "bLengthChange": false,
  "searching": false,
  "ordering": false,
  "paging": false,
  ajax: {
    url: "../controllers/indexController.php",
    type: "POST",
    data: { action: 'service'},
    dataType: 'json',
    dataSrc: '',
    cache: false
  },
  columns: [
    { title: 'Category', data: "project_name", visible: true},
    { title: 'For Approval', data: "for_approval_count", visible: false},
    { title: 'Pending', data: "pending_count", visible: true, className: 'text-center'},
    { title: 'In-Progress', data: "in_progress_count", visible: true, className: 'text-center'},
    { title: 'Approved', data: "approved_count", visible: true, className: 'text-center'},
    { title: 'Rejected', data: "rejected_count", visible: true, className: 'text-center'},
  ],
  createdRow: function (row, data, dataIndex) {
    // Additional row creation logic if needed
    if (data.project_name === 'Total') {
      $(row).hide();
      var rowData = jobTable.row(row).data();
      $('#approvalsCount').html(rowData.for_approval_count);
      $('#inprogressCount').html(rowData.in_progress_count);
      $('#approvedCount').html(rowData.approved_count);
      $('#rejectedCount').html(rowData.rejected_count);
    }

  }
  });

  var serviceTable=$('#jobTable').DataTable({
  processing: true,
  "bLengthChange": false,
  "searching": false,
  "ordering": false,
  "paging": false,
  ajax: {
    url: "../controllers/indexController.php",
    type: "POST",
    data: { action: 'jobs'},
    dataType: 'json',
    dataSrc: '',
    cache: false
  },
  columns: [
    { title: 'Category', data: "category_name", visible: true},
    { title: 'Pending', data: "pending_count", visible: true, className: 'text-center'},
    { title: 'In-Progress', data: "in_progress_count", visible: true, className: 'text-center'},
    { title: 'Approved', data: "approved_count", visible: true, className: 'text-center'},
    { title: 'Rejected', data: "rejected_count", visible: true, className: 'text-center'},

  ],
  createdRow: function (row, data, dataIndex) {
  }
  });


  var timer = setInterval(function() {
    serviceTable.ajax.reload();
    jobTable.ajax.reload();

  }, 10000);


});
