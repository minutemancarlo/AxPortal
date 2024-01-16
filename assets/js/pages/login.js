function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
  $(document).ready(function() {


    $('#loginForm').submit(function(event) {
      event.preventDefault();
      var rememberMe = $("#check1").is(":checked");
      if (rememberMe) {
          setCookie("rememberMe", "true", 7); // Expires in 7 days
          setCookie("Email", $('#email').val(), 7);
          setCookie("Password", $('#password').val(), 7);
        } else {
          // Remove the "Remember me" cookie if not checked
          setCookie("rememberMe", "", -1); // Expire the cookie immediately
          setCookie("Email", "", -1);
          setCookie("Password", "", -1);
        }
          var successCallback = function(response) {

              var data = JSON.parse(JSON.stringify(response));
            if (data.success) {
              Toast.fire({
                icon: 'success',
                title: data.message,
                timer: 2000,
              }).then(() => {
                // window.location.href = window.origin+'/lms/admin';
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
           var formData = $(this).serialize();
          loadContent('../controllers/loginController.php', formData, successCallback, errorCallback);
      });

      $("#togglePassword").on("click", function() {
       var passwordInput = $("#password");
       var eyeIcon = $("#eyeIcon");

       if (passwordInput.attr("type") === "password") {
           passwordInput.attr("type", "text");
           eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
       } else {
           passwordInput.attr("type", "password");
           eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
       }
   });

   var rememberMe = getCookie("rememberMe");
   if (rememberMe === "true") {
     // If the cookie is set, check the checkbox
     $("#check1").prop("checked", true);
     console.log(getCookie("Email"));
     $("#email").val(getCookie("Email"));
     $("#password").val(getCookie("Password"));

   }
});




  function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1, c.length);
      }
      if (c.indexOf(nameEQ) == 0) {
        return c.substring(nameEQ.length, c.length);
      }
    }
    return null;
  }
