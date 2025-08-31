/*-----------------------------------------------------------------------------------*/
/*	RETINA.JS
/*-----------------------------------------------------------------------------------*/

(function(){function t(e){this.path=e;var t=this.path.split("."),n=t.slice(0,t.length-1).join("."),r=t[t.length-1];this.at_2x_path=n+"@2x."+r}function n(e){this.el=e,this.path=new t(this.el.getAttribute("src"));var n=this;this.path.check_2x_variant(function(e){e&&n.swap()})}var e=typeof exports=="undefined"?window:exports;e.RetinaImagePath=t,t.confirmed_paths=[],t.prototype.is_external=function(){return!!this.path.match(/^https?\:/i)&&!this.path.match("//"+document.domain)},t.prototype.check_2x_variant=function(e){var n,r=this;if(this.is_external())return e(!1);if(this.at_2x_path in t.confirmed_paths)return e(!0);n=new XMLHttpRequest,n.open("HEAD",this.at_2x_path),n.onreadystatechange=function(){return n.readyState!=4?e(!1):n.status>=200&&n.status<=399?(t.confirmed_paths.push(r.at_2x_path),e(!0)):e(!1)},n.send()},e.RetinaImage=n,n.prototype.swap=function(e){function n(){t.el.complete?(t.el.setAttribute("width",t.el.offsetWidth),t.el.setAttribute("height",t.el.offsetHeight),t.el.setAttribute("src",e)):setTimeout(n,5)}typeof e=="undefined"&&(e=this.path.at_2x_path);var t=this;n()},e.devicePixelRatio>1&&(window.onload=function(){var e=document.getElementsByTagName("img"),t=[],r,i;for(r=0;r<e.length;r++)i=e[r],t.push(new n(i))})})();

/*-----------------------------------------------------------------------------------*/
/*	SLIDER
/*-----------------------------------------------------------------------------------*/



				$(document).ready(function() {

				if ($.fn.cssOriginal!=undefined)
					$.fn.css = $.fn.cssOriginal;

					$('.full-layout .banner').revolution(
						{
							delay:9000,
							startheight:470,
							startwidth:960,

							navigationType:"bullet",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off
							
							hideThumbs:200,

							navOffsetHorizontal:0,
							navOffsetVertical:-35,

							stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
							stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

							shadow:0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"off",							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus


						});
						
						$('.box-layout .banner').revolution(
						{
							delay:9000,
							startheight:470,
							startwidth:1040,

							navigationType:"bullet",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off
							
							hideThumbs:200,

							navOffsetHorizontal:0,
							navOffsetVertical:-35,

							stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
							stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

							shadow:0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"off",							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus


						});
						
						
						
						$('.portfolio-banner').revolution(
						{
							delay:9000,
							startheight:450,
							startwidth:680,

							navigationType:"bullet",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off
							
							hideThumbs:200,

							navOffsetHorizontal:0,
							navOffsetVertical:-35,

							stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
							stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

							shadow:0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"off",							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus


						});
						
						$('.full-portfolio-banner').revolution(
						{
							delay:9000,
							startheight:470,
							startwidth:980,

							navigationType:"bullet",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar

							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off
							
							hideThumbs:200,

							navOffsetHorizontal:0,
							navOffsetVertical:-35,

							stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
							stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

							shadow:0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"off",							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus


						});
						
						
						

					});



/*-----------------------------------------------------------------------------------*/
/*	TWITTER
/*-----------------------------------------------------------------------------------*/

getTwitters('twitter', {
        id: 'elemisdesign', 
        count: 3, 
        enableLinks: true, 
        ignoreReplies: false,
        template: '<span class="twitterPrefix"><span class="twitterStatus">%text%</span><br /><em class="twitterTime"><a href="http://twitter.com/%user_screen_name%/statuses/%id%">%time%</a></em>',
        newwindow: true
});

getTwitters('twitter-footer', {
        id: 'elemisdesign', 
        count: 2, 
        enableLinks: true, 
        ignoreReplies: false,
        template: '<span class="twitterPrefix"><span class="twitterStatus">%text%</span><br /><em class="twitterTime"><a href="http://twitter.com/%user_screen_name%/statuses/%id%">%time%</a></em>',
        newwindow: true
});

