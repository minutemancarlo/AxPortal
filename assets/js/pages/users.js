$(document).ready(function() {


var table=$('#userTable').DataTable({
processing: true,
ajax: {
  url: "../controllers/userController.php",
  type: "POST",
  data: { action: 'select'},
  dataType: 'json',
  dataSrc: '',
  cache: false
},
columns: [
  { title: 'Id', data: "id", visible: false },
  { title: 'Name', data: "name", visible: true,
  render: function(data, type, row) {
    if (type === 'display' || type === 'filter') {
      return data.toLowerCase().replace(/(^|\s)\S/g, function(t) {
        return t.toUpperCase();
      });
    }
    return data;
  }
 },
  { title: 'Email', data: "email", visible: true },
  {
    title: 'Email Status',
    data: "is_verified",
    className: "text-center",
    visible: true,
    render: function(data, type, row) {
var stat = data == 1 ? 'Verified' : 'Not Verified';
return stat;
}
  },
  {
    title: 'Role',
    data: "role",
    className: "text-center",
    visible: true,
    render: function(data, type, row) {
      var stat;
      switch (data) {
    case '0':
        stat = 'Superuser';
        break;
    case '1':
        stat = 'Auxiliary';
        break;
    case '2':
        stat = 'End-User';
        break;
    case '3':
        stat = 'Councilor';
        break;
    default:
        stat = 'Unassigned';
}
      return stat;
    }
  },{
    title: 'Account Status',
    data: "is_active",
    className: "text-center",
    visible: true,
    render: function(data, type, row) {
var stat = data == 1 ? 'Active' : 'Deactivated';
return stat;
}
},
  {
    title: 'Action',
    data: null,
    orderable: false,
    searchable: false,
    className: "text-center",
    render: function (data, type, row) {
      var buttons = '<a class="btn btn-success btn-action-edit" data-id="' + row.Id + '" data-toggle="tooltip" data-placement="top" title="Assign Role"><i class="fas fa-pen"></i></a>';
      buttons += ' <button class="btn btn-primary btn-action-delete" data-id="' + row.Id + '" data-toggle="tooltip" data-placement="top" title="Activation"><i class="fa fa-user-check"></i></button>';

      return buttons;
    }
  }
],
order: [[1, 'desc']],
createdRow: function (row, data, dataIndex) {

if (data.is_active == '0') {
    // If is_active is 0, add a class to make the text red
    $(row).addClass('inactive-row');
}
}
});

//Role Selection
function selectRole() {
  return new Promise((resolve, reject) => {
    Swal.fire({
      title: "Select a Role",
      input: "select",
      inputOptions: {
        0: "Superuser",
        1: "Auxiliary",
        2: "End-User",
        3: "Councilor"
      },
      inputPlaceholder: "Select a Role",
      showCancelButton: true,
      inputValidator: (value) => {
        return new Promise((resolve) => {
          if (value != '') {
            resolve();
          } else {
            resolve("You need to select a role");
          }
        });
      }
    }).then((result) => {
      if (result.isConfirmed) {
        resolve(result.value);
      }
    });
  });
}



$(document).on('click', '.btn-action-edit', function() {
  var row = table.row($(this).closest('tr')).data();
  console.log(row.id);
  selectRole().then((selectedRole) => {
    console.log("Selected Role: " , selectedRole);
    var successCallback = function(response) {
        console.log(response);
        var data = JSON.parse(JSON.stringify(response));
        if (data.success) {
            Toast.fire({
                icon: 'success',
                title: data.message,
                timer: 2000,
            }).then(() => {
               location.reload();
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: data.message
            });
        }
    };

    var errorCallback = function(xhr, status, error) {
        var errorMessage = xhr.responseText;
        console.log('AJAX request error:', errorMessage);
        Toast.fire({
            icon: 'error',
            title: "Unexpected Error Occured. Please check browser logs for more info."
        });
    };
    var formData = new FormData();
    formData.append("action", "update");
    formData.append("role", selectedRole );
    formData.append("id", row.id );
    formData.append("is_active", row.is_active);
    $.ajax({
        url: '../controllers/userController.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: successCallback,
        error: errorCallback
    });




  }).catch((error) => {

    console.error(error);
  });
});

//Disable
$(document).on('click', '.btn-action-delete', function() {
  var row = table.row($(this).closest('tr')).data();
  Swal.fire({
  title: "Update Account Status",
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: "Activate",
  denyButtonText: `Deactivate`
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  var is_active;
  if (result.isConfirmed) {
    is_active=1;
  } else if (result.isDenied) {
    is_active=0;
  }
  return;
  var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
          Toast.fire({
              icon: 'success',
              title: data.message,
              timer: 2000,
          }).then(() => {
             location.reload();
          });
      } else {
          Toast.fire({
              icon: 'error',
              title: data.message
          });
      }
  };

  var errorCallback = function(xhr, status, error) {
      var errorMessage = xhr.responseText;
      console.log('AJAX request error:', errorMessage);
      Toast.fire({
          icon: 'error',
          title: "Unexpected Error Occured. Please check browser logs for more info."
      });
  };
  var formData = new FormData();
  formData.append("action", "update");
  formData.append("role", row.role );
  formData.append("id", row.id );
  formData.append("is_active", is_active);
  $.ajax({
      url: '../controllers/userController.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: successCallback,
      error: errorCallback
  });
});
});



});
