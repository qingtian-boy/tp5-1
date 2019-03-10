<?php 
namespace app\admin\controller; //定义当前类所在的命名空间
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Type;
use app\admin\model\Attribute;
class GoodsController extends CommonController {


    public function recycle(){
        //获取所有的(非回收站的)商品并分配到模板
        $goods = Goods::alias('t1')
                ->field('t1.*,t2.cat_name')
                ->join('sh_category t2','t1.cat_id = t2.cat_id','left')
                ->where('t1.is_delete',1)
                ->select();
        return $this->fetch('',[
            'goods' => $goods
        ]);
    }


    public function joinRecycle(){
        if(request()->isAjax()){
            $goods_id = input('goods_id');
            //把is_delele改为1   update() setInc()  setDec  setField
            if(Goods::where('goods_id',$goods_id)->setField('is_delete',1)){
                $response = ['code'=>200,'message'=>'加入回收站成功'];
            }else{
                $response = ['code'=>-1,'message'=>'加入回收站失败'];
            }
            echo json_encode($response);
        }
    }

	public function index(){
		//获取所有的(非回收站的)商品并分配到模板
		$goods = Goods::alias('t1')
				->field('t1.*,t2.cat_name')
				->join('sh_category t2','t1.cat_id = t2.cat_id','left')
                ->where('t1.is_delete',0)
				->select();
		return $this->fetch('',[
			'goods' => $goods
		]);
	}


	public function getTypeAttr(){
		//1.判断是否是ajax请求
		if(request()->isAjax()){
			//2.接受类型参数
			$type_id = input('type_id');
			//3.查询出此类型下面的所有的属性数据
			$attributes = Attribute::where('type_id',$type_id)->select();
			//4.响应json格式数据
			echo json_encode($attributes);
		}
	}

    
    public function add(){
    	// echo 'sn_'.time().uniqid();die;
    	//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');

			//3.验证器验证
			$result = $this->validate($postData,"Goods.add",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//需要处理多文件上传操作
			$goods_img = $this->uploadImg();
			//判断是否有文件上传成功
			if($goods_img){
				//把多张图片的路径存储到数据表goods_img字段中，存json格式
				$postData['goods_img'] = json_encode($goods_img);
				//文件上传成功，进行图片的缩略图缩放
				$thumb_arr = $this->genThumbImg($goods_img);
				//把小图路径存储到表对应的字段中，进行入库操作,存储json格式
				$postData['goods_middle'] = json_encode($thumb_arr['goods_middle']);
				$postData['goods_thumb'] = json_encode($thumb_arr['goods_thumb']);

			}
			//4.写入数据库
			$goodsModel = new Goods();
			if($goodsModel->allowField(true)->save($postData)){
				$this->success("编辑成功",url("/admin/goods/index"));
			}else{
				$this->error("编辑失败");
			}
		}

    	//取出商品所有分类并分配到模板中
    	$catModel = new Category();
    	$cats = $catModel->select();
    	//无限极递归处理
    	$catsTree = $catModel->getSonsTree($cats);
    	//取出所有的商品类型，并分配到模板中
    	$types = Type::select();
    	return $this->fetch('',[
    		'cats' => $catsTree,   
    		'types' => $types
    	]);
    }


    //定义一个多文件上传的方法
    public function uploadImg(){
    	$result = []; //存储图片原图的路径
     	//1.接受文件的name名称，获取文件的信息
     	$files = request()->file('img');
     	//2.定义上传的目录，定义一些上传的要求
     	$uploadDir = "./uploads/";
     	$validate = [
     		'size' => 1024*1024*2, // 2MB
     		'ext'  => 'png,jpg,jpeg,gif'
     	];
     	//循环上传文件
     	foreach($files as $file){
     		$info = $file->validate($validate)->move($uploadDir);
     		if($info){
     			//上传成功，获取到文件名称保存到一个数组中
     			$result[] = str_replace('\\', '/', $info->getSaveName());
     		}
     		//失败可以不用理会
     	}

     	//返回结果
     	return $result;
    }


    //定义一个生产缩略图的方法
    public function genThumbImg($goods_img){
    	//$goods_img  [20181221/gsdgfsdgfsdgf.jpg,20181221/gsdgfsdgfsdgf.jpg]
    	$goods_middle = []; //存储中图的路径
    	$goods_thumb = []; //存储小图的路径

    	//生成350*350 
    	foreach($goods_img as $path){
    		//1.打开原图的路径
    		$images = \think\Image::open('./uploads/'.$path);
    		//炸开原图的路径，得到目录和图片名称
    		$arr = explode('/',$path);// [20181221,gsdgfsdgfsdgf.jpg]
    		$middle_path = $arr[0].'/middle_'.$arr[1];
    		$images->thumb(350,350,2)->save("./uploads/".$middle_path);
    		//把成功的路径存储到$goods_middle数组中
    		$goods_middle[] = $middle_path;
    	}

    	//生成50*50 
    	foreach($goods_img as $path){
    		//1.打开原图的路径
    		$images = \think\Image::open('./uploads/'.$path);
    		//炸开原图的路径，得到目录和图片名称
    		$arr = explode('/',$path);// [20181221,gsdgfsdgfsdgf.jpg]
    		$thumb_path = $arr[0].'/thumb_'.$arr[1];
    		$images->thumb(50,50,2)->save("./uploads/".$thumb_path);
    		//把成功的路径存储到$goods_thumb数组中
    		$goods_thumb[] = $thumb_path;
    	}

    	//返回中图和小图的路径
    	return [ 'goods_middle'=>$goods_middle,'goods_thumb'=>$goods_thumb ];
    }

}