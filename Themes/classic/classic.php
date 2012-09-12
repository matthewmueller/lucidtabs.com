<% session_start(); %>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>{$title} | Lucid Tabs</title>
		{css}
	</head>
	<body>
		<div class="theme_classic_head">
			<div class="theme_classic_header">	
				<a href = "../Main/"><div class="theme_classic_logo"></div></a>
				<div class="theme_classic_search-box">
					{partial "Search"}
					<div class="theme_classic_search-button"></div>
				</div>
				<div class="theme_classic_login-box">
					<?php if (!isset($_SESSION['user_id'])): ?>
					<div class="theme_classic_login-btn {$login_btn}"></div>
					<div class="theme_classic_separator"></div>
					<div class="theme_classic_signup-btn {$signup_btn}"></div>
					<?php else: ?>
					<div class="theme_classic_name"><%= ucwords($_SESSION['name']) %></div>
					<?php endif ?>
				</div>
			</div>
		</div>
			
		<div class="theme_classic_body">
			<span class="theme_classic_notice" style="display:none">
				<strong>
					Please use <a href="http://www.mozilla.com/firefox">Firefox</a> or 
					<a href="http://www.apple.com/safari/">Safari</a> for the best results!
				</strong>
			</span>
			<noscript><strong>This site will not function properly without Javascript!</strong></noscript>
			{master}
		</div>
		
		<div class="theme_classic_foot">
			<div class="theme_classic_footer">
				<a href = "../Feedback/" class="theme_classic_feedback">Feedback</a>
				<a href = "../More/" class="theme_classic_help">Help</a>
				<?php if (isset($_SESSION['user_id'])): ?>
				<a href = "../Main/?action=logout" class="theme_classic_logout">Logout</a>
				<?php endif ?>
			</div>
		</div>
	</body>
	{javascript}
</html>