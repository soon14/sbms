<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('sbms_system',array('uniacid'=>$_W['uniacid']));
    if(checksubmit('submit')){
            $data['tx_sxf']=$_GPC['tx_sxf'];
            $data['zd_money']=$_GPC['zd_money'];
            $data['uniacid']=$_W['uniacid'];
            $data['tx_notice']=html_entity_decode($_GPC['tx_notice']);
            $data['tx_mode']=$_GPC['tx_mode'];
            if($_GPC['id']==''){                
                $res=pdo_insert('sbms_system',$data);
                if($res){
                    message('添加成功',$this->createWebUrl('txsz',array()),'success');
                }else{
                    message('添加失败','','error');
                }
            }else{
                $res = pdo_update('sbms_system', $data, array('id' => $_GPC['id']));
                if($res){
                    message('编辑成功',$this->createWebUrl('txsz',array()),'success');
                }else{
                    message('编辑失败','','error');
                }
            }
        }

include $this->template('web/txsz');