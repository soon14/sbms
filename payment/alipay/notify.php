<?php

error_reporting(0);
define('IN_MOBILE', true);
if (!empty($_POST)) {
    $out_trade_no = $_POST['out_trade_no'];
    require '../../../../framework/bootstrap.inc.php';
    $body          = $_POST['body'];
    $strs          = explode(':', $body);
    $_W['uniacid'] = $_W['weid'] = intval($strs[0]);
    $type          = intval($strs[1]);
    $setting = uni_setting($_W['uniacid'], array(
        'payment'
    ));
    if (is_array($setting['payment'])) {
        $alipay = $setting['payment']['alipay'];
        if (!empty($alipay)) {
            $prepares = array();
            foreach ($_POST as $key => $value) {
                if ($key != 'sign' && $key != 'sign_type') {
                    $prepares[] = "{$key}={$value}";
                }
            }
            sort($prepares);
            $string = implode($prepares, '&');
            $string .= $alipay['secret'];
            $sign = md5($string);
            if ($sign == $_POST['sign']) {
                if (empty($type)) {
                    $tid = $out_trade_no;
                    if (strexists($tid, 'MSZF')) {
                        $tids = explode("MSZF", $tid);
                        $tid  = $tids[0];
                    }
                    $sql               = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid and `module`=:module limit 1';
                    $params            = array();
                    $params[':tid']    = $tid;
                    $params[':module'] = 'sbms';
                    $log               = pdo_fetch($sql, $params);
                    if (!empty($log) && $log['status'] == '0') {
                        $site = WeUtility::createModuleSite($log['module']);
                        if (!is_error($site)) {
                            $method = 'payResult';
                            if (method_exists($site, $method)) {
                                $ret               = array();
                                $ret['weid']       = $log['weid'];
                                $ret['uniacid']    = $log['uniacid'];
                                $ret['result']     = 'success';
                                $ret['type']       = $log['type'];
                                $ret['from']       = 'return';
                                $ret['tid']        = $log['tid'];
                                $ret['user']       = $log['openid'];
                                $ret['fee']        = $log['fee'];
                                $ret['is_usecard'] = $log['is_usecard'];
                                $ret['card_type']  = $log['card_type'];
                                $ret['card_fee']   = $log['card_fee'];
                                $ret['card_id']    = $log['card_id'];
                                $result = $site->$method($ret);
                                if (is_array($result) && $result['result'] == 'success') {
                                    $record           = array();
                                    $record['status'] = '1';
                                    pdo_update('core_paylog', $record, array(
                                        'plid' => $log['plid']
                                    ));
                                    exit('success');
                                }
                            }
                        }
                    }
                } else if ($type == 1) {
                    $logno = trim($out_trade_no);
                    if (empty($logno)) {
                        exit;
                    }
                    $log = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_member_log') . ' WHERE `uniacid`=:uniacid and `logno`=:logno limit 1', array(
                        ':uniacid' => $_W['uniacid'],
                        ':logno' => $logno
                    ));
                    if (!empty($log) && empty($log['status'])) {
                        pdo_update('ewei_shop_member_log', array(
                            'status' => 1,
                            'rechargetype' => 'alipay'
                        ), array(
                            'id' => $log['id']
                        ));
                        m('member')->setCredit($log['openid'], 'credit2', $log['money'], array(
                            0,
                            '人人商城会员充值:credit2:' . $log['money']
                        ));
                        m('member')->setRechargeCredit($log['openid'], $log['money']);
                        if (p('sale')) {
                            p('sale')->setRechargeActivity($log);
                        }
                        m('notice')->sendMemberLogMessage($log['id']);
                    }
                }
            }
        }
    }
}
exit('fail');