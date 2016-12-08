<form id="frmAnnouncement" method="post" action="<?php echo URL;?>t_announcement">
	<input id = "hidUrl" type="hidden" name="hidUrl" value = "<?php echo URL;?>" />
	<div class = 'divContainerMain'>
		<div class = 'divContainerMenu'>
			<?php require 'views/menu/'.$this->user_role.'/main_menu.php'; ?>
		</div>
		
		<div class = "divContainerDetails">
			<div class = "divContainerDetailsMenu">
				<?php require 'views/menu/'.$this->user_role.'/submenu.php'; ?>
			</div>
			<div id = "divGridReportContainer"></div>
			<div class = "divFilterReportContainerBottom">
				<input type="button" id = "btnExport" value = "Export to Excel"/>
			</div>
		</div>
		
		<div class = "divContainerInput">
			<div class = "divContinerInputTitle">Announcement</div>
			<div class = "divContinerInputDetails">
				<input type="hidden" name="hidAnnouncementId" />
				<label id="label">Subject</label>
				<input id="input" type="text" value="" placeholder=" Subject" name = "txtSubject"></option><br/>
				<label id="label">Message</label>
				<textarea id="input" name = "txtaMessage" placeholder="Message"></textarea><br />
				
				
			</div>
			<div class = "divContinerInputButton">
				<input id="Inputbtn" type="submit" name = "subForm" value = "Save as New"/>
				<input id="Inputbtn" type="submit" name = "subForm" value = "Update"/>
				<input id="Inputbtn" type="button" id = "btnClear" value = "Clear"/>
				<input id="Inputbtn" type="submit" name = "subForm" value = "Delete"/>
			</div>
		</div>
	</div>
	<div class="divClear"></div>
</form>

<hr />

