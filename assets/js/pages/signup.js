$(document).ready(function() {
  $('#signupForm').on('submit', function() {
    event.preventDefault();
    var password = $('#Password').val();
    var confirmPassword = $('#ConfirmPassword').val();

    // Check if password is at least 8 characters long
    if (password.length < 8) {
      $('#Password').addClass('is-invalid');
      $('.password-error').show();
      return false; // Prevent form submission
    } else {
      $('#Password').removeClass('is-invalid');
      $('.password-error').hide();
    }

    // Check if both password and confirm password fields are empty
    if (password === '' && confirmPassword === '') {
      $('#ConfirmPassword').addClass('is-invalid');
      $('.confirm-password-error').show();
      return false; // Prevent form submission
    }

    // Check if password and confirm password match
    if (password !== confirmPassword) {
      $('#ConfirmPassword').addClass('is-invalid');
      $('.confirm-password-error').show();
      return false; // Prevent form submission
    } else {
      $('#ConfirmPassword').removeClass('is-invalid');
      $('.confirm-password-error').hide();
    }

    var successCallback = function(response) {
      console.log(response);
      var data = JSON.parse(JSON.stringify(response));
      if (data.success) {
        Swal.fire({
          title: 'Verification email sent',
          text: data.message,
          icon: 'info',
          showCancelButton: false,
          confirmButtonText: 'Okay'
        }).then(function() {
          var email = $('#Email').val();
          var domain = email.substring(email.indexOf("@") + 1);
          window.open("https://" + domain, "_blank");
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
    var formData = $(this).serialize();
    loadContent('../controllers/signupController.php', formData, successCallback, errorCallback);

  });

});
