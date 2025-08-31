/**************************************************************************
 * jQuery Plugin for Fullwidth Slider
 * @version: 1.0
 * @requires jQuery v1.8 or later
 * @author Krisztian Horvath
**************************************************************************/

jQuery(document).ready(function() {
	initFullWidthSilder();
});

	function initFullWidthSilder() {


		jQuery('.fullwidth_slider').each(function() {
			// CATCH ALL THE ENTRIES

			var fwslider = jQuery(this);
			fwslider.find('.fs-entry').each(function() {
					var ent=jQuery(this);


					// WRAP THE ENTRIES IN DIVS
					ent.append('<div class="image_wrapper"><div class="image_innerwrapper"><div class="imageholder"></div></div></div>');

					// SET THE BG OF THE CONTAINERS
					ent.find('.imageholder').css({'background-image':'url('+ent.data("src")+')'});

					// ON HOVER WE HIDETHE OTHER ENTRIES
					ent.find('.seemore').hover(
					function() {
						if (jQuery('.fullwidth_slider').data('drag') != 1)
							jQuery('.fullwidth_slider .fs-entry').each(function() {
								jQuery(this).addClass("notselected");
								ent.removeClass("notselected");
							})
					},
					function() {
						jQuery('.fullwidth_slider .fs-entry').each(function() {
							jQuery(this).removeClass("notselected");
						})
					})

					ent.find('.seemore').click(function() {
						var ent=jQuery(this).parent();

						jQuery('.fullwidth_slider .fs-entry').each(function() {
							jQuery(this).removeClass("notselected").addClass("allnotselected").removeClass('selected');
						});
						ent.removeClass('allnotselected').addClass("selected");
						var mi=ent.closest('.slider_wrapper').find('.fs-moreinfo');

						mi.slideUp(300);
						setTimeout(function() { mi.html(ent.data('content'));},300);
						mi.delay(100).slideDown(500);
					})
			});


			// CALL THE SWIPE FUNCTION TO THE ITEM
			fwslider.parent().overscroll({
				cancelOn: '.no-drag',
				hoverThumbs: true,
				//persistThumbs: true,
				showThumbs: false,
				scrollLeft: 300,
				direction:"horizontal",
				wheelDirection:"vertical",
				scrollDelta:5.7,
				scrollLeft: 0,
				captureThreshold:1,
				driftDecay:1.1,
				driftTimeout: 100
			}).on('overscroll:dragstart overscroll:driftstart', function(event){

				jQuery('.fullwidth_slider .fs-entry').each(function() {
							jQuery(this).removeClass("notselected");
						})
				fwslider.data('drag',1);
			}).on('overscroll:dragend overscroll:driftend', function(event){
								fwslider.data('drag',0);
			});

		});

		// RESIZE THE ENTRIES, AND TAN RESIZE EVERY TIME WE NEED
		resizeFullWidthSlider();
		jQuery(window).resize(function() {
			resizeFullWidthSlider();
		});
	}

	///////////////////////////////////
	// THE FULLWIDTH SLIDER RESIZING //
	///////////////////////////////////
	function resizeFullWidthSlider() {
		jQuery('.fullwidth_slider').each(function() {
			var l=2;
			var t=0;
			var fwslider=jQuery(this);

			// WIDTH OF THE SCREEN
			var sw=jQuery(window).width();

			// THE DIMENSION OF THE CURRENT ITEM
			var ww=0;
			var hh=0;


			// THE HEIGHT OF THE FULLWIDTH SLIDER
			var fwheight = 220;
			var fwwidth = 280;

			// THE X OFFSET BETWEEN THE IMAGES !
			var xoffset=10;

			if (sw<1200) {
				var prop = (sw/1200)*1.3;
				if (prop>1) prop=1;
				fwheight=Math.round(fwheight*prop);
				fwwidth=Math.round(fwwidth*prop);
			}

			// SET THE RIGHT HEIGHT OF THE ELEMENT
			fwslider.height(fwheight);

			jQuery(this).find('.fs-entry').each(function() {
					var ent=jQuery(this);

					// SIZING THE BOXES
					if (ent.hasClass("fs-maxw")) ww=fwwidth;
					if (ent.hasClass("fs-maxh")) hh=fwheight;

					// POSITION OF THE ITEMS
					ent.css({'width':ww+"px", 'height':hh+"px",'left':l+"px", 'top':t+"px"});

					// REPOSITION THE NEXT ITEM

					if (t+ent.height()<fwheight-4)
						t=t+ent.height();
					else
						{
							t=0;
							l=l+ent.width()+xoffset;
						}
					fwslider.width(l);
			})
		})
	  }

