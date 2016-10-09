<?php
namespace Home\Controller;

use Think\controller;

class IndexController extends BaseController{
    
    function index(){
        //查询年费会员的信息
        $package = M('package')->where(array('id'=>$this->user_info['vip_type']))->find();
        $this->assign('package', $package);
        $this->display();
    }
    
    function getVip(){
        //获取用户年龄
        $id_card = $this->user_info['idcard'];
        //大于60岁不允许申请
        $age = getAgeByID($id_card);
        if( $age >= 60 && $this->user_info['vip'] != 1 ){
            $this->assign('message', '60岁以上，不允许申请会员 ！');
            $this->display('Public:error');
        }
        if($age>=20 && $age<30){
            $key = 'package_price1';
        }elseif( $age>=30 && $age<40 ){
            $key = 'package_price2';
        }elseif( $age>=40 && $age<50 ){
            $key = 'package_price3';
        }elseif( $age>=50 && $age<60 ){
            $key = 'package_price4';
        }elseif( $age >60 ){
            $key = 'package_price5';
        }
        $user_money = getMoneyByAge();
        $package = M('package')->select();
        foreach( $package as $k=>$v ){
            $type_list = M('package_type')->where(array('package_id'=>$v['id']))->select();
            $list[$k] = array('id'=>$v['id'], 'package_name'=>$v['package_name'], 'package_price'=>$v[$key], 'type_list'=>$type_list);
        }
        $this->assign('package_list', $list);
        $this->assign('user_money', $user_money);
        $this->display();
    }
    
    function getMoney(){
        if( IS_AJAX && IS_POST ){
            $type = I('post.type');
            if( $type < 0 ){
                return $this->ajaxReturn(array('status' => 'n', 'msg' => '参数不正确！'));
            }
            //是否跟当前的套餐一致
            if( $type == $this->user_info['vip_time'] ){
                return $this->ajaxReturn(array('status'=>'n', 'msg'=>'与当前会员类型一致'));
                
            }
            $user_money = getMoneyByAge(getAgeByID($this->user_info['idcard']));
            $money = $user_money[$type];
            //生成订单
            $order_sn = time();
            $data = array('user_id' => $this->userid, 'order_sn' => $order_sn, 'type' => $type, 'money' => $money, 'addtime' => time(), 'status' => 0);
            M('vip_order')->add($data);
            Vendor('WxPayPubHelper.WxPayPubHelper');
            $jsApi = new \JsApi_pub();
            $unifiedOrder = new \UnifiedOrder_pub();
//            $price = floatval($money) * 100;
            $price = 1;
            $unifiedOrder->setParameter("openid", $this->user_info['openid']);
            $unifiedOrder->setParameter("body", '购买会员');
            $unifiedOrder->setParameter("out_trade_no", $order_sn);
            $unifiedOrder->setParameter("total_fee", $price);
            //$unifiedOrder->setParameter("total_fee",1);
            $unifiedOrder->setParameter("notify_url", "http://longchuanwenhua.gotoip4.com/payback.php");
            $unifiedOrder->setParameter("attach", "order_sn=" . $order_sn . "&userid=" . $this->userid);
            $unifiedOrder->setParameter("trade_type", "JSAPI");
            $prepay_id = $unifiedOrder->getPrepayId();
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters1();
            $jsApiParameters['status'] = 'y';
            exit(json_encode($jsApiParameters));
        }
    }
	
