function loadContent(paramData, returnAddr){
	$.fancybox.showLoading();
	document.getElementById(returnAddr).innerHTML = "<i class='icon-hourglass'></i>"
	
	$("#"+returnAddr).load("a_ajax.php" + paramData, function(response, status, xhr) {
		if (status == "error") {
			var errorContent =  xhr.statusText + " (" + xhr.status + ")";
			document.getElementById(returnAddr).innerHTML = "<i>通訊錯誤，請重試</i>"
			$.fancybox.hideLoading();
			return;
		}
		$.fancybox.hideLoading();
		//alert(response);
	});	
}

function pauseHide(ms){
	setTimeout($.fancybox.hideLoading, ms);
}

function get_innerHTML(ElementId){ return document.getElementById(ElementId).innerHTML; }

function get_value(ElementId){
	var tmpValue;
	tmpValue = $.trim(document.getElementById(ElementId).value);
	tmpValue = tmpValue.replace(/[,`~!@#$%^&*':;><|.\ /=]/g, "");
	return tmpValue;
}

function get_value_ue(ElementId){
	var tmpValue;
	tmpValue = $.trim(document.getElementById(ElementId).value);
	tmpValue = tmpValue.replace(/[,`~!#$%^&*':;><|\ /=]/g, "");
	return encodeURI(tmpValue);
}