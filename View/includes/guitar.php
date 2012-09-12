<% foreach ($guitars as $guitar): %>
<div class="template">
	<div class="leftUp"></div>
	<div class="header">
		<span class="header-title"><%= $guitar['title'] %></span>
	</div>
	<div class="rightUp"></div>
	<div class="content-container">
		<div class="content">
			
			<% foreach ($guitar['board']['Struts'] as $strut): %>
			<div class="strut" style="
				left:<%= $strut['left']*15+38 %>px;
				top:<%=$strut['top']*30+30%>px;
			">
				<%= $strut['input']; %>
			</div>
			<% endforeach %>
			
			
			<% foreach ($guitar['board']['Stretchables'] as $stretchable): %>
			
			<div class="stretchable" style="
				left:<%= $stretchable['left']*15+8 %>px;
				top:<%= $stretchable['top']*30+30 %>px;
				width:<%=$stretchable['span']*15+8%>px;
			">
				<%= $stretchable['input'] %>
			</div>
			
			<% endforeach %>

		</div>
	</div>
	<div class="closure">
		<div class="leftDown"></div>
		<div class="footer"></div>
		<div class="rightDown"></div>
	</div>
</div>
<% endforeach %>
