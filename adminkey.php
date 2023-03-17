<?php
// 设置登录此页面需要用户名和密码
$user = 'lang';
$pass = 'lang';

// 检查是否已经输入了用户名和密码
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
    || $_SERVER['PHP_AUTH_USER'] !== $user || $_SERVER['PHP_AUTH_PW'] !== $pass) {

    // 如果没有输入用户名和密码，或者用户名和密码不匹配，则提示用户进行身份验证
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo '您需要输入用户名和密码才能访问此页面。';
    exit;
}

// 如果用户已经通过身份验证，则显示页面内容
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>更新 API Key 和 URL</title>
    <style>
        a.custom-link {
            text-decoration: none;
            color: inherit; /* 继承父元素的文字颜色 */
        }
    </style>
</head>
<body>
    <h1>更新 API Key</h1>
    
    <form method="post">
        <label for="api_key">API Key:</label>
        <input type="text" name="api_key" id="api_key" value="<?php echo htmlspecialchars($OPENAI_API_KEY); ?>">
        <button type="submit">提交更新</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_key'])) {
        $new_api_key = $_POST['api_key'];
        $file_path = 'stream.php';
        $file_contents = file_get_contents($file_path);
        $new_file_contents = preg_replace('/\$OPENAI_API_KEY\s*=\s*".*?";/', "\$OPENAI_API_KEY = \"$new_api_key\";", $file_contents);
        file_put_contents($file_path, $new_file_contents);
        echo '<p>API key 更新成功!</p>';
    }
    ?>
    <br><br>
    <h1>更新 代理</h1>
    <p>使用官方代理接口的话 搭建服务器环境必须要在国外 可以访问官方代理接口的服务器或者虚拟主机</p>
  <p>不使用代理接口的话 服务器环境可以在国内服务器或者虚拟主机 无限制 因为是代理不确定 响应速度如果人多使用的话 也会受到影响 也有不稳定因素 建议自己搭建代理 （支持自定义代理url） </p>
<form method="post">
  <label for="url">代理URL:</label>
  
  <select name="url" id="url">
    <option value="https://api.openai.com/v1/chat/completions">官方代理接口</option>
    <option value="https://service-ht4mbn8k-1315790278.hk.apigw.tencentcs.com/">网友代理接口</option>
    <option value="https://api.1re.ren/v1/chat/completions">枭的私人接口</option>
    <option value="custom">自定义</option>
  </select>
  <input type="text" name="custom_url" id="custom_url" style="display: none;" placeholder="输入自定义URL">
  <button type="submit">提交更新</button>
</form>

<script>
  document.getElementById('url').addEventListener('change', function() {
    var custom_url_input = document.getElementById('custom_url');
    if (this.value === 'custom') {
      custom_url_input.style.display = 'block';
      custom_url_input.required = true;
    } else {
      custom_url_input.style.display = 'none';
      custom_url_input.required = false;
    }
  });
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
  if ($_POST['url'] === 'custom' && isset($_POST['custom_url'])) {
    $new_url = $_POST['custom_url'];
  } else {
    $new_url = $_POST['url'];
  }
  $file_path = 'stream.php';
  $file_contents = file_get_contents($file_path);
  $new_file_contents = preg_replace('/curl_setopt\(\$ch, CURLOPT_URL, \'(.*?)\'\);/', "curl_setopt(\$ch, CURLOPT_URL, '$new_url');", $file_contents);
  file_put_contents($file_path, $new_file_contents);
  echo '<p>URL 更新成功!</p>';
}
?>

</body>
</html>
