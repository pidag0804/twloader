var showFrame = 1;
var totalPrice = 0;
var pageLoad = 0;

function submitform()
{
	$('#mask').hide();
	$('.window').hide();
	document.getElementById('sub').click();
	
	var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
	$body.animate({ scrollTop: $('#Title_buy').offset().top}, 1000, 'easeOutBounce');
	
	if ( !debug ) {
		document.getElementById('sub').disabled = true;
		document.getElementById('btnSubmit').disabled = true;
		document.getElementById('btnSubmit').value = "載入中";
	}
}

function getSelectText(id) {
	return document.getElementById(id).options[document.getElementById(id).selectedIndex].text;
}

function AutoBig(obj) {
	obj.value = obj.value.toUpperCase();
}

function checkForm() {
	if ( pageLoad == 0 ) return;
	document.getElementById('sub').disabled = true;
	
	mList = new Array('mustType', '不能為空',
					  'mustNumber', '必須為數字',
					  'mustEmail', '不符合電郵格式',
					  'mustEngNum', '只能包含英文及數字',
					  'mustTel', '必須為 10 位數字',
					  'mustSevenNum', '必須為 7 位數字',
					  'mustChin', '必須為中文字'
	);
	var checkFunction = window["checkData"];
	var errorMessage = '';
	
	for ( var i = 0; i < mList.length; i+=2) {
		var mArr = document.getElementsByClassName(mList[i]);
		for (var a = 0; a < mArr.length; a++){
	                  //if ( checkFunction ( mArr[a].id , i/2 ) == 0 ){
				var parentID = mArr[a].parentNode.parentNode.parentNode.parentNode.id;
				if ( parentID != 'frmLogin' && checkFunction ( mArr[a].id , i/2) == 0 )
					errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
		}
	}
	//errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
	if ( errorMessage != '' ) {
		alert ( "發生錯誤\n\n" + errorMessage ) ;
	} else {
		var confirmHtml = ""
		for ( var i = 1; i <= showFrame; i++) {
			confirmHtml += "遊戲帳戶 (" + i + "): " + document.getElementById('game_id_' + i).value + "<br />";
			confirmHtml += "購買類型 (" + i + "): " + getSelectText('plan_' + i) + " - " + getSelectText('limit_' + i) + "<br /><br />";
			confirmHtml += !isdefaultValue('pay_caption', 1) ? "收支備考: " + document.getElementById('pay_caption').value + "<br />" : "";
			confirmHtml += "聯絡電話: " + document.getElementById('tel').value + "<br />";
			confirmHtml += !isdefaultValue('email', 1) ? "Email: " + document.getElementById('email').value + "<br />" : "";
			//confirmHtml += !isdefaultValue('agent', 1) ? "推薦人: " + document.getElementById('agent').value + "<br />" : "";
			//confirmHtml += !isdefaultValue('message', 1) ? "<br />備註: <br />" + document.getElementById('message').value : "";
		}
		document.getElementById('confirm_price').innerHTML = calMoney();
		document.getElementById('confirmBox').innerHTML = confirmHtml;
		
		document.getElementById('sub').disabled = false;
		showModal("#dlg_ApplyConfirm");
	}
}

function calMoney() {
	var PriceList = new Array();
	totalPrice = 0;
	PriceList[1] = new Array(230, 460, 600, 830, 1060, 1200, 1430, 1660, 1890, 2120, 2350, 2400;
	PriceList[2] = new Array(260, 730);
	
	for (var i = 1; i <= showFrame; i++) {
		var planID = document.getElementById('plan_' + i).value;
		var limitID = document.getElementById('limit_' + i).value;
		if ( planID == 0 ) continue;
		totalPrice += PriceList[planID][limitID];
	}
	//if ( document.getElementById('payMethod').value == 1 || document.getElementById('payMethod').value == 5 )
	totalPrice += 30;
	document.getElementById('showPrice').innerHTML = totalPrice;
	document.getElementById('payPrice').value = totalPrice;
	
	return totalPrice;
}

function editFrame(act) {
	switch (act) {
		case "add":
			if (showFrame >= 4) return;
			showFrame++;
			document.getElementById('Frame_' + showFrame).style.display = 'block';
			if ( showFrame > 1 ) document.getElementById('btnDel').style.display = 'block';
			break;
		case "del":
			if (showFrame <= 1) return;
			document.getElementById('Frame_' + showFrame).style.display = 'none';
			showFrame--;
			if ( showFrame <= 1 ) document.getElementById('btnDel').style.display = 'none';
			if ( showFrame < 4 ) document.getElementById('btnAdd').style.display = 'block';
			break;
	}
	document.getElementById('total_id').value = showFrame;
	calMoney();
}

function switchplan(Frameid) {
	var getID = document.getElementById('plan_' + Frameid).value;
	var targetSelect = document.getElementById('limit_' + Frameid);
	var i;
	
	for (i = targetSelect.length - 1; i>=0; i--)
	  targetSelect.remove(i);
	
	if ( getID == 1 ) {
		for ( i = 0; i < 12; i++ ) {
			var new_option = new Option(i+1+ ' 個月', i);	  
			targetSelect.options.add(new_option);
		}
	} else if ( getID == 2 ) {
		var new_option = new Option("400 次", 0);
		targetSelect.options.add(new_option);
		var new_option = new Option("1200 次", 1);
		targetSelect.options.add(new_option);
	} else if ( getID == 0 ) {
		var new_option = new Option("---", 0);
		targetSelect.options.add(new_option);
	}
	calMoney();
}

function selectPlan() {
	var payMethodID = document.getElementById('payMethod').value;
	var PayCaptionList = new Array('', '', '真實姓名 (中文)', '真實姓名 (中文)', '金融卡最後 7 位數字', '');
	var PayCaptionClassList = new Array('', '', 'mustType mustChin', 'mustType mustChin', 'mustType mustSevenNum', '');
	var PayCaption = PayCaptionList[payMethodID];
	
	if ( payMethodID > 1 ) document.getElementById('pay_caption').value = DefaultValueList['pay_caption'];
	document.getElementById('pay_caption').className = "text-input defaultText " + PayCaptionClassList[payMethodID];
	document.getElementById('pay_caption_label').innerHTML = PayCaption;
	document.getElementById('pay_caption_row').style.display = ( PayCaption != '' ) ? 'block' : 'none';
	calMoney();
}