Feedback = function() {
	
}

Feedback.prototype = {
	
	start : function() {
		$('.theme_classic_feedback').css('color', '#0078E5');
		
		$('.feedback_submit_button').click(function() {
			var name = $('.feedback_name_input').val();
			var email = $('.feedback_email_input').val();
			var feedback = $('.feedback_text').val()
			
			if (name.length <= 1) {
				$('.feedback_name_input').css("border-color", "#ff4b4b").focus();
				return;
			}
			else if (email.indexOf("@") < 0 || email.indexOf(".") < 0) {
				$('.feedback_email_input').css("border-color", "#ff4b4b").focus();
				return;
			}
			else if(feedback == "") {
				$('.feedback_text').css("border-color", "#ff4b4b").focus();
				return;
			}
			
			post = {};
			post.feedback= feedback;
			post.name = name;
			post.email = email;
			
			var postData = $(post).formatPost();

			$.ajax({
				url: '?action=submit',
				type: 'POST',
				data: postData,

				success: function(response) {
					$('.feedback_submit_button').fadeOut("slow", function() {
						$('.feedback_thankyou_button').fadeIn("slow");
					});
					$('.feedback_text,.feedback_email_input,.feedback_name_input').attr("DISABLED", "true");
				},

				error: function() {
					alert('error occured!');
				}
			});
			
			
		});
		
	},
	
	
	login : function() {
		window.location = "../Main/?view=login";
	},
	
	signup : function() {
		window.location = "../Main/?view=signup";
	},
}