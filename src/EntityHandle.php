<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-06
 * Time: 下午 5:49
 */

namespace Shein;


interface EntityHandle
{
    public function find();
    public function remove();
    public function merge();
    public function persist();
    public function flush();
}