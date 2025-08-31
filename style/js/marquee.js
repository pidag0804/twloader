
<!-- Beginning of JavaScript -

// CREDITS:
// Right-To-Left-Scroller width fade-effect by Urs Dudli and Peter Gehrig 
// Copyright (c) 2000 Peter Gehrig and Urs Dudli. All rights reserved.
// Permission given to use the script provided that this notice remains as is.
// Additional scripts can be found at http://www.24fun.com.
// info@24fun.ch
// 6/7/2000

// IMPORTANT: 
// If you add this script to a script-library or script-archive 
// you have to add a link to http://www.24fun.com on the webpage 
// where the scrips will be running.

// Edit the variables below:

// Your messages. Add as many as you like
var message=new Array()
message[0]="WEB CLIPART: collections, tricks, tutorials, free tools from about.com. CLICK HERE."
message[1]="WEB FUN: Link to an about.com expert who can tell you all about JavaScript. CLICK HERE."
message[2]="WEB SPEED: Let SiteTools speed up your load time for free. CLICK HERE."


// the URLs of your messages
var messageurl=new Array()
messageurl[0]="http://www.nii.org"
messageurl[1]="http://www.nii.org"
messageurl[2]="http://www.nii.org"

// the targets of the links
// accepted values are '_blank' or '_top' or '_parent' or '_self'
// or the name of your target-window (for instance 'main')
var messagetarget=new Array()
messagetarget[0]="_blank"
messagetarget[1]="_blank"



messagetarget[2]="_blank"

// font-color
var messagecolor= new Array()
messagecolor[0]="red"
messagecolor[1]="blue"
messagecolor[2]="black"

// distance of the scroller to the left margin of the browser-window (pixels)
var scrollerleft=20

// distance of the scroller to the top margin of the browser-window (pixels)
var scrollertop=20

// width of the scroller (pixels)
var scrollerwidth=800

// height of the scroller (pixels)
var scrollerheight=20

// speed 1: lower means faster
var pause=20

// speed 2: higher means faster
var step=2

// font-size
var fntsize=10


// font-family
var fntfamily="Arial"

// font-weight: 1 means bold, 0 means normal
var fntweight=1

// do not edit the variables below
var fadeimgwidth=60
var fadeimgleftcontent,fadeimgrightcontent
var clipleft,clipright,cliptop,clipbottom
var i_message=0
var timer
var textwidth
var textcontent=""
if (fntweight==1) {fntweight="700"}
else {fntweight="100"}

function init() {
	gettextcontent()
	
	fadeimgleftcontent="<img src='fadeimgleft.gif' width="+fadeimgwidth+" height="+scrollerheight+">"
	fadeimgrightcontent="<img src='fadeimgright.gif' width="+fadeimgwidth+" height="+scrollerheight+">"

    if (document.all) {
		text.innerHTML=textcontent
		fadeimgleft.innerHTML=fadeimgleftcontent
		fadeimgright.innerHTML=fadeimgrightcontent
		textwidth=text.offsetWidth
		document.all.text.style.posTop=scrollertop
        document.all.text.style.posLeft=scrollerleft+scrollerwidth
		document.all.fadeimgleft.style.posTop=scrollertop
        document.all.fadeimgleft.style.posLeft=scrollerleft
		document.all.fadeimgright.style.posTop=scrollertop
        document.all.fadeimgright.style.posLeft=scrollerleft+scrollerwidth-fadeimgwidth

		clipleft=0
		clipright=0
		cliptop=0
		clipbottom=scrollerheight
		document.all.text.style.clip ="rect("+cliptop+" "+clipright+" "+clipbottom+" "+clipleft+")"
        scrolltext()
    }
	if (document.layers) {
		document.text.document.write(textcontent)
		document.text.document.close()
		document.fadeimgleft.document.write(fadeimgleftcontent)
		document.fadeimgleft.document.close()
		document.fadeimgright.document.write(fadeimgrightcontent)
		document.fadeimgright.document.close()
		textwidth=document.text.document.width
		document.text.top=scrollertop
		document.text.left=scrollerleft+scrollerwidth
		document.fadeimgleft.top=scrollertop
        document.fadeimgleft.left=scrollerleft
		document.fadeimgright.top=scrollertop
        document.fadeimgright.left=scrollerleft+scrollerwidth-fadeimgwidth
		
		document.text.clip.left=0
		document.text.clip.right=0
		document.text.clip.top=0
		document.text.clip.bottom=scrollerheight

        scrolltext()
    }
}

function scrolltext() {
    if (document.all) {
		if (document.all.text.style.posLeft>=scrollerleft-textwidth) {
			document.all.text.style.posLeft-=step
			clipright+=step
			if (clipright>scrollerwidth) {
				clipleft+=step
			}
			document.all.text.style.clip ="rect("+cliptop+" "+clipright+" "+clipbottom+" "+clipleft+")"
			
			var timer=setTimeout("scrolltext()",pause)
		}
		else {
			changetext()
		}
	}
   if (document.layers) {
		if (document.text.left>=scrollerleft-textwidth) {
			document.text.left-=step
			document.text.clip.right+=step
			if (document.text.clip.right>scrollerwidth) {
				document.text.clip.left+=step
			}
			var timer=setTimeout("scrolltext()",pause)
		}
		else {
			changetext()
		}
	}
}

function changetext() {
    i_message++
	if (i_message>message.length-1) {i_message=0}
	gettextcontent()
	if (document.all) {
		text.innerHTML=textcontent
		textwidth=text.offsetWidth
		
        document.all.text.style.posLeft=scrollerleft+scrollerwidth
		clipleft=0
		clipright=0
		document.all.text.style.clip ="rect("+cliptop+" "+clipright+" "+clipbottom+" "+clipleft+")"
		
        scrolltext()
	}

	if (document.layers) {
   		document.text.document.write(textcontent)
		document.text.document.close()
		textwidth=document.text.document.width

		document.text.left=scrollerleft+scrollerwidth
		document.text.clip.left=0
		document.text.clip.right=0
		
        scrolltext()
	}
}

function gettextcontent() {
	textcontent="<span style='position:relative;font-size:"+fntsize+"pt;font-family:"+fntfamily+";font-weight:"+fntweight+"'>"
	textcontent+="<a href="+messageurl[i_message]+" target="+messagetarget[i_message]+">"
	textcontent+="<nobr><font color="+messagecolor[i_message]+">"+message[i_message]+"</font></nobr></a></span>"
}

window.onresize=init;

// - End of JavaScript - -->
