<?php
session_start();
if(isset($_SESSION['state'])){

}else{
    echo "<script>alert('请登陆'); window.location.href='index.html'</script>";
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>DATABASE</title>
    <link rel="stylesheet" href="Public/css/codemirror.css">
    <script src="Public/js/codemirror.js"></script>

    <link rel="stylesheet" href="Public/css/bootstrap.min.css">
    <link rel="stylesheet" href="Public/css/eclipse.css">



    <link rel="stylesheet" href="Public/css/show-hint.css">
    <script src="Public/js/show-hint.js"></script>

    <script src="Public/js/sql-hint.js"></script>
    <script src="Public/js/sql.js"></script>
    <script src="Public/js/matchbrackets.js"></script>






</head>

<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="teach.html">帮助</a></li>
                <li role="presentation"><a href="https://github.com/shuxnhs/PRedis">反馈</a></li>
                <li role="presentation"><a href="logout.php" ">退出</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">PRedis version-1.0.1</h3>
    </div>


    <h1 class="text-center page-header">请阅读帮助后输入语句</h1>
    <div class="jumbotron">
        <form action="show.php" method="post">
            <h3 style="display: inline-block ; float: left">请输入</h3>
            <p style="float: right"><input type="submit" name="submit" id="submit" value="运行" class="btn btn-lg btn-success"  ></p>
            <div><textarea class="form-control" id="sql" name="sql">set, hello, world</textarea></div>
        </form>



    </div>





    <div class="row marketing">
        <div class="col-lg-6">
            <h4>结果:</h4>
            <?php
            include_once "PhpRedis.php";
            $redis= new PhpRedis();
            $host=$_SESSION['host'];
            $port=$_SESSION['port'];
            try {
                $redis->connect($host, $port);
            } catch (PhpRedisException $e) {

                echo "<script>alert('请登陆'); window.location.href='index.html'</script>";

            }

            if(isset($_POST['sql'])){

                $sql =$_POST['sql'];
                $arr=explode(", ",$sql);


                try {

                    var_dump(call_user_func_array(array($redis,"exec"),array_values($arr)));


                } catch (PhpRedisException $e) {

                    echo "请根据帮助输入正确的语句";
                }



            }






            ?>


        </div>


    </div>



</div>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("sql"), {
        mode:"text/x-sql",
        theme:"eclipse",          //设置主题
        indentWithTabs: true,     //缩进
        smartIndent: true,        //自动缩进
        lineNumbers: true,        //显示行号
        matchBrackets : true,     //括号匹配
        autofocus: true
    });
    editor.setSize('1020px','100px');
    editor.setvalue("show databases;");
    var text=editor.getValue();

    $('form').on('submit',function (e) {
        editor.save();

    })
</script>

</body>
</html>


