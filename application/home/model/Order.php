<?php  
namespace app\home\model;
use think\Model;

class Order extends Model{
	protected $pk = 'id';
	protected $autoWriteTimestamp = true;
}