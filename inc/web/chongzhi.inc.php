<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$list=pdo_getall('sbms_czhd',array('uniacid'=>$_W['uniacid']));
include $this->template('web/chongzhi');