/*-----------------------------------------------------------------------------------*/
/*	FLICKR
/*-----------------------------------------------------------------------------------*/
	
$(document).ready(function($){
	$('.flickr.feed').dcFlickr({
		limit: 15, 
        q: { 
            id: '26979613@N00',
			lang: 'en-us',
			format: 'json',
			jsoncallback: '?'
        },
		onLoad: function(){
			$('.feed .frame a').prepend('<span class="more"></span>');
			$('.feed .frame').mouseenter(function(e) {

            $(this).children('a').children('span').fadeIn(300);
        }).mouseleave(function(e) {

            $(this).children('a').children('span').fadeOut(200);
        });
		}
	});
});	

/*-----------------------------------------------------------------------------------*/
/*	DRIBBBLE
/*-----------------------------------------------------------------------------------*/

$(document).ready(function () {		
		
	$.jribbble.getShotsByPlayerId('tweedlebop', function (playerShots) {
		var html = [];
		
		$.each(playerShots.shots, function (i, shot) {
			html.push('<li class="frame"><a href="' + shot.url + '" target="_blank">');
			html.push('<img class="round" src="' + shot.image_teaser_url + '" ');
			html.push('alt="' + shot.title + '"></a></li>');
		});
		
		$('.dribbble.feed').html(html.join(''));
		
		$('.feed .frame').mouseenter(function(e) {

            $(this).children('a').children('span').fadeIn(200);
        }).mouseleave(function(e) {

            $(this).children('a').children('span').fadeOut(200);
        });

	}, {page: 1, per_page: 20});
	
	
});

/*-----------------------------------------------------------------------------------*/
/*	FORM
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function($){
	$('.forms').dcSlickForms();
});


$(document).ready(function() {
	$('.comment-form input[title]').each(function() {
		if($(this).val() === '') {
			$(this).val($(this).attr('title'));	
		}
		
		$(this).focus(function() {
			if($(this).val() == $(this).attr('title')) {
				$(this).val('').addClass('focused');	
			}
		});
		$(this).blur(function() {
			if($(this).val() === '') {
				$(this).val($(this).attr('title')).removeClass('focused');	
			}
		});
	});
});

/*-----------------------------------------------------------------------------------*/
/*	IMAGE HOVER
/*-----------------------------------------------------------------------------------*/		
		
$(document).ready(function() {	
$('.frame a').prepend('<span class="more"></span>');

});

$(document).ready(function() {
        $('.frame').mouseenter(function(e) {

            $(this).children('a').children('span').fadeIn(300);
        }).mouseleave(function(e) {

            $(this).children('a').children('span').fadeOut(200);
        });
    });	



/*-----------------------------------------------------------------------------------*/
/*	PORTFOLIO GRID
/*-----------------------------------------------------------------------------------*/ 

$(document).ready(function(){
 var $container = $('#portfolio .items');
	$container.imagesLoaded( function(){
		$container.isotope({
			itemSelector : '.item',
			layoutMode : 'fitRows'
		});	
	});
			
	$('.filter li a').click(function(){
		
		$('.filter li a').removeClass('active');
		$(this).addClass('active');
		
		var selector = $(this).attr('data-filter');
		$container.isotope({ filter: selector });
		
		return false;
	});
});

