<?php
global $_GPC, $_W;
// $action = 'ad';
// $title = $this->actions_titles[$action];
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('sbms_system',array('uniacid'=>$_W['uniacid']));
    if(checksubmit('submit')){
            $data['pl_score']=trim($_GPC['pl_score']);
            $data['xf_score']=trim($_GPC['xf_score']);
            $data['uniacid']=trim($_W['uniacid']);
            if($_GPC['id']==''){                
                $res=pdo_insert('sbms_system',$data);
                if($res){
                    message('添加成功',$this->createWebUrl('score',array()),'success');
                }else{
                    message('添加失败','','error');
                }
            }else{
                $res = pdo_update('sbms_system', $data, array('id' => $_GPC['id']));
                if($res){
                    message('编辑成功',$this->createWebUrl('score',array()),'success');
                }else{
                    message('编辑失败','','error');
                }
            }
        }
    include $this->template('web/score');