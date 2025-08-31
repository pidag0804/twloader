var DefaultValueList = new Array();
var pageLoad = 0;

var mList = new Array('mustType', '不能為空',
					  'mustNumber', '必須為數字',
					  'mustEmail', '不符合電郵格式',
					  'mustEngNum', '只能包含英文及數字',
					  'mustTel', '必須為 10 位數字',
					  'mustSevenNum', '必須為 7 位數字',
					  'mustChin', '必須為中文字',
					  'mustPass', '必須為 4-30 位英文或數字'
);

function initPage() {
        var deValArr = document.getElementsByClassName("defaultText");
        for (i = 0; i < deValArr.length; i++)
           DefaultValueList[deValArr[i].id] = deValArr[i].value;
        pageLoad = 1;
}

function checkData( dataID, functionNumber ) {
    var dataValue = document.getElementById(dataID).value;
    var DefaultValue = isdefaultValue(dataID);
    var RegExpArray = new Array(0, /^[\d]+$/, /^([\w.]+)@([\w.]+)/, /^[\d|a-zA-Z]+$/, /^(\d{10})+$/, /^(\d{7})+$/, /^[\W]+$/, /^[\d|a-zA-Z]{4,30}$/);
    
    if ( ( /^game_id_[1-4]/.test(dataID) || /^plan_[1-4]/.test(dataID) ) && dataID.charAt(dataID.length-1) > showFrame ) return 1;
    if ( DefaultValue ) return ( functionNumber > 0 );
    if ( functionNumber > 0 )
    return RegExpArray[functionNumber].test(dataValue);
    //return XRegExp.test(dataValue, RegExpArray[functionNumber]);
}

function isdefaultValue(id, fix) {
    var result = (document.getElementById(id).value == DefaultValueList[id] || document.getElementById(id).value == "");
    if ( fix && result == 1 ) document.getElementById(id).value = "";
    return result;
}

