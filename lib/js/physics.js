
$(document).ready(function(){

	$('.slideshow-micros-critiques').bxSlider({
		slideSelector : '.micro-critique',
		pager: false,
		nextText : '',
		prevText: ''                                                       
	});


	THE1958 = {
	containerCoverGame : $('.container-cover-game-to-margin'),
	containerGameswall : $('#games-in-wall'),
	marginTheCover : function(){
		// $('.container-cover-game-to-margin').css('margin-top' , -($('.container-cover-game-to-margin').height()-94)+'px');
	},
	makeNotesBeautiful: function () {
		$('.container-c-note').each(function(){
			var $circleback = $(this).find('canvas.colorback');
			var $circle = $(this).find('canvas:not(.colorback)');
			var minCNote = 0;
			var maxCNote = 100;

			var size = $circleback.width();
			var lineWidth = 8, retrait = 5;
			if($(this).hasClass('container-note-crt')){
				lineWidth = 6;
				retrait = 3;
			}
			if($(this).hasClass('c-note-gameplay')){
				colorCircle = "#ff8043";
			}else if($(this).hasClass('c-note-graphism')){
				colorCircle = "#ffbc3e";
			}else if($(this).hasClass('c-note-lifetime')){
				colorCircle = "#00ad67";
			}else if($(this).hasClass('c-note-story')){
				colorCircle = "#5585d7";
			}else if($(this).hasClass('c-note-moyenne')){
				colorCircle = "#191c1f";
			}else if($(this).hasClass('c-note-bo')){
				colorCircle = "#896DCF";
			}else if($(this).hasClass('c-note-crt')){
				colorCircle = "#d12c30";
				lineWidth = 4;
				retrait = 5;
			}else if($(this).hasClass('c-note-average-activity')){
				colorCircle = "#d12c30";
				lineWidth = 8;
				retrait = 5;
			}
			
			var valNote = $(this).find('.note').data('note');

			var newNote = (valNote - minCNote) / (maxCNote - minCNote);

			var ctxCircleBack = $circleback[0].getContext('2d');

			//debut du tracé cercle contenant
			ctxCircleBack.beginPath();
			ctxCircleBack.lineWidth = lineWidth;
			ctxCircleBack.strokeStyle = colorCircle;
			ctxCircleBack.arc(size/2, size/2, (size/2)-retrait, 0, 2*Math.PI);
			ctxCircleBack.stroke();

			// debut du tracé cercle note
			var ctxCircleNote = $circle[0].getContext('2d');
			ctxCircleNote.beginPath();
			ctxCircleNote.lineWidth = lineWidth;
			ctxCircleNote.strokeStyle = colorCircle;
			ctxCircleNote.arc(size/2, size/2, (size/2)-retrait, -1/2*Math.PI, newNote*2*Math.PI - 1/2*Math.PI);
			ctxCircleNote.stroke();
		});
	},
	ajax_with_retry:function(a){
		defaults={
			retry_limit:2,
			type:"get",
			dataType:"html",
			success:function(a){},
			data:{}
		};
		a=$.extend(
			{},
			defaults,
			a
			);
		$.ajax({
			url:a.url,
			type:a.type,
			data:a.data,
			dataType:a.dataType,
			try_count:0,
			retry_limit:a.retry_limit,
			success:a.success,
			error:function(a,c,d){
				this.try_count++;
				this.try_count<=this.retry_limit&&$.ajax(this)}
		})
	},
	orderGameswall : function(){
		// $('#games-in-wall').width($("#page-gameswall").width());
		var ratioGamePic = 1.33;
		$('.container-game.featured-game').height($('.container-game.featured-game').width()*ratioGamePic);
		$('.container-game:not(.featured-game)').height($('.container-game.featured-game').height()/2);
		// $('.container-game').each(function(){
		// 	// var $gamePic = $(this).find('.game-cover');
		// 	// var wGamePic = parseInt($gamePic.attr('width'));
		// 	// var hGamePic = parseInt($gamePic.attr('height'));
		// 	// var ratioGamePic = hGamePic/wGamePic;
		// 	var wContainerGame = $(this).width();
		// 	var newHContainerGame = wContainerGame*ratioGamePic;
		// 	if(newHContainerGame%2 != 0){
		// 		newHContainerGame -= 1;
		// 	}
		// 	$(this).height(newHContainerGame-1);
		// });
		// $("#games-in-wall").masonry({
		//   // options
		//   columnWidth: $('.container-game:not(.featured-game)').width()/2,
		//   itemSelector: '.container-game:not(.container-game-b)',
		//   gutter:0,
		//   isFitWidth: true
		// });
		// $("#games-in-wall").masonry('bindResize');
	},
	postComment : function(a){
		var f = $("#"+a.target.id);
		var data = f.serializeArray();
		var c = f.closest('.compoze-com');
		if(!!CURRENT_USER){
			this.ajax_with_retry({
				url:f.attr('action'),
				type:f.attr('method'),
				method:f.attr('method'),
				data:data,
				dataType:"JSON",
				success:function(b){
					c.addClass('loading');
					c.find('.content-com').html('Une seconde ...');
					tplComment = $('#template-mustache-comment').html();
	    			if(b['comment_id'] != false){
		    			c.before(Mustache.render(tplComment, b));
		    			c.remove();
	    			}
				}
			});
		}
	},
	set_want_game : function(a){
		if(!!CURRENT_USER){
			if($('.want-game-button').hasClass('pressed')){
				var b = "remove";
			}else{
				var b = "add";
			}
			this.ajax_with_retry({
				url:a.data('url'),
				type:"POST",
				method:"POST",
				data: {action:b, at:$('.want-game-button').data('authenticity-token')}
			});
			$('.have-game-button').removeClass('pressed');
			if($('.want-game-button').hasClass('pressed')){
				$('.want-game-button').removeClass('pressed');
			}else{
				$('.want-game-button').addClass('pressed');
			}
		}
	}
	,
	set_have_game : function(a){
		if(!!CURRENT_USER){
			if($(".have-game-button").hasClass('pressed')){
				var b = "remove";
			}else{
				var b = "add";
			}
			this.ajax_with_retry({
				url:a.data('url'),
				type:"POST",
				method:"POST",
				data: {action:b, at:$('.have-game-button').data('authenticity-token')}
			});
			$('.want-game-button').removeClass('pressed');
			if($(".have-game-button").hasClass('pressed')){
				$(".have-game-button").removeClass('pressed');
			}else{
				$(".have-game-button").addClass('pressed');
			}
		}
	}
};
	$(".prevent-default").on("click", "a", function (event) {
	    event.preventDefault();
	    // return false;
	});
	$('.tipsy-bottom').tipsy({gravity : 'n'});
	$('.tipsy-top').tipsy({gravity : 's'});
	$('.tipsy-top-html').tipsy({gravity : 's', html:true});
	$('.tipsy-bottom-html').tipsy({gravity : 'n', html:true});
	$('.tipsy-right').tipsy({gravity : 'w'});


	$(document).on("click", ".notifications-show-button", function(event){
		$(this).removeClass('has_unread');
		$(this).find('.inbox-count').removeClass('has_unread');
		event.preventDefault();
		if($(this).is('#global-notifications-inbox-icon')){
			var a = $('#container-notifications-global');
			var b = $('#container-notifications-followings');
		}else if($(this).is('#followings-notifications-inbox-icon')){
			var a = $('#container-notifications-followings');
			var b = $('#container-notifications-global');
		}
		if($(this).hasClass('reveal')){
			$(this).removeClass('reveal');
			a.removeClass('reveal');
		}else{
			$('.notifications-show-button').removeClass('reveal');
			$(this).addClass('reveal');
			a.addClass('reveal');
			b.removeClass('reveal');
		}
		if(!a.hasClass('loaded')){
			a.addClass("loading");
			THE1958.ajax_with_retry({
				url:this.href,
				success:function(b){
					a.html(b);
					a.addClass("loaded").removeClass("loading");
				}
			});
		}
	}).on("click", ".show-more-notifications-button", function(event){
		event.preventDefault;
		var a=$(this).addClass("loading");
		THE1958.ajax_with_retry({
			url:this.href,
			success:function(b){
				a.before(b).remove();
			}
		});
		return false;
	}).on("click", ".show-more-comments-button", function(event){
		event.preventDefault;
		var a=$(this).addClass("loading");
		THE1958.ajax_with_retry({
			url:this.href,
			success:function(b){
				a.after(b).remove();
			}
		});
		return false;
	}).on("click", ".pyong-button", function(event){
		event.preventDefault();
		a = $(this);
		at = a.data('authenticity-token');
		if(a.hasClass('pressed')){return false;}else{
			a.addClass('pressed');
			THE1958.ajax_with_retry({
				url:this.href,
				type:"post",
				data:{at:at},
				success:function(b){
					a.parents('.pyong-action').find('.how-many-pyong').text(b);
					a.text("I'M IN SPACE!");
				}
			});
		}
	}).on("click", ".container-notification .action-summary", function(){
		$parent = $(this).parent();
		$parent.toggleClass('reveal');
		$parent.removeClass('new');
	}).on('click', ".want-game-button", function(){
		THE1958.set_want_game($(this));
	}).on('click', ".have-game-button", function(){
		THE1958.set_have_game($(this));
	}).on('click', ".follow-button-normal", function(){
		var data = {};
		$this = $(this);
		if(!!CURRENT_USER && !$this.hasClass('loading')){
			$this.addClass('loading');
			data['at'] = $this.data('authenticity-token');
			if($this.hasClass('follow-button')){
				data['action'] = "set";
				var delClass = 'follow-button';
				var addClass = 'unfollow-button';
				var textDefault = 'Abonné';
				var hoverText = 'Se désabonner';
			}else{
				data['action'] = "del";
				var delClass = 'unfollow-button';
				var addClass = 'follow-button';
				var textDefault = "S'abonner";
				var hoverText = "S'abonner";
			}
			THE1958.ajax_with_retry({
				url:$this.data('url'),
				type:"post",
				data:data,
				success:function(b){
					// console.log(b);
					$this.addClass(addClass).removeClass(delClass);
					$this.attr('data-text-hover', hoverText).attr('data-text-default', textDefault).text(textDefault);
				}
			});
			$this.removeClass('loading');
		}
	}).on('click', ".appreciation-button", function(){
		var data = {};
		$this = $(this);
		$parent = $this.parent();
		data['at'] = $this.data('authenticity-token');
		if($this.hasClass('pressed')){
			var delClass = 'pressed';
			var addClass = '';
			data['action'] = 'del';
		}else{
			data['action'] = 'set';
			var delClass = '';
			var addClass = 'pressed';
		}
		if(!!CURRENT_USER && !$this.hasClass('loading')){
			$this.addClass('loading');
			THE1958.ajax_with_retry({
				url:$this.data('url'),
				type:"post",
				data:data,
				success:function(b){
					console.log(b);
					$parent.find('.appreciation-button').removeClass('pressed');
					$this.addClass(addClass).removeClass(delClass);
				}
			});
			$this.removeClass('loading');
		}
	});

	$.fn.centerVer = function () {
		this.each(function(){
		    $(this).css("position","absolute");
		    $(this).css("top", Math.max(0, (($($(this).parent()).height() - $(this).outerHeight()) / 2) + 
                            $($(this).parent()).scrollTop()) + "px");
		});
	    return this;
	};
	$.fn.marginTopHimself = function () {
    	this.css("margin-top", -($(this).innerHeight() + 15)+ "px");
	    return this;
	};
	$.fn.selectRange = function(start, end) {
    	if(!end) end = start; 
	    return this.each(function() {
	        if (this.setSelectionRange) {
	            this.focus();
	            this.setSelectionRange(start, end);
	        } else if (this.createTextRange) {
	            var range = this.createTextRange();
	            range.collapse(true);
	            range.moveEnd('character', end);
	            range.moveStart('character', start);
	            range.select();
	        }
	    });
	};
	var refreshAverage = function(){
		var noteGameplay = parseInt($('.form-note-gameplay .input-note input.note').val());
		var noteGraphism = parseInt($('.form-note-graphism .input-note input.note').val());
		var noteStory = parseInt($('.form-note-story .input-note input.note').val());
		var noteLifeTime = parseInt($('.form-note-lifetime .input-note input.note').val());
		var noteBO = parseInt($('.form-note-bo .input-note input.note').val());
		var sizeContainer = parseInt($('input.note').data('size'));

		var noteAverage = Math.round((noteGameplay+noteGraphism+noteStory+noteLifeTime+noteBO)/5);
		$('.form-note-average .input-note input.note').val(noteAverage);


		var $circleAverage = $('.form-note-average .input-note canvas:not(.colorback)');

		var ctxAverage = $circleAverage[0].getContext('2d');
		var $input = $('input.note');
		var minNote = $input.data('min');
		var maxNote = $input.data('max');
		var valNote = noteAverage;
		var ratio = (valNote - minNote) / (maxNote - minNote);
		var lineWidth = 8, retrait = 5;
		if(sizeContainer == 60){
			retrait = 3;
			lineWidth = 6;
		}
		ctxAverage.clearRect(0,0,sizeContainer,sizeContainer);
		ctxAverage.beginPath();
		ctxAverage.arc(sizeContainer/2,sizeContainer/2, (sizeContainer/2)-retrait, -1/2 * Math.PI, ratio*2*Math.PI - 1/2 * Math.PI);
		ctxAverage.lineWidth = lineWidth;
		ctxAverage.strokeStyle = '#434142';
		if(sizeContainer == 60){
			ctxAverage.strokeStyle = '#191c1f';
		}
		ctxAverage.stroke();
	};
	$('#begin-noting').click(function(){
		$(this).parents('.box-rate-game').hide(),
		$('.rating.gameplay').show();
		$(".rating li").click(function(){
		    $(this).parent().find("li").removeClass("active");
		    $(this).addClass("active");
		    if($(this).parent().attr("rel") == 'end'){
		        $(".rating ul").each(function(){
		            $("."+$(this).attr("id")).html($(this).find("li.active").text());
		            $(".rating").hide();
		            $(".totalrating").show();
		        });
		    }else{
		        $(".rating").hide();
		        $("."+$(this).parent().attr("rel")).show();
		    }
		});
	});

	$('#send-notes').click(function(){
		$this = $(this);
		var notes = {},
			i = 0;
		$('.rating ul').each(function(){
			var note = parseInt($(this).find('li.active').text());
			if(note > 0 && note <= 10){
				notes[$(this).data('rate')] = note;
			}else{
				return false;
			}
			i++;
		});

		// if(i == 5){
		$('#loader').fadeIn(200);
		THE1958.ajax_with_retry({
			url: $this.data('url'),
			data : {notes:notes},
			method : "POST",
			type : "POST",
			success:function(b){
				$this.parents('#container-ratings-game').replaceWith(b);
			}
		});
		$("#loader").fadeOut(500);
		// }
	});
	$("#edit-notes").click(function(){
        $(".rating li").removeClass("active");
        $(".totalrating").hide();
        $(".rating.gameplay").show()
    });
	
	$('input.note').each(function(){
		var $input = $(this);
		var sizeContainer = parseInt($input.data('size'));
		var $div = $input.parent();
		var minNote = $input.data('min');
		var maxNote = $input.data('max');
		var valNote = $(this).val();
		var ratio = (valNote - minNote) / (maxNote - minNote);

		// definition color 
		if($div.parent().hasClass('form-note-gameplay')){
			colorCircle = "#ff8043";
		}else if($div.parent().hasClass('form-note-graphism')){
			colorCircle = "#ffbc3e";
		}else if($div.parent().hasClass('form-note-lifetime')){
			colorCircle = "#00ad67";
		}else if($div.parent().hasClass('form-note-story')){
			colorCircle = "#5585d7";
		}else if($div.parent().hasClass('form-note-average-black')){
			colorCircle = "#191c1f";
		}else if($div.parent().hasClass('form-note-average')){
			colorCircle = "#434142";
		}else if($div.parent().hasClass('form-note-bo')){
			colorCircle = "#896DCF";
		}

		var lineWidth = 8, retrait = 5;
		if(sizeContainer == 60){
			retrait = 3;
			lineWidth = 6;
		}
		
		var $backgroundCircle = $('<canvas height="'+sizeContainer.toString()+'px" width="'+sizeContainer.toString()+'px" class="colorback"/>');
		var $circleNote = $('<canvas height="'+sizeContainer.toString()+'px" width="'+sizeContainer.toString()+'px"/>');
		$div.append($backgroundCircle).append($circleNote);

		var ctxCircleFormBackNote = $backgroundCircle[0].getContext('2d');

		ctxCircleFormBackNote.beginPath();
		ctxCircleFormBackNote.arc(sizeContainer/2,sizeContainer/2, (sizeContainer/2)-retrait, 0, 2*Math.PI);
		ctxCircleFormBackNote.lineWidth = lineWidth;
		ctxCircleFormBackNote.strokeStyle = colorCircle;
		ctxCircleFormBackNote.stroke();


		var ctxCircleFormNote = $circleNote[0].getContext('2d');

		ctxCircleFormNote.beginPath();
		ctxCircleFormNote.arc(sizeContainer/2,sizeContainer/2, (sizeContainer/2)-retrait, -1/2 * Math.PI, ratio*2*Math.PI - 1/2 * Math.PI);
		ctxCircleFormNote.lineWidth = lineWidth;
		ctxCircleFormNote.strokeStyle = colorCircle;
		ctxCircleFormNote.stroke();

		// cercle form
		$div.mousedown(function(event){
			event.preventDefault();
			if($div.parent().hasClass('form-note-gameplay')){
				colorCircle = "#ff8043";
			}else if($div.parent().hasClass('form-note-graphism')){
				colorCircle = "#ffbc3e";
			}else if($div.parent().hasClass('form-note-lifetime')){
				colorCircle = "#00ad67";
			}else if($div.parent().hasClass('form-note-bo')){
				colorCircle = "#896DCF";
			}else if($div.parent().hasClass('form-note-story')){
				colorCircle = "#5585d7";
			}else if($div.parent().hasClass('form-note-average-black')){
				colorCircle = "#191c1f";
				return false;
			}else if($div.parent().hasClass('form-note-average')){
				colorCircle = "#434142";
			}
			var x = event.pageX - $div.offset().left - $div.width()/2;
			var y = event.pageY - $div.offset().top - $div.height()/2;
			var a = Math.atan2(x, -y) / (2*Math.PI);

			if (a < 0) {
				a += 1;
			}
			ctxCircleFormNote.clearRect(0,0,sizeContainer,sizeContainer);
			ctxCircleFormNote.beginPath();
			ctxCircleFormNote.arc(sizeContainer/2,sizeContainer/2, (sizeContainer/2)-retrait, -1/2 * Math.PI, a*2*Math.PI - 1/2 * Math.PI);
			ctxCircleFormNote.lineWidth = lineWidth;
			ctxCircleFormNote.strokeStyle = colorCircle;
			ctxCircleFormNote.stroke();

			var newValNote = a * (maxNote - minNote) + minNote;
			$input.val(Math.floor((newValNote+2.5)/5)*5);
			$input.attr('value', Math.floor((newValNote+2.5)/5)*5);

			refreshAverage();
			$div.bind('mousemove', function(event){
				var x = event.pageX - $div.offset().left - $div.width()/2;
				var y = event.pageY - $div.offset().top - $div.height()/2;
				var a = Math.atan2(x, -y) / (2*Math.PI);

				if (a < 0) {
					a += 1;
				}
				ctxCircleFormNote.clearRect(0,0,sizeContainer,sizeContainer);
				ctxCircleFormNote.beginPath();
				ctxCircleFormNote.arc(sizeContainer/2,sizeContainer/2, (sizeContainer/2)-retrait, -1/2 * Math.PI, a*2*Math.PI - 1/2 * Math.PI);
				ctxCircleFormNote.lineWidth = lineWidth;
				ctxCircleFormNote.strokeStyle = colorCircle;
				ctxCircleFormNote.stroke();

				var newValNote = a * (maxNote - minNote) + minNote;
				$input.val(Math.floor((newValNote+2.5)/5)*5);

				refreshAverage();
			});
		}).mouseup(function(event){
			event.preventDefault();
			$div.unbind('mousemove');

			refreshAverage();	
		});

	});
	$('.autosuggestion-on').on('keyup', function(e){
		if($(this).text().length >= 2){
			var data = {};
			data['search'] = $(this).text();
			data['ac'] = true;
			THE1958.ajax_with_retry({
				url:$(this).data('url-autosugestion'),
				method : "POST",
				type: "POST",
				data : data,
				success:function(b){
					console.log(b);
				}
			});
		}
	});
	$(".editable:not(.autosuggestion-on)").on('click', function(e) {
	    e.preventDefault();

	    if($(this).find('.placeholder')){
		    var div = $(this).find('.placeholder')[0];
		    div.focus();

		    if (window.getSelection && document.createRange) {
		        // IE 9 and non-IE
		        var sel = window.getSelection();
		        var range = document.createRange();
		        range.setStart(div, 0);
		        range.collapse(true);
		        sel.removeAllRanges();
		        sel.addRange(range);
		    } else if (document.body.createTextRange) {
		        // IE < 9
		        var textRange = document.body.createTextRange();
		        textRange.moveToElementText(div);
		        textRange.collapse(true);
		        textRange.select();
		    }
	    }
	}).on('1958:ffocus', function(e){
		    var div = $(this)[0];
		    div.focus();

		    if (window.getSelection && document.createRange) {
		        // IE 9 and non-IE
		        var sel = window.getSelection();
		        var range = document.createRange();
		        range.setStart(div, 100);
		        range.collapse(true);
		        sel.removeAllRanges();
		        sel.addRange(range);
		    } else if (document.body.createTextRange) {
		        // IE < 9
		        var textRange = document.body.createTextRange();
		        textRange.moveToElementText(div);
		        textRange.collapse(true);
		        textRange.select();
		    }
	});

	var defaultPlaceHolderTitleCritique = $('.editable[name=title] .placeholder').text();
	var defaultPlaceHolderContentCritique = $('.editable[name=content] .placeholder').text();
	var defaultPlaceHolderMicroCritique = $('.editable[name=the-mccrt] .placeholder').text();

	$('.editable').on('keypress', function(){
		$(this).find('.placeholder').remove();
		$(this).addClass('not-empty').removeClass('empty');
	}).on('blur', function(){
		if($(this).text() == ""){
			$(this).addClass('empty').removeClass('not-empty');
			if($(this).attr('name') == "title"){
				$(this).html('').append('<span class="placeholder">'+defaultPlaceHolderTitleCritique+'</span>');
				$('#container-title-and-author .title-critique').text(defaultPlaceHolderTitleCritique);
			}else if($(this).attr('name') == "content"){
				$(this).html('').append('<span class="placeholder">'+defaultPlaceHolderContentCritique+'</span>');
			}else if($(this).attr('name') == "the-mccrt"){
				$(this).html('').append('<span class="placeholder">'+defaultPlaceHolderMicroCritique+'</span>');
			}
			
		}
	}).on('keyup', function(event){
		$('#container-head-game').marginTopHimself();
		if($(this).attr('name') == "title"){
			$('.center-ver').centerVer();
			l = $(this).text().length;
			ml = $(this).data('max-length');
			$(this).attr('data-length', l);
			$('#container-title-and-author .title-critique').text($(this).text());
			if (l >= ml) {
	    		event.stopPropagation();
	    		return false;
	    	}
		}else if($(this).attr('name') == "the-mccrt"){
			$('.centerVer').centerVer();
			l = $(this).text().length;
			ml = $(this).parents('form').data('max-length');
			$(this).parents('form').attr('data-length', l);
			if (l >= ml) {
	    		event.stopPropagation();
	    		return false;
	    	}
		}
	});

	$('#send-create-critique').on('click', function(){
		var data = {},
			notes = {};
		var isOk = (!$('.editable[name=title]').hasClass('empty') && !$('.editable[name=content]').hasClass('empty'));
		if(isOk){	
			$('#loader').fadeIn(100);
			data['title'] = $('.editable[name=title]').text();
			data['content'] = $('.editable[name=content]').html();
			data['at'] = $("form[name=create-critique-form").data('authenticity-token');
			$('.container-form-notes input.note').each(function(){
				notes[$(this).attr('name')] = $(this).attr('value');
			});
			data['notes'] = notes;
			THE1958.ajax_with_retry({
				url:$("form[name=create-critique-form").attr('action'),
				method : "POST",
				type: "POST",
				data : data,
				dataType : 'JSON',
				success:function(b){
					// console.log(b);
					if(b['s']==true){
						document.location.href = b['u'];
						$('#loader').fadeOut(250);
					}else{
						$('#loader').fadeOut(250);
					}
				}
			});
		}
	});
	$('#send-create-micro-critique').click(function(){
		var data = {};
		var isOk = (!$('.editable[name=the-mccrt]').hasClass('empty'));
		if(isOk){
			$('#loader').fadeIn(100);
			data['content'] = $('.editable[name=the-mccrt]').text();
			data['at'] = $("form[name=create-micro-critique-form").data('authenticity-token');
			THE1958.ajax_with_retry({
				url:$("form[name=create-micro-critique-form").attr('action'),
				method : "POST",
				type: "POST",
				data : data,
				dataType : 'JSON',
				success:function(b){
					if(b['s']==true){
						document.location.href = b['u'];
						$('#loader').fadeOut(250);
					}else{
						$('#loader').fadeOut(250);
					}
				}
			});
		}
	});

	$('.center-ver').centerVer();
	$('#container-head-game').marginTopHimself();
	// $('.container-cover-game-to-margin').css('margin-top' , -($('.container-cover-game-to-margin').height()-94)+'px');

	THE1958.marginTheCover();
	THE1958.makeNotesBeautiful();
	THE1958.orderGameswall();
	
	$(window).resize(function(){
		THE1958.marginTheCover();
		THE1958.orderGameswall();
		$('.center-ver').centerVer();
		$('#container-head-game').marginTopHimself();
		// $('.container-cover-game-to-margin').css('margin-top' , -($('.container-cover-game-to-margin').height()-94)+'px');
	});
});
$(document).on('submit', 'form', function(e) {
	switch(e.target.id) {
		case 'post_comment_form' :
			e.preventDefault();
			e.stopPropagation();
			THE1958.postComment(e);
			break;
		default:
			//
			break;
	}
	return false;
});