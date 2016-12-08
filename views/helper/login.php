<form action="<?php echo URL;?>h_login" method="post">
	<div class="login">
		<img id="star"src="public/images/star2.png"><br/><br/>
		<label id="label">Username</label><input  id="input" type="text" name="txtLogin" /><br />
		<label id="label">Password</label><input id="input" type="password" name="txtPassword" /><br />
		<label id="label"></label><input style="height:40px; width:100px; border-radius:5px;" type="submit" name = "subForm" value = "Login"/><br />
		<label>&nbsp;</label><p class = "message_info"><?php echo $this->message_info;?></p>
	</div>
</form>