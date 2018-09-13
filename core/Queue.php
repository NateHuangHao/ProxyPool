<?php
namespace ProxyPool\core;
/**
 * 队列处理文件
 */
class Queue
{
    /**
     * 队列。
     */
    private $queue;

    /**
     * 队列的长度。
     */
    private $size;

    /**
     * 构造方法 - 初始化数据。
     */
    public function __construct()
    {
        $this->queue = array();
        $this->size = 0;
    }
    /**
     * 数组入队列
     *
     * @param array $arr 入队数据。
     * @return object 返回对象本身。
     */
    public function arr2queue($arr)
    {
        $this->queue = $arr;
        $this->size = count($arr);
        return $this;
    }
    /**
     * 入队操作。
     *
     * @param mixed $data 入队数据。
     * @return object 返回对象本身。
     */
    public function push($data)
    {
        $this->queue[$this->size++] = $data;
        return $this;
    }
    /**
     * 出队操作。
     *
     * @return mixed 空队列时返回FALSE，否则返回队头元素。
     */
    public function pop()
    {
        if (!$this->isEmpty()) {
            --$this->size;
            $first = $this->queue[0];
            array_shift($this->queue);
            return $first;
        }
        return FALSE;
    }
    /**
     * 获取队列。
     *
     * @return array 返回整个队列。
     */
    public function getQueue()
    {
        return $this->queue;
    }
    /**
     * 获取队头元素。
     *
     * @return mixed 空队列时返回FALSE，否则返回队头元素。
     */
    public function getFront()
    {
        if (!$this->isEmpty()) {
            $first = $this->queue[0];
            return $first;
        }
        return FALSE;
    }
    /**
     * 获取队列的长度。
     *
     * @return integer 返回队列的长度。
     */
    public function getSize()
    {
        return $this->size;
    }
    /**
     * 检测队列是否为空。
     *
     * @return boolean 空队列则返回TRUE，否则返回FALSE。
     */
    public function isEmpty()
    {
        return 0 === $this->size;
    }
}