/*-----------------------------------------------------------------------------------*/
/*	VIDEOCASE
/*-----------------------------------------------------------------------------------*/ 

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 0) {
            n = Number(arguments[1]);
            if (n != n) { // shortcut for verifying if it's NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
}

$(document).ready(function(){
 var $container = $('#videocase .items');
	$container.imagesLoaded( function(){
		$container.isotope({
			itemSelector : '.item',
			layoutMode : 'fitRows'
		});	
	});
			
	$('.filter li a').click(function(){
		
		$('.filter li a').removeClass('active');
		$(this).addClass('active');
		
		var selector = $(this).attr('data-filter');
		$container.isotope({ filter: selector });
		
		return false;
	});
	
	
	var _videocontainer = $('#videocontainer');
	var _addressArr = [];
	$('.items li').each(function(index) {
		$(this).attr('rel', index);
		_addressArr[index] = $(this).data('address');
	});
	
	var _descArr = [];
	$('.description li').each(function(index) {
		_descArr[index] = $(this);
		$(this).hide();
		$(this).on('click', function(event) {
		  	alert('click description');
		});
	});
	
	var _currentNum = 0;
	var isInit = false;
	_videocontainer.fitVids();
	
	var _videoArr = [];
	$('.video-item').each(function(index) {
	  	_videoArr[index] = $(this)
		if(index!=0) $(this).hide();
	});
	
	$.address.init(function(event) {
	}).change(function(event) {
		var _address = $.address.value().replace('/', '');
		if(_address){
			if(_address!=""&&_currentNum!=_addressArr.indexOf(_address))loadAsset(_addressArr.indexOf(_address));			
		}else{		
			$.address.path(_addressArr[0]);			
		} 
	})	
	
	
	$('.items li').on('click', function(event) {
		loadAsset($(this).attr('rel'));
		return false;
	});
	
	function loadAsset(n){
		$('html, body').animate({scrollTop: _videocontainer.offset().top-30}, 600);
		_index = n;	   
		var _pv = _videoArr[_currentNum];
		if(_pv)_pv.animate({opacity: 0}, 300, function() {
			var _ph = _pv.height();
			_pv.hide();				
			_pv.remove();
			var _h = _videoArr[_index].show().css('opacity', 0).height();
			_videoArr[_index].css('height', _ph);
			_videoArr[_index].animate({opacity: 1, height: _h}, 600, function() {
				_videoArr[_index].css('height', 'auto');
				_videocontainer.append(_pv);
				//_videocontainer.fitVids();			
			})
		})		
		$.address.path(_addressArr[_index])
		_currentNum = _index;		
		return false;
	}
	
});

/*-----------------------------------------------------------------------------------*/
/*	VIDEO
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function() {
    		jQuery('.video').fitVids();
    	});	

/*-----------------------------------------------------------------------------------*/
/*	FANCYBOX
/*-----------------------------------------------------------------------------------*/

$(document).ready(function() {
			
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
                                       
					arrows : true,
					padding: 10,
					closeBtn: true,
					openEffect : 'fade',
					closeEffect : 'fade',
					prevEffect : 'fade',
					nextEffect : 'fade',
					helpers : {
						media : {},
						buttons	: false,
						thumbs : {
							width  : 50,
							height : 50
						},
						title : {
							type : 'outside'
						},
						overlay : {

                                        closeClick : false,
            				opacity: 0.8
        				}	
					},
					beforeLoad: function() {
            var el, id = $(this.element).data('title-id');

            if (id) {
                el = $('#' + id);
            
                if (el.length) {
                    this.title = el.html();
                }
            }
        }
				});
		});

/*-----------------------------------------------------------------------------------*/
/*	TABS
/*-----------------------------------------------------------------------------------*/
 $(document).ready( function() {
      $('.tabs').easytabs({
	      animationSpeed: 300,
	      updateHash: false
      });
});

/*-----------------------------------------------------------------------------------*/
/*	TOGGLE
/*-----------------------------------------------------------------------------------*/
$(document).ready(function(){
//Hide the tooglebox when page load
$(".togglebox").hide();
//slide up and down when click over heading 2
$("h4").click(function(){
// slide toggle effect set to slow you can set it to fast too.
$(this).toggleClass("active").next(".togglebox").slideToggle("slow");
return true;
});
});

