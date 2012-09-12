<div id="menu">
	<div class="alternatives-container">
		<% if (count($alternatives) > 0): %>
		<span id = "alternatives-label" class="label">Alternatives:</span>
		<% endif %>
		
		<div class = "alternatives">
			<% for($i = 1; $i <= count($alternatives); $i++): %>	
			<div class="alternative"><a href ="?action=alternative&id=<%=$alternatives[$i-1]%>"><%= $i %></a></div>
			<% endfor %>
		</div>
	</div>

	<div class="scales-container">
		<span id = "scales-label" class="label">Tuning:</span>
		<div class = "scales">{$scale}</div>
	</div>

	<div class="capo-container">
		<div id="capo-image"></div>
		<div class = "capo">{$capo}</div>
	</div>

	<div id="rating-container">
		<div id="current_rating" class = "rating"></div>
		<div id="my_rating" class = "rating"></div>
		<span id = "num_ratings" class = "label">{$num_ratings} <% if ($num_ratings == 1): %>rating<% else: %>ratings<% endif %>
		</span>
	</div>
</div>