$(document).ready(show);

			function show() {
				$("img.info").bind('click',show_photo);
			}

			function show_photo() {
				$("body").append("<div class='show_photo'></div>");
				$("img.info").clone().appendTo("div.show_photo").toggleClass("show_photo");
				$("div.show_photo").bind('click',hide_photo);
			}

			function hide_photo() {
				$("div.show_photo").remove();
			}