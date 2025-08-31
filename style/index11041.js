mui.init({swipeBack: false
,gestureConfig: {tap:true,doubletap:true,longtap:true,hold:true,release:true}});

var 自由面板1 = new 自由面板("自由面板1","454px");
var 按钮1 = new 按钮("按钮1",按钮1_被单击,null,null);
var 网络操作1 = new 网络操作("网络操作1",网络操作1_发送完毕);

function 按钮1_被单击(){

	网络操作1.置附加请求头({"Content-Type":"application/json;charset=UTF-8"});
	网络操作1.发送网络请求("01ZZHYZV7OZ23V4F6ZYZB3QZSXBM2RNB4Y"},5000);
}

function 网络操作1_发送完毕(发送结果,返回信息){

	var 文本;
	文本 = 返回信息;

	var json=转换操作.文本转json(文本);
    窗口操作.切换窗口(json.content.downloadUrl);

}