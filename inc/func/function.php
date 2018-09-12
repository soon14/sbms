<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 18/8/24
 * Time: 下午2:38
 */

defined('IN_IA') or exit('Access Denied');

function m($name = ''){
    static $_module = array();
    if(isset($_module[$name])){ return $_module[$name];}
    $model = SBMS_PATH_INC . 'model/' . strtolower($name) . '.php';
    if(!is_file($model)){
        die('Model ' . $name . ' Not Found!');
    }
    require $model;
    $class_name = 'Sbms_' . ucfirst($name);
    $_module[$name] = new $class_name;
    return $_module[$name];
}

function show_json($status = 1, $return = null) {
    $ret = array(
        'status' => $status
    );
    if ($return) {
        $ret['result'] = $return;
    }
    die(json_encode($ret));
}