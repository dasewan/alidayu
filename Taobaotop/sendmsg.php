<?php
/*$file = fopen("domain3.csv", "r");
while (!feof($file)) {
    var_dump((fgetcsv($file)));
}
fclose($file);*/
/*require './Taobaotop/TopClient.php';
require './Taobaotop/request/AlibabaAliqinFcSmsNumSendRequest.php';*/

function PHPMailerAutoload($classname)
{
    //Can't use __DIR__ as it's only in PHP 5.3+
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR .  strtolower($classname) . '.php';
    echo $filename;
    if (is_readable($filename)) {
        echo $filename;
        require $filename;
    }
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    //SPL autoloading was introduced in PHP 5.1.2
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {

        spl_autoload_register('PHPMailerAutoload', true, true);
    } else {

        spl_autoload_register('PHPMailerAutoload');
    }
} else {
    /**
     * Fall back to traditional autoload for old PHP versions
     * @param string $classname The name of the class to load
     */
    function __autoload($classname)
    {
        PHPMailerAutoload($classname);
    }
}
$mobile = 13156956520;
$code = 123;
$c = new TopClient;
$c->appkey = "23660835";
$c->secretKey = "0255455d3573d91471530603a9d6d7a9";
var_dump($c);
$req = new \Taobaotop\AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend($code);
$req->setSmsType("normal");
$req->setSmsFreeSignName("一手单");
$req->setSmsParam("{\"code\":\"" . $code . "\",\"product\":\"一手单\"}");
$req->setRecNum($mobile);
$req->setSmsTemplateCode('SMS_52235140');
$resp = $c->execute($req);
var_dump($resp);
if (isset($resp->result)) {
    if ($resp->result->success) {
        return "";
    } else {
        return "发送失败";
    }
} else {
    $result = "发送失败";
    if (isset($resp->sub_msg))
        $result = trim($resp->sub_msg);
    if (empty($result) || $result == "")
        return "发送失败";
    else
        return $result;
}