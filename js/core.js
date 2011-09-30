var ENTER = 13, ESC = 27;

var USER = 1;
var ADMIN = 2;
var COACH = 4;
var BEHEER = 8;
var OUDER = 16;
var SPELER = 32;

var data = new Array(1, 2, 4, 8, 16, 32);

$.loading_alert = function() {
	if ($('#darkenwrapper').is(':visible'))
	{
		$('#loadingalert').fadeIn(100);
	}
	else
	{
		$('#loadingalert').show();
		$('#darkenwrapper').fadeIn(100, function() {
			setTimeout(function() {
				if ($('#loadingalert').is(':visible'))
				{
					$.alert("Error", "Processing Error, please try again.");
				}
			}, 5000);
		});
	}

	return $('#loadingalert');
}    

/**
 * Display a simple alert similar to JSs native alert().
 *
 * @param string title Title of the message, eg "Information"
 * @param string msg Message to display. Can be HTML.
 * @param bool fadedark Remove the dark background when done? Defaults
 * 	to yes.
 *
 * @returns object Returns the div created.
 */
$.alert = function(title, msg, fadedark) {
	var div = $('#phpbb_alert');
	div.find('h3').html(title);
	div.find('p').html(msg);

	div.bind('click', function(e) {
		e.stopPropagation();
		return true;
	});
	$('#darkenwrapper').one('click', function(e) {
		var fade = (typeof fadedark !== 'undefined' && !fadedark) ? div : $('#darkenwrapper');
		fade.fadeOut(100, function() {
			div.hide();
		});
		return false;
	});

	$(document).bind('keydown', function(e) {
		if (e.keyCode === ENTER || e.keyCode === ESC) {
			$('#darkenwrapper').trigger('click');
			return false;
		}
		return true;
	});

	div.find('.alert_close').one('click', function() {
		$('#darkenwrapper').trigger('click');
	});

	if ($('#loadingalert').is(':visible'))
	{
		$('#loadingalert').fadeOut(100, function() {
			$('#darkenwrapper').append(div);
			div.fadeIn(100);
		});
	}
	else if ($('#darkenwrapper').is(':visible'))
	{
		$('#darkenwrapper').append(div);
		div.fadeIn(100);
	}
	else
	{
		$('#darkenwrapper').append(div);
		div.show();
		$('#darkenwrapper').fadeIn(100);
	}

	return div;
}
