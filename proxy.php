<?php
// API请求地址
$apiUrl = 'http://api.xingchenfu.xyz/API/wmsc.php';

// 初始化CURL会话
$ch = curl_init();

// 设置CURL参数
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,                  // 请求地址
    CURLOPT_RETURNTRANSFER => true,          // 结果以字符串返回（而非直接输出）
    CURLOPT_CUSTOMREQUEST => 'GET',          // 明确使用GET请求方式
    CURLOPT_FOLLOWLOCATION => true,          // 自动跟随301/302重定向
    CURLOPT_TIMEOUT => 10,                   // 超时时间（秒），避免长期阻塞
    CURLOPT_SSL_VERIFYPEER => false,         // 关闭SSL证书验证（若API为http可忽略，https需谨慎）
    CURLOPT_SSL_VERIFYHOST => false
]);

// 执行请求并获取响应
$response = curl_exec($ch);
$curlErrNo = curl_errno($ch);
$curlErrMsg = curl_error($ch);

// 关闭CURL会话
curl_close($ch);

// 处理请求结果
if ($curlErrNo !== 0) {
    // CURL请求失败（如网络问题、超时等）
    die("API请求失败：" . $curlErrMsg);
}

// 验证响应是否为MP4（通过响应头或内容特征，此处简化处理）
$responseHeader = curl_getinfo($ch);
if (isset($responseHeader['content_type']) && strpos($responseHeader['content_type'], 'video/mp4') !== false) {
    // 设置响应头，让浏览器识别为MP4文件（可直接播放或下载）
    header("Content-Type: video/mp4");
    header("Content-Length: " . strlen($response));
    // 若需要直接触发下载，可添加以下行（文件名可自定义）
    // header("Content-Disposition: attachment; filename='output.mp4'");
    
    // 输出MP4内容
    echo $response;
} else {
    // 响应非MP4格式（如API返回错误信息、其他数据）
    die("API返回内容非MP4格式，响应内容：" . $response);
}
?>
