<?php 
namespace app\admin\model;
use think\Model;

class Order extends Model{
	protected $pk = 'id';
	protected $autoWriteTimestamp = true;
}