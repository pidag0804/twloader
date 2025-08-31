<?php
//比邻云盘 phpSdkV1.0 2020年9月27日 By Myxf
class ext_bilnnYun{
    private $url = "https://pan.bilnn.com/dav"; //api请求地址
    private $email = "pidag0804@gmail.com"; //登录邮箱地址
    private $secret_key = ""; //webdav接口秘钥
    private $folder = "/"; //上传路径

    public function __construct($email = "",$secret_key = "") {
        $this->email = $email;
        $this->secret_key = $secret_key;
    }

    //列出目录
    public function getDirectory($folder = "/"){
        $response = $this->request($folder);
        if (empty($response) || $response=='Error') return json_encode(['code'=>401,'msg'=>'请检查账号或密码是否正确']);
        $response_array = $this->xmlstr_to_array($response);
        return json_encode(['code'=>200,'msg'=>'文件目录获取成功','backdata'=>['lists'=>$response_array]]);
    }

    //上传文件到云盘
    public function uploadFile($filePath = "", $folder = "/"){
        $filePath = iconv('UTF-8','GB2312',$filePath);
        $fileName = iconv('GB2312','UTF-8',basename($filePath));
        $fileSize = filesize($filePath);

        if (!file_exists($filePath)) return json_encode(['code'=>404,'msg'=>'文件不存在']);
        if (!is_file($filePath)) return json_encode(['code'=>405,'msg'=>'不是有效文件']);
        if ($fileSize==0) return json_encode(['code'=>405,'msg'=>'不能上传空文件']);

        $response = $this->request($folder.$fileName,"PUT",file_get_contents($filePath));
        if ($response!="Created") return json_encode(['code'=>500,'msg'=>'文件上传失败']);
        return json_encode(['code'=>200,'msg'=>'文件上传成功','backdata'=>['filePath'=>$folder.$fileName,'fileName'=>$fileName,'fileSize'=>$fileSize]]);
    }

    //获取文件下载地址
    public function getDownLoadUrl($filePath = ""){
        $_url = $this->url;
        $this->url = str_replace("/dav", "/app/index.php?c=api&a=getDownLoadUrl", $this->url);
        $data = array();
        $data['filePath'] = $filePath;
        $data['secret_key'] = base64_encode($this->email.":".$this->secret_key);
        $response = json_decode($this->request("","POST",$data),true);
        if (@$response['code']!=0) return json_encode(['code'=>500,'msg'=>'外链获取失败']);
        $DownLoadUrl = str_replace("/dav", "", $_url).@$response['backdata']['url'];
        $this->url = $_url;
        return json_encode(['code'=>200,'msg'=>'外链获取成功','backdata'=>['file_name'=>@$response['backdata']['file_name'],'file_size'=>$response['backdata']['file_size'],'url'=>$DownLoadUrl]]);
    }

    //创建文件夹
    public function newFolder($filePath){
        $response = $this->request($filePath,"MKCOL");
        if ($response!="Created") return json_encode(['code'=>500,'msg'=>'文件夹创建失败，可能已存在']);
        return json_encode(['code'=>200,'msg'=>'文件夹创建成功']);
    }

    //删除文件或文件夹
    public function delFile($filePath = ""){
        $_tmp = explode("/", $filePath);
        $filePath = "";
        foreach ($_tmp as $key => $one) {
            if (!empty($one)) $filePath .= "/".urlencode($one);
        }
        $response = $this->request($filePath,"DELETE");
        if ($response=="Not Found") return json_encode(['code'=>404,'msg'=>'文件或目录不存在']);
        return json_encode(['code'=>200,'msg'=>'文件或目录删除成功']);
    }

    //移动或重命名文件/文件夹
    public function move($filePath = "", $toPath = ""){
        $_tmp = explode("/", $filePath);
        $filePath = "";
        foreach ($_tmp as $key => $one) {
            if (!empty($one)) $filePath .= "/".urlencode($one);
        }
        $response = $this->request($filePath,"MOVE","Destination: ".$this->url.$toPath);
        if ($response=="Not Found") return json_encode(['code'=>404,'msg'=>'文件或目录不存在']);
        if ($response=="Bad Request") return json_encode(['code'=>500,'msg'=>'文件操作错误']);
        return json_encode(['code'=>200,'msg'=>'文件操作成功']);
    }



    //网络请求方法
    private function request($folder = "", $method = "PROPFIND", $data = ""){
        $url = $this->url.$folder;
        $header[]  =  "Authorization: Basic ". base64_encode($this->email.":".$this->secret_key);
        if ($method == "MOVE" ) $header[] = $data;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
           return 'Error';
        }
        curl_close($curl);
        return $tmpInfo;
    }

    //Xml To Array
    private function xmlstr_to_array($xmlstr) {
        if ($xmlstr=="Not Found") return [];
        $doc = new DOMDocument();
        $doc->loadXML(urldecode($xmlstr));
        $root = $doc->documentElement;
        $output = $this->domnode_to_array($root);
        $output['@root'] = $root->tagName;
        $xml_array = array();
        foreach ($output['D:response'] as $key => $one) {
            $_array = array();
            $_array['filename'] = str_replace("/dav", "", $one['D:href']);
            $_array['is_folder'] = isset($one['D:propstat']['D:prop']['D:getcontentlength']) ? 0 : 1;
            $_array['filesize'] = $_array['is_folder'] == 1 ? 0 : intval($one['D:propstat']['D:prop']['D:getcontentlength']);
            $_array['lastmodified'] = date('Y-m-d H:i:s',strtotime($one['D:propstat']['D:prop']['D:getlastmodified']));
            $xml_array[] = $_array;
        }
        return $xml_array;
    }

    private function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                    $output = array('@content'=>$output); //Change output into an array.
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
}
?>