/*-----------------------------------------------------------------------------------*/
/*	MEGAFOLIO
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function() {


					var api=jQuery('.megafolio-container').megafoliopro(
						{
							filterChangeAnimation:"fade",			
							filterChangeSpeed:600,					// Speed of Transition
							filterChangeRotate:99,					// If you ue scalerotate or rotate you can set the rotation (99 = random !!)
							filterChangeScale:0.8,					// Scale Animation Endparameter
							delay:20,
							defaultWidth:980,							
							paddingHorizontal:5,
							paddingVertical:5,
							lazyLoadStartEntry:0,
							layoutarray:[9]		// Defines the Layout Types which can be used in the Gallery. 2-17 or "random". You can define more than one, like {5,2,6,4} where the first items will be orderd in layout 5, the next comming items in layout 2, the next comming items in layout 6 etc... You can use also simple {9} then all item ordered in Layout 9 type.

						});

					jQuery('.order').click(function()  { api.megaremix(jQuery(this).data('order')); });
					jQuery('.filter').click(function() { api.megafilter(jQuery(this).data('category')); });


					
					jQuery(".fancybox").fancybox({

					arrows : true,
					padding: 10,
					closeBtn: true,
					openEffect : 'fade',
					closeEffect : 'fade',
					prevEffect : 'fade',
					nextEffect : 'fade',
					helpers : {
						media : {},
						buttons	: false,
						thumbs : {
							width  : 50,
							height : 50
						},
						title : {
							type : 'outside'
						},
						overlay : {
                                        closeClick : false,
            				opacity: 0.8
        				}	
					}
					
				});
				
				jQuery('.filter').click(function() { 
        api.megafilter(jQuery(this).data('category')); 
        jQuery('.active').each(function() { jQuery(this).removeClass('active')});
        jQuery(this).addClass('active');
     });
					
					jQuery(".pad").change(function() {
						var mc = jQuery('.megafolio-container');
						mc.data('paddingh',jQuery("#ph").val());
						mc.data('paddingv',jQuery("#pv").val());
						api.megaremix(api.megagetcurrentorder());
					});

				});
				
/*-----------------------------------------------------------------------------------*/
/*	BUTTON HOVER
/*-----------------------------------------------------------------------------------*/


jQuery(document).ready(function($)  {
$(".button, .btn-submit").css("opacity","1.0");
$(".button, .btn-submit").hover(function () {
$(this).stop().animate({ opacity: 0.85 }, "fast");  },
function () {
$(this).stop().animate({ opacity: 1.0 }, "fast");  
}); 
});	

jQuery(document).ready(function($)  {
$("ul.client-list li").css("opacity","0.70");
$("ul.client-list li").hover(function () {
$(this).stop().animate({ opacity: 1.0 }, "fast");  },
function () {
$(this).stop().animate({ opacity: 0.70 }, "fast");  
}); 
});

/*-----------------------------------------------------------------------------------*/
/*	SOCIAL TIMELINE
/*-----------------------------------------------------------------------------------*/

$(document).ready(function(){
		
		$('#socialTimeline').dpSocialTimeline({
			feeds: 
			{
				'twitter': {data: 'elemisdesign', limit: 4},
				'dribbble': {data: 'tweedlebop', limit: 4},
				'facebook_page': {data: '107694792658540', limit: 4},
				'flickr': {data: '34681007@N08', limit: 3},
				'pinterest': {data: 'claugos', limit: 3},
				'youtube': {data: 'kimbramusic', limit: 3},
				'vimeo': {data: 'user8456906', limit: 3}
				
			},
			total: 25,
			layoutMode: 'masonry',
			showLayout: false,
			itemWidth: 230,
			skin: 'light'
		});
	});				

/*-----------------------------------------------------------------------------------*/
/*	CAROUSEL
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function($) {
	$('.showbiz-container').showbizpro({       
       containerOffsetRight:5,
       heightOffsetBottom:5
      });
});

/*-----------------------------------------------------------------------------------*/
/*	SELECTNAV
/*-----------------------------------------------------------------------------------*/

$(document).ready(function() {
		
			selectnav('tiny', {
				label: '--- Navigation --- ',
				indent: '-'
			});

			
		});
						
/*-----------------------------------------------------------------------------------*/
/*	MENU
/*-----------------------------------------------------------------------------------*/
ddsmoothmenu.init({
	mainmenuid: "menu",
	orientation: 'h',
	classname: 'menu',
	contentsource: "markup"
})

/*-----------------------------------------------------------------------------------*/
/*	FIX IE BUG
/*-----------------------------------------------------------------------------------*/
document.getElementsByClassName = function(eleClassName){
  var getEleClass = [];
  var myclass = new RegExp("\\b"+eleClassName+"\\b");
  var elem = this.getElementsByTagName("*");
  for(var h=0;h<elem.length;h++){
	var classes = elem[h].className;
	if (myclass.test(classes)) getEleClass.push(elem[h]);
  }
  return getEleClass;
}




/*-----------------------------------------------------------------------------------*/
/*	FIX IE BUG
/*-----------------------------------------------------------------------------------*/

$(document).ready(function() {
               $(".1").fancybox({

                    'autoScale'               : true,
 	            'hideOnOverlayClick':false,  
                    'showCloseButton':true, 
                    'transitionIn'          : 'none',
                    'transitionOut'          : 'none',
                    'type'                    : 'iframe'
               });
     });