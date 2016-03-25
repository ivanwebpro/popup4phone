"use strict";

jQuery(document).ready(function()
{
	var $ = jQuery;

	function getNextZIndex()
	{
		var index_highest = 10;
		$('*').each(function()
		{
	    var index_current = parseInt($(this).css("z-index"), 10);
	    if(index_current > index_highest)
			{
	    	index_highest = index_current;
	    }
		});

		return index_highest + 1;
	}

	$('.popup4phone-popover-button').css('z-index', getNextZIndex()+20);


	var popup4phone = {};
	popup4phone.enabled = true;
	popup4phone.selector = '.popup4phone-form-popup';
	popup4phone.dlg = $(popup4phone.selector);
	popup4phone.fade = $('.popup4phone-fade');
	popup4phone.displaying = false;

	popup4phone.settings = popup4phone_settings;

	popup4phone.dlg2 = function ()
	{
		return $(this.selector);
	}

	popup4phone.restoreArea = function (f, a)
	{
		var dlg = f.closest('.popup4phone');
    dlg.find('.areas .area-' + a).show();
	}

	popup4phone.areaSetContent = function (f, a, c)
	{
		var $dlg = f.closest('.popup4phone');
		if (typeof c !== 'undefined' && c)
		{
    	$dlg.find('.areas .area-' + a).html(c);
		}
	}

	popup4phone.areaAdd = function (f, a)
	{
		var $dlg = f.closest('.popup4phone');
		var sel = '.areas .area-' + a;
		var $a = $dlg.find(sel);

		if ($a.length > 0)
			return;

		$a = $("<div/>").addClass("areas").addClass("area-" + a);
		$a.css("display", "none");
		$dlg.find(".areas").append($a);
	}

	popup4phone.showArea = function (f, a, c)
	{
		var dlg = f.closest('.popup4phone');

		dlg.find('.areas .area').hide();
		dlg.find('.areas .area-' + a).show();

		if (typeof c !== 'undefined' && c)
		{
    	dlg.find('.areas .area-' + a).html(c);
		}
		this.center();
	};

	popup4phone.submit = function(e, form)
	{
		e.preventDefault();

		var s = this.settings;
		var is_inline = (form.closest('.form-inline').length > 0);
   	var lab = is_inline ? s.ga_label_submit_inline :
								s.ga_label_submit_popup;

		this.trackGA({
			"cat": s.ga_category,
			"act": s.ga_action_submit,
			"lab": lab});


    var t = this;
		t.showArea(form, 'loading');

		var url = form.attr('action')
    var formData = form.serialize() + "&ajax=1";

    $.ajax({
      'url': url,
      'type': 'POST',
      'dataType': 'json',
			'data': formData,
			'cache': false,
			'error': function ajaxError(XHR, settings, thrownError)
			{
				var resp = XHR.responseText;
				t.showArea(form, 'error', resp);
				t.restoreArea(form, 'form');
			},
      'success': function ajaxSuccess(data)
			{

				try
				{
					var a = "tracking-pixel";
		      t.areaAdd(form, a);
					t.areaSetContent(form, a, "");
					t.areaSetContent(form, a, s.on_submit_tag);

					eval(s.js_run_on_submit);
				}
				catch(e)
				{
					console.log("JS code for run on the submit has error:");
		    	console.log(e);
				}


				if (data.success)
      		t.showArea(form, 'success', data.response);
				else
				{
					t.showArea(form, 'error', data.response);
					t.restoreArea(form, 'form');
				}
			}
			});
	}

	popup4phone.center = function()
	{
		if (!this.dlg)
		{
			// dlg is not initialized yet
			return;
		}

		var winWidth = $(window).width();
		var winHeight = $(window).height();
		var docHeight = $(document).height();
		var scrollPos = $(window).scrollTop();

		this.width = 400;
	  if (0.8*winWidth < this.width)
		{
			this.width = Math.round(0.8 * winWidth);
		}

		var dlgLeft = (winWidth - this.width) / 2;
    var dlgHeight = this.dlg.height();

		if (dlgHeight > winHeight)
			var disTop = scrollPos + 5;
		else
		 	var disTop = scrollPos + (winHeight - dlgHeight)/2;

		this.dlg.css({
			'width': this.width + 'px',
			'left': dlgLeft + 'px',
			'top': disTop + 'px',
			'z-index': getNextZIndex()+20,
			});
		$('.popup4phone-fade').css(
			{
				'width': winWidth + 'px',
				'height': docHeight + 'px'
			});
	}

	popup4phone.init = function()
	{
		var title = document.title;
		$("[name='popup4phone[ws_pages_submit_title]']").val(title);

		var t = this;

		$(document).on("submit", ".popup4phone form", function(e)
		{
			t.submit(e, $(this));
		});

		t.delay = this.settings.delay;
		if (this.settings.auto_popup_enabled && ($('.popup4phone-form-inline[data-no-popup=1]').length == 0))
		{
			setTimeout(function ()
				{
					var auto = true;
					t.showDlg(auto);
				},
				t.delay*1000);
		}
	}

	$(document).on( "popup4phone-open-force", function()
	{
		//console.log("have ev popup4phone-open-force")
		var auto = false;
		popup4phone.showDlg(auto);
	});

	$(document).on( "popup4phone-disable", function()
	{
		popup4phone.enabled = false;
		$('.popup4phone-popover-button').hide();
	});

	$(document).on( "popup4phone-enable", function()
	{
		popup4phone.enabled = true;
		$('.popup4phone-popover-button').show();
	});

	popup4phone.noticeAppPopupShown = function()
	{
    jQuery.ajax(
		{
			dataType: 'json',
			charset: 'UTF-8',
			type: 'POST',
			data:
				{
					'popup4phone-shown': {"notice": true}
				},
		});
	};

	popup4phone.showDlg = function(auto)
	{
		if (this.displaying || !this.enabled)
			return;

		var s = this.settings;
		var is_auto = !auto ? false: true;;
		var lbl = is_auto ? s.ga_label_open_auto :
								s.ga_label_open_click;

		if (is_auto)
		{
			if (!s.state.popup_show)
			{
      	return;
			}
			this.noticeAppPopupShown();
		}

		this.trackGA(
		{
			"cat": s.ga_category,
			"act": s.ga_action_open,
			"lab": lbl
		});


		this.dlg
			.find('.areas')
			.html($('.popup4phone-areas-init').html());
		this.center();
		this.displaying = true;
    this.fade.fadeIn('fast');
		this.dlg.fadeIn('fast');
	}

	popup4phone.hideDlg = function()
	{
		this.displaying = false;
		this.dlg.hide();
		this.fade.hide();
	}

	popup4phone.trackGA = function(ps)
	{
		var s = this.settings;
		if (!s.ga_send_events)
			return;

		var def = {"cat": '', "act": '', "lab": ''};
		ps = $.extend(def, ps);

		var u;
		var gaf;

		if (typeof _gaq !== 'undefined')
		{
   		gaf = _gaq;
			u = false;
  	}
		else if (typeof ga !== 'undefined')
		{
   		gaf = ga;
			u = true;
  	}
		else
		{
			console.log("Google Analytics not found, cannot track events");
			return;
		}

		//if (typeof gaf !== 'function')
		//{
			//console.log("Google Analytics not found, cannot track events");
			//return;
		//}

		if (u)
		{
  		gaf('send','event', ps.cat, ps.act, ps.lab);
  	}
		else
		{
  		gaf.push(['_trackEvent', ps.cat, ps.act, ps.lab]);
  	}
	};

	popup4phone.init();
	$(window).resize(function() {popup4phone.center(); });
	popup4phone.center();

	$('.popup4phone-force-show, .popup4phone-popover-button *, .popup4phone-popover-button-inline').click(function(e)
	{
		e.preventDefault();
		e.stopPropagation();
		var auto = false;
		popup4phone.showDlg(auto);
	});

	$('.close, .popup4phone-fade').click(function(e)
	{
		popup4phone.hideDlg();
	});


  $('.popup4phone-popover-button').fadeIn(2000, function()
	{
		if (popup4phone_settings.popup_button_animation_bounce)
		{
			animateButton();
		}

		function animateButton()
		{
			var s = 100;
			var a = 5;
			var b = 20;
			var e = "easeInBounce";
			$('.popup4phone-popover-button .wrapper')
				.stop(true,true)
				.animate({"top": a}, s, e).animate({"top": -a}, s, e)
				.animate({"top": a}, s, e).animate({"top": -a}, s, e)
				;
			setTimeout(animateButton, 10*1000);
		};

	});

});
