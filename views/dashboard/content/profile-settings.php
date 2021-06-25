<h3>Click on the button to generate your affiliate link, which you can share with your contacts</h3>
<div class="aff-login-form-container">
		<form id="aff_login_form"  class="aff_form"action="" method="post">
			<fieldset>
				<p>
					<label for="aff_user_Login">Enter Paypal Email</label>
					<input name="aff_user_paypal" id="aff_user_paypal" class="aff-required" type="text"/>
				</p>
				<hr>
				<p>
					<input type="hidden" name="aff_logout_nonce" value="<?php echo wp_create_nonce('aff-logout-nonce'); ?>"/>
                    <a href="http://localhost:8083/testing/wp-login.php?action='logout'"><h3><u>Log Out</u></h3></a>
				</p>
			</fieldset>
		</form>
	</div>
