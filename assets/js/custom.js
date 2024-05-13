$(document).ready(function () {
	$(function () {
		var url = window.location.href;
		if (url.split("?")) {
			var split = url.split("?");
			url = split[0];
		}
		if (url.split("#")) {
			var split = url.split("#");
			url = split[0];
		}
		// for single sidebar menu
		$("ul.nav-sidebar a")
			.filter(function () {
				return this.href == url;
			})
			.addClass("active");

		// for sidebar menu and treeview
		$("ul.nav-treeview a")
			.filter(function () {
				return this.href == url;
			})
			.parentsUntil(".nav-sidebar > .nav-treeview")
			.css({
				display: "block",
			})
			.addClass("menu-open")
			.prev("a")
			.addClass("active");
	});

	$(window).on("load", function () {
		// Once the page is fully loaded, stop spinning
		$(".loading").hide();
	});

	$(".loading").show();

	tooltps();

	function tooltps(html = true) {
		// for auto add tooltip
		$("[title]").attr("data-toggle", "tooltip");
		if (html) {
			$("[title]").attr("data-html", "true");
		}
		$("body").tooltip({
			selector: "[data-toggle=tooltip]",
			trigger: "hover",
		});
	}

	$(document).on('click', '[data-toggle="lightbox"]', function (event) {
		event.preventDefault();
		$(this).ekkoLightbox({
			alwaysShowClose: true
		});
	});

});