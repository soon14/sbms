<?php
global $_GPC, $_W;
$seller_id=$_COOKIE["storeid"];
$uid=$_COOKIE["uid"];
$GLOBALS['frames'] = $this->getNaveMenu($seller_id, $action='start',$uid);
$cur_store = $this->getStoreById($seller_id);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$sys=pdo_get('sbms_system',array('uniacid'=>$_W['uniacid']),'is_order');
$list=pdo_get('sbms_order',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
if(checksubmit('submit2')){
		$res=pdo_update('zhjd_order',array('online_price'=>$_GPC['newcost']),array('id'=>$_GPC['id']));
		if($res){
			message('编辑成功',$this->createWebUrl('ddgl',array()),'success');
		}else{
			message('编辑失败','','error');
		}
	}
//$newcost=$list['dis_cost']-$list['ytyj_cost'];
include $this->template('web/dlinorderinfo');