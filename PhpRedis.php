<?php
/**
 * Created by PhpStorm.
 * User: shuxnhs
 * Date: 18-11-16
 * Time: 下午10:27
 */
class PhpRedisException extends Exception{}


class PhpRedis
{
    protected $conn = NULL;

    protected $command = NULL;

    protected $isPipeline = FALSE;

    protected $pipelineCmd = '';

    protected $pipelineCount = 0;

    protected $response = '';

    public function connect($host = '127.0.0.1', $port = 6379, $timeout = 0)
    {
        $this->conn = stream_socket_client("tcp://$host:$port", $errno, $errstr, $timeout);
        if (!$this->conn)
        {
            throw new PhpRedisException("无法连接redis服务器：$errstr", $errno);
        }
        return $this->conn;
    }

    public function checkConnect($chost,$cport, $timeout = 0){
        $this->conn = stream_socket_client("tcp://$chost:$cport", $errno, $errstr, $timeout);
        if (!$this->conn)
        {
            throw new PhpRedisException("无法连接redis服务器：$errstr", $errno);
        }
        return $this->conn;

    }




    protected function _makeCommand($args)
    {
        $cmds = array();
        $cmds[] = '*' . count($args) . "\r\n";
        foreach($args as $arg)
        {
            $cmds[] = '$' . strlen($arg) . "\r\n$arg\r\n";
        }

        $this->command = implode($cmds);
    }

    protected function _fmtResult()
    {
        if ($this->response[0] == '-')
        {
            $this->response = ltrim($this->response, '-');
            list($errstr, $this->response) = explode("\r\n", $this->response, 2);
            throw new PhpRedisException($errstr, 500);
        }

        switch($this->response[0])
        {
            case '+':
            case ':':
                list($ret, $this->response) = explode("\r\n", $this->response, 2);
                $ret = substr($ret, 1);
                break;
            case '$':
                $this->response = ltrim($this->response, '$');
                list($slen, $this->response) = explode("\r\n", $this->response, 2);
                $ret = substr($this->response, 0, intval($slen));
                $this->response = substr($this->response, 2 + $slen);
                break;
            case '*':
                $ret = $this->_resToArray();
                break;
        }

        return $ret;
    }

    protected function _resToArray()
    {
        $ret = array();
        $this->response = ltrim($this->response, '*');
        list($count, $this->response) = explode("\r\n", $this->response, 2);
        for($i = 0; $i < $count; $i++)
        {
            $tmp = $this->_fmtResult();
            $ret[] = $tmp;
        }
        return $ret;
    }

    protected function _fetchResponse()
    {

        $this->response = fread($this->conn, 8196);
        stream_set_blocking($this->conn, 0); // 设置连接为非阻塞
        // 继续读取返回结果
        while($buf = fread($this->conn, 8196))
        {
            $this->response .= $buf;
        }
        stream_set_blocking($this->conn, 1); // 恢复连接为阻塞
    }

    public function exec()
    {
        if (func_num_args() == 0)
        {
            throw new PhpRedisException("参数不可以为空", 301);
        }
        $this->_makeCommand(func_get_args());

        if (TRUE === $this->isPipeline)
        {
            $this->pipelineCmd .= $this->command;
            $this->pipelineCount++;
            return;
        }

        //echo $this->command;
        fwrite($this->conn, $this->command, strlen($this->command));

        $this->_fetchResponse();

        //echo $this->response;
        return $this->_fmtResult();
    }

    public function initPipeline()
    {
        $this->isPipeline = TRUE;
        $this->pipelineCount = 0;
        $this->pipelineCmd = '';
    }

    public function commitPipeline()
    {
        $ret = array();

        if ($this->pipelineCmd)
        {
            fwrite($this->conn, $this->pipelineCmd, strlen($this->pipelineCmd));

            $this->_fetchResponse();

            for($i = 0; $i < $this->pipelineCount; $i++)
            {
                $ret[] = $this->_fmtResult();
            }
        }
        $this->isPipeline = FALSE;
        $this->pipelineCmd = '';
        return $ret;
    }

    public function close()
    {
        @stream_socket_shutdown($this->conn, STREAM_SHUT_RDWR);
        @fclose($this->conn);
        $this->conn = NULL;
    }
}
