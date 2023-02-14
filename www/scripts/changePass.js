$(document).ready(function(){
  
	// animate the form fields when they're focused
	$('input[type="password"]').focus(function(){
	  $(this).animate({
		width: '+=20px'
	  }, 200);
	});
	
	$('input[type="password"]').blur(function(){
	  $(this).animate({
		width: '-=20px'
	  }, 200);
	});
  
	// validate the form fields before submission
	function validateForm() {
	  var currentPassword = $('#password_current').val();
	  var newPassword = $('#password_new').val();
	  var confirmPassword = $('#password_new_again').val();
	  
	  if (currentPassword == '') {
		alert('Please enter your current password.');
		return false;
	  }
	  
	  if (newPassword == '') {
		alert('Please enter a new password.');
		return false;
	  }
	  
	  if (newPassword.length < 8) {
		alert('Your new password must be at least 8 characters long.');
		return false;
	  }
	  
	  if (confirmPassword == '') {
		alert('Please confirm your new password.');
		return false;
	  }
	  
	  if (newPassword != confirmPassword) {
		alert('Your new password and confirmation do not match.');
		return false;
	  }
	  
	  return true;
	}
	console.log("izadji")
  });
  