    function subsidies(){
        //微信签名
        $wx_config = M('weixin_config')->where('id = 1')->find();
        $jssdk = new \Org\Util\Wxsdk($wx_config['appid'], $wx_config['secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->display();
    }
    
    function save_sub(){
        
        if( IS_POST ){
            $zd = I('post.zd') ? I('post.zd') : $message = '参数不正确1';
            $zd_doctor = I('post.zd_doctor') ? I('post.zd_doctor') : $message = '参数不正确2';
            $bl_image = I('post.bl_img') ? I('post.bl_img') : $message = '参数不正确3';
            if( $message ){
                $this->assign('message', $message);
                $this->display('Public:error');
                exit;
            }
            $data = array('bbzd'=>$zd, 'userid'=>$this->userid, 'zd_doctor'=>$zd_doctor, 'bl_image'=>$bl_image, 'addtime'=>time());
            $insert = M('subsidies')->add($data);
            if( $insert > 0 ){
                $this->assign('url', 'http://'.$_SERVER['HTTP_HOST'].U('Index/index'));
                $this->assign('message', '补贴申请成功！');
                $this->display('Public:success');
            }else{
                $this->assign('message', '服务器出错！');
                $this->display('Public:error');
            }
        }
    }
    
    public function goods(){
        //查询诊疗项目
        $goods_list = M('goods')->select();
        $this->assign('goods_list', $goods_list);
        $this->display('goods_list');
    }
    
    public function goods_detail(){
        $id = I('get.id');
        if($id <= 0) exit('参数不正确！');
        $goods_data = M('goods')->where(array('id'=>$id))->find();
        if( $this->user_info['vip'] = 1 && $this->user_info['vip_time'] > time() ){ //会员有效
            if( $this->user_info['vip_type'] == 1 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale1']/100));
            }else if( $this->user_info['vip_type'] == 2 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale2']/100));
            }else if( $this->user_info['vip_type'] == 3 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale3']/100));
            }
        }else{
            $scale_price = 'yuan';
        }
        $this->assign('scale_price', $scale_price);
        $this->assign('goods_data', $goods_data);
        $this->display();
    }
    
    public function sub_goods(){
        $id = I('post.id');
        $goods_data = M('goods')->where(array('id'=>$id))->find();
        if( $this->user_info['vip'] = 1 && $this->user_info['vip_time'] > time() ){ //会员有效
            if( $this->user_info['vip_type'] == 1 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale1']/100));
            }else if( $this->user_info['vip_type'] == 2 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale2']/100));
            }else if( $this->user_info['vip_type'] == 3 ){
                $scale_price = $goods_data['good_price'] - ($goods_data['good_price'] * ($goods_data['good_scale3']/100));
            }
        }else{
            $scale_price = $goods_data['good_price'];
        }
        
        
        //生成订单
        $order_sn = time();
        if( $scale_price == 0 ){ //不需要支付
            $data = array('user_id'=>$this->userid, 'goods_id'=>$goods_data['id'], 'goods_name'=>$goods_data['goods_name'], 'yuan_money'=>$goods_data['good_price'], 'money'=>$scale_price, 'addtime'=>time(), 'order_sn'=>$order_sn, 'order_status'=>2);
            M('goods_order')->add($data);
            $this->ajaxReturn(array('status'=>'y', 'money'=>0, 'msg'=>'支付成功'));
        }else{
            $data = array('user_id'=>$this->userid, 'goods_id'=>$goods_data['id'], 'goods_name'=>$goods_data['goods_name'], 'yuan_money'=>$goods_data['good_price'], 'money'=>$scale_price, 'addtime'=>time(), 'order_sn'=>$order_sn);
            M('goods_order')->add($data);
        }
        Vendor('WxPayPubHelperGood.WxPayPubHelper');
        $jsApi = new \JsApi_pub();
        $unifiedOrder = new \UnifiedOrder_pub();
//            $price = floatval($scale_price) * 100;
        $price = 1;
        $unifiedOrder->setParameter("openid", $this->user_info['openid']);
        $unifiedOrder->setParameter("body", '诊疗项目');
        $unifiedOrder->setParameter("out_trade_no", $order_sn);
        $unifiedOrder->setParameter("total_fee", $price);
        //$unifiedOrder->setParameter("total_fee",1);
        $unifiedOrder->setParameter("notify_url", "http://longchuanwenhua.gotoip4.com/goodsback.php");
        $unifiedOrder->setParameter("attach", "order_sn=" . $order_sn . "&userid=" . $this->userid);
        $unifiedOrder->setParameter("trade_type", "JSAPI");
        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters1();
        $jsApiParameters['status'] = 'y';
        exit(json_encode($jsApiParameters));
        
    }
    
    public function order(){
        $status = I('get.status') > 0 ? I('get.status') : 1;
        $where = array('user_id'=>$this->userid);
        if( $status == 2 ){
            $where['order_status'] = 0;
        }elseif( $status == 3 ){
            $where['order_status'] = 2;
        }
        $order_list = M('goods_order')->where($where)->order('id desc')->select();
        $this->assign('action', 'order');
        $this->assign('status', $status);
        $this->assign('order_list', $order_list);
        $this->display();
    }
    
    public function uploadImg()
    {
        if( IS_AJAX ){
            //检测用户相册数量
            $serverid = isset($_POST['serverIds']) ? $_POST['serverIds'] : '';
            $server_arr = explode(',', trim($serverid, '"'));
            $count = count($server_arr);
            if( $count >= 30 ){
                $this->ajaxReturn(array('status'=>'n','msg'=>'只能上传30张照片'));
            }
            foreach( $server_arr as $v ){
                $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token().'&media_id='.$v;
                $fileinfo = $this->downloadImageFromWeiXin($url);
                $filename = 'uploads/BL/'.uniqid().'.jpg';
                $this->saveImageFromWeiXin($filename, $fileinfo['body']);
                $data[] = array('user_id'=>$this->userid, 'path'=>'/'.$filename,'server_id'=>$v, 'addtime'=>time());
                $server_id .= $v.',';
                $server_img[] = array('path'=>'/'.$filename, 'id'=>$v);
            }

            file_put_contents('1.txt', json_encode(array('status'=>'y', 'server'=>array('server_id'=>trim($server_id, ','), 'server_img'=>$server_img))));
            //插入数据库
            M('img')->addAll($data);
            $this->ajaxReturn(array('status'=>'y', 'server'=>array('server_id'=>trim($server_id, ','), 'server_img'=>$server_img)));
        }
    }
    
    public function saveImageFromWeiXin($filename, $filecontent)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }
    
    //下载微信资源图片
    function downloadImageFromWeiXin($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        return array_merge(array('body' => $package), array('header' => $httpinfo));
    }
    
    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
    public function new_access_token()
    {
        $time = time();
        $ret = M('weixin_config')->where('id = 1')->find();
        $appid = $ret['appid'];
        $appsecret = $ret['secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ret_json = $this->curl_get_contents($url);
        $ret = json_decode($ret_json);
        if($ret->access_token)
        {
            M('weixin_config')->where('id = 1')->save(array('access_token'=>$ret->access_token, 'dateline'=>$time));
        }
        return $ret->access_token;
    }
    
    public function access_token()
    {
        $ret = M('weixin_config')->where('id = 1')->find();
        $appid = $ret['appid'];
        $appsecret = $ret['secret'];
        $access_token = $ret['access_token'];
        $dateline = $ret['dateline'];
        $time = time();
        if(($time - $dateline) >= 7200)
        {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $ret_json = $this->curl_get_contents($url);
            $ret = json_decode($ret_json);
            if($ret->access_token)
            {
                M('weixin_config')->where('id = 1')->save(array('access_token'=>$ret->access_token, 'dateline'=>$time));
                return $ret->access_token;
            }
        }
        elseif(empty($access_token))
        {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $ret_json = $this->curl_get_contents($url);
            $ret = json_decode($ret_json);
            if($ret->access_token)
            {
                M('weixin_config')->where('id = 1')->save(array('access_token'=>$ret->access_token, 'dateline'=>$time));
                return $ret->access_token;
            }
        }
        else
        {
            return $access_token;
        }
    }
    
    public function curl_get_contents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
    
    public function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url='')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($proxystatus == 'true')
        {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        if(!empty($ref_url))
        {
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_REFERER, $ref_url);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        ob_start();
        return curl_exec ($ch);
        ob_end_clean();
        curl_close ($ch);
        unset($ch);
    }
    
    
}
