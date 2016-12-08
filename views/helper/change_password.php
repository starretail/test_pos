<form id="frmChangePassword" method="post" action="<?php echo URL;?>h_change_password">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = 'divLoader'></div>
	<div class = 'divContainerMain'>
		<div class = 'divContainerMenu'>
			<?php require 'views/menu/'.$this->user_role.'/main_menu.php'; ?>
		</div>
		
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Change Password</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidUserProfileId" value = "<?php echo $this->user_profile_id; ?>" />
				<label>Username</label><input type="text" name="txtUserName" value = "<?php echo $this->user_name; ?>" readonly /><br />
				<label>Old Password</label><input type="password" name="txtOldPassword" /><br />
				<label>New Password</label><input type="password" name="txtNewPassword" /><br />
				<label>Confirm Password</label><input type="password" name="txtConfirmPassword" /><br />
			</div>
			<div class = "divContinerInputButton">
				<input type="submit" name = "subForm" value = "Change Password"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

