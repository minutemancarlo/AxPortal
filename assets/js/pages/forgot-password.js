$(document).ready(function() {
  $('#resetForm').on('submit', function() {
    event.preventDefault();
    var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
        Swal.fire({
          title: 'Password Reset Sent',
          text: data.message,
          icon: 'info',
          showCancelButton: false,
          confirmButtonText: 'Okay'
        })
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
    var formData = $(this).serialize();
    loadContent('../controllers/resetPassword.php', formData, successCallback, errorCallback);
  });
});
