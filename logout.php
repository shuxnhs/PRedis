<?php
/**
 * Created by PhpStorm.
 * User: 何晓宏
 * Date: 2018/10/28
 * Time: 15:56
 */

session_start();

session_destroy();
echo "<script>alert('已注销登陆，正在跳转');  location.href='index.html' </script>";


?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>注销登陆</title>
</head>
</html>

