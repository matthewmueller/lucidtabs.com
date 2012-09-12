<div class="container">
	
	<div id="tab">
		<div id="tab_name">
			<div class="tab_search" style="display:{$find}">	
				<span class="Search label">Song Name:</span>
				<div class = "Master_Search box">
					<input type="text" autocomplete="off" class = "Search input" />
					<div class="Search results">
						<div class="Search create">Create New Tab</div>
					</div>
				</div>
			</div>
			<div class="tab_new_container" style ="display:{$new}">
				<span class="label">Song:</span>
				<input type="text" class="tab_new_song">
				<span class="label">Artist:</span>
				<input type="text" class="tab_new_artist">
			</div>
			
		</div>
		
		<div id="tab_key">
			<input type="text" value="e" class="tab_key" maxlength="2">
			<input type="text" value="B" class="tab_key" maxlength="2">
			<input type="text" value="G" class="tab_key" maxlength="2">
			<input type="text" value="D" class="tab_key" maxlength="2">
			<input type="text" value="A" class="tab_key" maxlength="2">
			<input type="text" value="E" class="tab_key" maxlength="2">
		</div>
		<div id="tab_capo">
			<input type="text" value="0" class="capo_input" maxlength="2">
		</div>
		<div class="tab_description">
			<span class="description-label label">Description:</span>
			<textarea class="tab_description_text"></textarea>
		</div>
		<div class="tab_create_button"></div>
	</div>
	
	<div class="tab_add_button" style="display:none"></div>
	<div class="tab_save_button" style="display:none"></div>
						
</div>