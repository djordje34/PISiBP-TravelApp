<?php 
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('landing.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		// echo 'OK!'; validate stuff
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password_new' => array(
				'required' => true,
				'min' => 6
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_new'
			)
		));

		if($validation->passed()) {
			// change password
			if(!password_verify((Input::get('password_current')), $user->data()->password)) {
				echo 'Your current password is wrong.';
			} else {
				$user->update(array(
					'password' => password_hash(Input::get('password_new'), PASSWORD_BCRYPT)
				));
				Session::flash('home', 'Your password has been changed!');
				Redirect::to('landing.php');
			}
		} else {
			foreach($validation->errors() as $error) {
				echo $error, '<br>';
			}
		}
	}
}


?>

<form action="" method="post">
	<div class="field">
		<label for="password_current">Current Password</label>
		<input type="password" name="password_current" id="password_current">
	</div>
	<div class="field">
		<label for="password_new">New Password</label>
		<input type="password" name="password_new" id="password_new">
	</div>
	<div class="field">
		<label for="password_new_again">New Password Again</label>
		<input type="password" name="password_new_again" id="password_new_again">
	</div>

	<input type="submit" value="Change">
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>