/**
 * Overscroll v1.6.4
 *  A jQuery Plugin that emulates the iPhone scrolling experience in a browser.
 *  http://azoffdesign.com/overscroll
 *
 * Intended for use with the latest jQuery
 *  http://code.jquery.com/jquery-latest.js
 *
 * Copyright 2012, Jonathan Azoff
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *  http://jquery.org/license
 *
 * For API documentation, see the README file
 *  http://azof.fr/pYCzuM
 *
 * Date: Thursday, October 10 2012
 */

(function(e,t,n,r,i,s,o,u,a){"use strict";var f="overscroll",l=function(){var r=u.browser,s,o=n.userAgent,c=t.createElement(f).style,h=r.webkit?"webkit":r.mozilla?"moz":r.msie?"ms":r.opera?"o":"",p=h?["-","-"].join(h):"";l={prefix:h,overflowScrolling:false};u.each(h?[h,""]:[h],function(t,n){var r=n?n+"RequestAnimationFrame":"requestAnimationFrame",i=n?n+"OverflowScrolling":"overflowScrolling";if(e[r]!==a){l.animate=function(t){e[r].call(e,t)}}if(c[i]!==a){if(o.indexOf("Chrome")<0){l.overflowScrolling=p+"overflow-scrolling"}}});l.touchEvents="ontouchstart"in e;if(!l.animate){l.animate=function(e){i(e,1e3/60)}}if(h==="moz"||h==="webkit"){l.cursorGrab=p+"grab";l.cursorGrabbing=p+"grabbing"}else{s="https://mail.google.com/mail/images/2/";l.cursorGrab="url("+s+"openhand.cur), default";l.cursorGrabbing="url("+s+"closedhand.cur), default"}return l}(),c={drag:"mousemove touchmove",end:"mouseup mouseleave click touchend touchcancel",hover:"mouseenter mouseleave",ignored:"select dragstart drag",scroll:"scroll",start:"mousedown touchstart",wheel:"mousewheel DOMMouseScroll"},h={captureThreshold:3,driftDecay:1.1,driftSequences:22,driftTimeout:100,scrollDelta:15,thumbOpacity:.7,thumbThickness:6,thumbTimeout:400,wheelDelta:20},p={cancelOn:"select,input,textarea",direction:"multi",dragHold:false,hoverThumbs:false,scrollDelta:h.scrollDelta,showThumbs:true,persistThumbs:false,wheelDelta:h.wheelDelta,wheelDirection:"vertical",zIndex:999},d=function(e,t){t.trigger("overscroll:"+e)},v=function(){return(new Date).getTime()},m=function(e,t,n){t.x=e.pageX;t.y=e.pageY;t.time=v();t.index=n;return t},g=function(e,t,n,r){var i,s;if(e&&e.added){if(e.horizontal){i=n*(1+t.container.width/t.container.scrollWidth);s=r+t.thumbs.horizontal.top;e.horizontal.css("margin",s+"px 0 0 "+i+"px")}if(e.vertical){i=n+t.thumbs.vertical.left;s=r*(1+t.container.height/t.container.scrollHeight);e.vertical.css("margin",s+"px 0 0 "+i+"px")}}},y=function(e,t,n){if(e&&e.added&&!t.persistThumbs){if(n){if(e.vertical){e.vertical.stop(true,true).fadeTo("fast",h.thumbOpacity)}if(e.horizontal){e.horizontal.stop(true,true).fadeTo("fast",h.thumbOpacity)}}else{if(e.vertical){e.vertical.fadeTo("fast",0)}if(e.horizontal){e.horizontal.fadeTo("fast",0)}}}},b=function(e){var t,n="events";var r=u._data?u._data(e[0],n):e.data(n);if(r&&r.click){t=r.click.slice();e.off("click").one("click",function(n){u.each(t,function(t,n){e.click(n)});return false})}},w=function(e){var t=e.data,n=t.thumbs,r=t.options,i=e.type==="mouseenter";y(n,r,i)},E=function(e){var t=e.data;if(!t.flags.dragged){g(t.thumbs,t.sizing,this.scrollLeft,this.scrollTop)}},S=function(e){e.preventDefault();var t=e.data,n=t.options,r=t.sizing,i=t.thumbs,s=t.wheel,o=t.flags,u,a=e.originalEvent;o.drifting=false},x=function(e){e.preventDefault();var t=e.data,n=e.originalEvent.touches,r=t.options,i=t.sizing,s=t.thumbs,o=t.position,u=t.flags,a=t.target.get(0);if(l.touchEvents&&n&&n.length){e=n[0]}if(!u.dragged){y(s,r,true)}u.dragged=true;if(r.direction!=="vertical"){a.scrollLeft-=e.pageX-o.x}if(t.options.direction!=="horizontal"){a.scrollTop-=e.pageY-o.y}m(e,t.position);if(--t.capture.index<=0){t.target.data(f).dragging=u.dragging=true;m(e,t.capture,h.captureThreshold)}g(s,i,a.scrollLeft,a.scrollTop)},T=function(e,t,n){var r=t.data,i,s,o,u,a=r.capture,f=r.options,c=r.sizing,p=r.thumbs,m=v()-a.time,y=e.scrollLeft,b=e.scrollTop,w=h.driftDecay;if(m>h.driftTimeout){return n(r)}i=f.scrollDelta*(t.pageX-a.x);s=f.scrollDelta*(t.pageY-a.y);if(f.direction!=="vertical"){y-=i}if(f.direction!=="horizontal"){b-=s}o=i/h.driftSequences;u=s/h.driftSequences;d("driftstart",r.target);r.drifting=true;l.animate(function E(){if(r.drifting){var t=1,i=-1;r.drifting=false;if(u>t&&e.scrollTop>b||u<i&&e.scrollTop<b){r.drifting=true;e.scrollTop-=u;u/=w}if(o>t&&e.scrollLeft>y||o<i&&e.scrollLeft<y){r.drifting=true;e.scrollLeft-=o;o/=w}g(p,c,e.scrollLeft,e.scrollTop);l.animate(E)}else{d("driftend",r.target);n(r)}})},N=function(e){var t=e.data,n=t.target,r=t.start=u(e.target),i=t.flags;i.drifting=false;if(r.size()&&!r.is(t.options.cancelOn)){if(!l.touchEvents){e.preventDefault()}n.css("cursor",l.cursorGrabbing);n.data(f).dragging=i.dragging=i.dragged=false;if(t.options.dragHold){u(document).on(c.drag,t,x)}else{n.on(c.drag,t,x)}t.position=m(e,{});t.capture=m(e,{},h.captureThreshold);d("dragstart",n)}},C=function(e){var t=e.data,n=t.target,r=t.options,i=t.flags,s=t.thumbs,o=function(){if(s&&!r.hoverThumbs){y(s,r,false)}};if(r.dragHold){u(document).unbind(c.drag,x)}else{n.unbind(c.drag,x)}if(t.position){d("dragend",n);if(i.dragging){T(n.get(0),e,o)}else{o()}}if(i.dragging&&t.start.is(e.target)){b(t.start)}n.data(f).dragging=t.start=t.capture=t.position=i.dragged=i.dragging=false;n.css("cursor",l.cursorGrab)},k=function(e){e=u.extend({},p,e);if(e.direction!=="multi"&&e.direction!==e.wheelDirection){e.wheelDirection=e.direction}e.scrollDelta=r.abs(e.scrollDelta);e.wheelDelta=r.abs(e.wheelDelta);e.scrollLeft=e.scrollLeft===a?null:r.abs(e.scrollLeft);e.scrollTop=e.scrollTop===a?null:r.abs(e.scrollTop);return e},L=function(e){var t=u(e),n=t.width(),r=t.height(),i=n>=e.scrollWidth?n:e.scrollWidth,s=r>=e.scrollHeight?r:e.scrollHeight,o=i>n||s>r;return{valid:o,container:{width:n,height:r,scrollWidth:i,scrollHeight:s},thumbs:{horizontal:{width:n*n/i,height:h.thumbThickness,corner:h.thumbThickness/2,left:0,top:r-h.thumbThickness},vertical:{width:h.thumbThickness,height:r*r/s,corner:h.thumbThickness/2,left:n-h.thumbThickness,top:0}}}},A=function(e,t){var n=u(e),r,i=n.data(f)||{},s=n.attr("style"),o=t?function(){i=n.data(f);r=i.thumbs;if(s){n.attr("style",s)}else{n.removeAttr("style")}if(r){if(r.horizontal){r.horizontal.remove()}if(r.vertical){r.vertical.remove()}}n.removeData(f).off(c.start,N).off(c.end,C).off(c.ignored,false)}:u.noop;return u.isFunction(i.remover)?i.remover:o},O=function(e,t){return{position:"absolute",opacity:t.persistThumbs?h.thumbOpacity:0,"background-color":"black",width:e.width+"px",height:e.height+"px","border-radius":e.corner+"px",margin:e.top+"px 0 0 "+e.left+"px","z-index":t.zIndex}},M=function(e,t,n){var r="<div/>",i={},s=false;if(t.container.scrollWidth>0&&n.direction!=="vertical"){s=O(t.thumbs.horizontal,n);i.horizontal=u(r).css(s).prependTo(e)}if(t.container.scrollHeight>0&&n.direction!=="horizontal"){s=O(t.thumbs.vertical,n);i.vertical=u(r).css(s).prependTo(e)}i.added=!!s;return i},_=function(e,t){t=k(t);var n=L(e),r,i={options:t,sizing:n,flags:{dragging:false},remover:A(e,true)};if(n.valid){i.target=e=u(e).css({position:"relative",overflow:"hidden",cursor:l.cursorGrab}).on(c.start,i,N).on(c.end,i,C).on(c.scroll,i,E).on(c.ignored,false);if(t.dragHold){u(document).on(c.end,i,C)}else{i.target.on(c.end,i,C)}if(t.scrollLeft!==null){e.scrollLeft(t.scrollLeft)}if(t.scrollTop!==null){e.scrollTop(t.scrollTop)}if(t.showThumbs){i.thumbs=r=M(e,n,t);if(r.added){g(r,n,e.scrollLeft(),e.scrollTop());if(t.hoverThumbs){e.on(c.hover,i,w)}}}e.data(f,i)}},D=function(e){A(e)()},P=function(e){return this.removeOverscroll().each(function(){_(this,e)})},H=function(e){return this.removeOverscroll().each(function(){var t=u(this).data(f,{remover:A(this)}).css(l.overflowScrolling,"touch").css("overflow","auto");e=k(e);if(e.scrollLeft!==null){t.scrollLeft(e.scrollLeft)}if(e.scrollTop!==null){t.scrollTop(e.scrollTop)}})},B=function(){return this.each(function(){D(this)})};P.settings=h;u.extend(o,{overscroll:l.overflowScrolling?H:P,removeOverscroll:B})})(window,document,navigator,Math,setTimeout,clearTimeout,jQuery.fn,jQuery)