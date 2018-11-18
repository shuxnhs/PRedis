<?php
/**
 * Created by IntelliJ IDEA.
 * User: 何晓宏
 * Date: 2018/11/13
 * Time: 12:49
 */

    include_once "PhpRedis.php";
    $redis=new PhpRedis();
    session_start();
    session_destroy();
	header('Content-type:text/html;charset=utf-8');
    $host=$_POST['host'];
    $port=$_POST['port'];


	if(isset($_POST['login']))   {
        {
            $conn=$redis->connect("$host","$port");

            //if(stream_socket_client("$host:$port", $errno, $errstr, 5)){
            if($conn){
                echo "<script language=javascript> window.location.href='show.php'</script>";
                session_start();
                $_SESSION['state']= 'yes';
                $_SESSION['host']= $host;
                $_SESSION['port']= $port;

            }else{


                echo "<script>alert('连接redis服务器失败');  location.href='index.html' </script>";

            }



	}

}
?>

