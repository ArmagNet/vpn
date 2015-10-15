 /*
    Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

    This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/

$(function() {
	$("#loginForm #renewButton").click(function(event) {
		event.stopPropagation();
		event.preventDefault();

		$(".renew-password").toggleClass("hidden");
	});

	$("#loginForm #loginButton").click(function(event) {
		event.stopPropagation();
		event.preventDefault();

		var myform = {
			password : $("#passwordInput").val(),
			newPassword : $("#newPasswordInput").val(),
			confirmNewPassword : $("#confirmNewPasswordInput").val()
		};

		$.post("do_login.php", myform, function(data) {
//			$("#loginForm").hide();
//			return;
			$(".renew-password").addClass("hidden");

			if (data.status == "ok") {
				window.location.reload(true);
			}
			else if (data.status == "renew_password") {
				$(".renew-password").removeClass("hidden");
			}

			$("#" + data.message + "Alert").parent(".container").removeClass("hidden");
			$("#" + data.message + "Alert").removeClass("hidden").show().delay(2000).fadeOut(1000, function() {
				$(this).parent(".container").addClass("hidden");
			});

		}, "json");
	});

	$(".logoutLink").click(function(event) {
		event.stopPropagation();
		event.preventDefault();

		var myform = {};

		$.post("do_logout.php", myform, function(data) {
			if (data.ok) {
				window.location.reload(true);
			} else {
			}
		}, "json");
	});
});