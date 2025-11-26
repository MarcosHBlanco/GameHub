jQuery(function ($) {
	const $btn = $(".menu-toggle");
	const $nav = $("#mainnav");

	console.log("[main.js] jQuery ready", {
		btn: $btn.length,
		nav: $nav.length,
		dollarIs: typeof $,
	});

	if (!$btn.length || !$nav.length) return;

	$btn.on("click", function () {
		const opened = $nav.toggleClass("open").hasClass("open");
		$btn.attr("aria-expanded", opened ? "true" : "false");
		console.log("[main.js] toggle", { opened });
	});

	$("#gameForm").on("submit", function () {
		const title = $('input[name="title"]').val().trim();
		const platform = $('input[name="platform"]').val().trim();
		const cover = $('input[name="cover_url"]').val().trim();
		$(".err-inline").remove();
		let ok = true;
		function err($el, msg) {
			$el.after(`<span class="err-inline err">${msg}</span>`);
			ok = false;
		}
		if (!title) err($('input[name="title"]'), "Required");
		if (!platform) err($('input[name="platform"]'), "Required");
		if (cover && !/^https?:\/\/.+/i.test(cover))
			err($('input[name="cover_url"]'), "Valid URL");
		return ok;
	});
});
