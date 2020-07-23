<?php
/**
 * Created by PhpStorm.
 * User: LXZ
 * Date: 2020/7/23
 * Time: 18:26
 */
namespace Redis\RedisTool;
    /*-------------------------------------------------
	 * redis驱动类，需要安装phpredis扩展
	 * @athor	liubin
	 * @link	http://pecl.php.net/package/redis
	 * @date	2016-02-09
	 *-------------------------------------------------
	*/
class RedisTool
{
    private $_redis = null;
    protected $ip = 'host.freebd.cn';
    protected $port = 6379;
    /**
     * 构造器
     * RedisTool constructor.
     */
    public function __construct($ip = 'host.freebd.cn', $port = 6379){
        $this->ip = $ip;
        $port = $port;
        if($this->_redis === null){
            try{
                $redis		= new Redis();
                $hanedel	= $redis->connect($this->ip, $this->port);
                if($hanedel){
                    $this->_redis = $redis;
                }else{
                    echo 'redis服务器无法链接';
                    $this->_redis = false;
                    exit;
                }
            }catch(RedisException $e){
                echo 'phpRedis扩展没有安装：' . $e->getMessage();
                exit;
            }
        }
    }
    /**
     * 队列尾追加
     * @param $key
     * @param $value
     * @return mixed
     */
    public function addRlist($key,$value){
        $result = $this->_redis->rPush($key,$value);
        return $result;
    }
    /**
     * 队列头追加
     * @param $key
     * @param $value
     * @return mixed
     */
    public function addLlist($key,$value){
        $result = $this->_redis->lPush($key,$value);
        return $result;
    }
    /**
     * 头出队列
     * @param $key
     * @return mixed
     */
    public function lpoplist($key){
        $result=$this->_redis->lPop($key);
        return $result;
    }
    /**
     * 尾出队列
     * @param $key
     * @return mixed
     */
    public function rpoplist($key){
        $result=$this->_redis->rPop($key);
        return $result;
    }
    /**
     * 查看队列
     * @param $key
     * @return mixed
     */
    public function showlist($key){
        $result = $this->_redis->lRange($key, 0, -1);
        return $result;
    }
    /**
     * 队列数量
     * @param $key
     * @return mixed
     */
    public function listcount($key){
//            $result = $this->_redis->lSize($key);
        $result = $this->_redis->lLen($key);
        return $result;
    }

    /**
     * 清空队列
     * @param $key
     * @return mixed
     */
    public function clearlist($key){
//            $result = $this->_redis->delete($key);
        $result = $this->_redis->del($key);
        return $result;
    }
    /**
     * 获取redis资源对象
     * @return bool|null
     */
    public function getHandel(){
        return $this->_redis;
    }
}
