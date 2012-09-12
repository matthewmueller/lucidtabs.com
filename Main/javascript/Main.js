Main = function() {

}

Main.prototype = {
	// Temporary!
	buttonEnable : function(Theme) {
		var Main = this;
		
		$('.main_signup_button').click(function() {
			$('.signup_name_check,.signup_email_check,.signup_pass_check').hide();
			
			if ($('.signup_name_input').val().length <= 1) {
				$('.signup_name_check').fadeIn("fast");
				return false;
			}
						
			if ($('.signup_email_input').val().indexOf("@") < 0 || $('.signup_email_input').val().indexOf(".") < 0 ) {
				$('.signup_email_check').fadeIn("fast");
				return false;
			}
			
			if ($('.signup_pass_input').val().length <= 5) {
				$('.signup_pass_check').fadeIn("fast");
				return false;
			}
			
			var post = {};
			post.name = $('.signup_name_input').val();
			post.email = $('.signup_email_input').val();
			post.password = $('.signup_pass_input').val();
			postData = $(post).formatPost();
			
			$.ajax({
				url: '?action=create',
				type: 'POST',
				data: postData,

				success: function(response) {
					if (response) window.location='../More/?firstTime=true';
					else $('.signup_email_check').html("Email already exists!").fadeIn("fast");
				},

				error: function() {
					alert('Could not complete your request.');
				}
			});
			
		});
		
		$(".main_search_big_button,.main_create_big_button,.main_help_big_button").hover(function() {
			$(this).addClass('hover');
		}, function() {
			$(this).removeClass('hover');
		});
		
		$(".main_search_big_button").click(function() {
			$('.Search.input').focus();
			$('.theme_classic_search-box').css({'border-color': "#0078E5"});
			$('.theme_classic_body, .theme_classic_footer').fadeTo('slow', 0.5, function() {
				$('.main_remove_button').fadeIn('fast');
			});
			
			$('.theme_classic_head').animate({paddingTop: 20}, 'slow', function() {
				Main.buttonDisable();
			})
		});
		
		$(".main_create_big_button").click(function() {
			window.location = "../Create/";
		});
		
		$(".main_help_big_button").click(function() {
			window.location = "../More/";
		});
		
		$(".main_remove_button").click(function() {
			Main.reset();
		});
	},
	
	buttonDisable : function() {
		$(".main_search_big_button,.main_create_big_button,.main_help_big_button").unbind('click mouseenter mouseleave').removeClass('hover');
	},
	
	reset : function() {
		$('.main_remove_button').fadeOut("fast", function() {
			$('.theme_classic_head').animate({paddingTop: 0}, 'slow', function() {
				$('.theme_classic_body, .theme_classic_footer').fadeTo('slow', 1);
				$('.theme_classic_search-box').css({'border-color': "#2A2A2A"});
			})
		});

		this.buttonEnable();
	},
	
	login : function() {
		$('#signup').fadeOut("fast", function() {
			$('#login').fadeIn("fast", function() {
				$('.login_email_input').focus();
			});
		});
		
		$('.login_notice').fadeOut("fast");
		$('.login_email_input').focus();
	},
	
	signup : function () {
		$('#login').fadeOut("fast", function() {
			$('#signup').fadeIn("fast", function() {
				$('.login_notice').hide();
				$('.signup_name_input').focus();
			});
		});
		
		$('.signup_name_input').focus();
	},
	
	toString : function() {
		return "Main Class";
	}
}