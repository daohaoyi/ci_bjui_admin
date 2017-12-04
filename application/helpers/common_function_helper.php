<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//include __ROOT__."/include/function/common_function.php" ;  //包含公用的方法 前台和后台公用
/**
 * 分页参数
 * @param int $total_data 总条数
 * @param int $page_size  每页大小
 * @param int $curr_page  当前页数
 * @return Array  array(
 *                      'limit'=>array(1,20),
 *                      'pageinfo'=>array(
 *                          'page_size'=>20,  //每页大小
 *                          'total_data'=>38, //总条数
 *                          'curr_page'=>1    //当前页
 *                      )
 *                  )
 */
if (!function_exists("page")) {
    function page($total_data, $page_size = "30", $curr_page = 1) {
        $ci = &get_instance();
        $offset = ($curr_page - 1) * $page_size; //偏移量
        return array(
            'limit' => array($offset, $page_size),
            'pageinfo' => array(
                'pageSize' => $page_size,   //每页大小
                'pageTotal' => $total_data, //总条数
                'pageCurrent' => $curr_page    //当前页
            )
        );
    }
}

function ydr2_password($pwd) {
//     $ci = &get_instance(); //初始化 为了用方法
//     $decor = $ci->config->item('db', 'ci_db_1');
//     $md5decor = md5($decor['dbprefix']);
	$md5decor = md5('xk_');
    return substr($md5decor, 0, 12) . md5($pwd) . substr($md5decor, -4, 4);
}

/**

 * 处理form 提交的参数过滤
 * $string	string  需要处理的字符串或者数组
 * $force	boolean  强制进行处理
 * @return	string 返回处理之后的字符串或者数组
 */
if (!function_exists("daddslashes")) {

    function daddslashes($string, $force = 1) {
        if (is_array($string)) {
            $keys = array_keys($string);
            foreach ($keys as $key) {
                $val = $string[$key];
                unset($string[$key]);
                $string[addslashes($key)] = daddslashes($val, $force);
            }
        } else {
            $string = addslashes($string);
        }
        return $string;
    }

}
/**

 * 处理form 提交的参数过滤
 * $string	string  需要处理的字符串
 * @return	string 返回处理之后的字符串或者数组
 */
if (!function_exists("dowith_sql")) {

    function dowith_sql($str) {
        $str = str_replace("and", "", $str);
        $str = str_replace("execute", "", $str);
        $str = str_replace("update", "", $str);
        $str = str_replace("count", "", $str);
        $str = str_replace("chr", "", $str);
        $str = str_replace("mid", "", $str);
        $str = str_replace("master", "", $str);
        $str = str_replace("truncate", "", $str);
        $str = str_replace("char", "", $str);
        $str = str_replace("declare", "", $str);
        $str = str_replace("select", "", $str);
        $str = str_replace("create", "", $str);
        $str = str_replace("delete", "", $str);
        $str = str_replace("insert", "", $str);
// $str = str_replace("'","",$str);
// $str = str_replace('"',"",$str);
// $str = str_replace(" ","",$str);
        $str = str_replace("or", "", $str);
        $str = str_replace("=", "", $str);
        $str = str_replace("%20", "", $str);
//echo $str;
        return $str;
    }

}
//获取登录的用户名
if (!function_exists("login_name")) {

    function login_name() {
        $data = decode_data();
        if (isset($data['username'])) {
            return $data['username'];
        } else {
            return '';
        }
    }

}

//获取登录的用户所在的群组
if (!function_exists("group_name")) {

    function group_name() {
        $data = decode_data();
        if (isset($data['group_name'])) {
            return $data['group_name'];
        } else {
            return '';
        }
    }

}
//获取登录的用户所在的角色ID 
if (!function_exists("role_id")) {

    function role_id() {
        $data = decode_data();
        if (isset($data['role_id'])) {
            return $data['role_id'];
        } else {
            return '';
        }
    }

}

//获取登录的用户的uid
if (!function_exists("admin_id")) {

    function admin_id() {
        $data = decode_data();
        if (isset($data['admin_id'])) {
            return $data['admin_id'];
        } else {
            return '';
        }
    }

}

//判断当前登录的用户是不是超级管理员
if (!function_exists("is_super_admin")) {

    function is_super_admin() {
        $data = decode_data();
        if (isset($data['isadmin']) && $data['isadmin']) {
            return true;
        } else {
            return false;
        }
    }

}

/*
 * @记录系统操作日志文件到数据库里面 
 * *sql 是要插入数据库中的 log_sql的值 
 * $action 动作
 * $person 操作人
 * $ip ip地址
 * status 操作是否成功 1成功 0失败
 * message 失败信息
 * groupname_ 定义数据库连接信息的时候的 groupname
 */
if (!function_exists("write_action_log")) {

    function write_action_log($url, $adminid, $ip, $sql = '', $params = '', $message = '', $state = '1') {
        if (!config_item('write_log_database')) {//是否记录日志文件到数据表中
            return false;
        }

        $sql = str_replace("\\", "", $sql); // 把\进行过滤掉
        $sql = str_replace("%", "\%", $sql); // 把 '%'前面加上\
        $sql = str_replace("'", "\'", $sql); // 把 ''过滤掉
        $message = daddslashes($message);
        $time_table = date("Ym", time());
        $ci = &get_instance(); //初始化 为了用方法
        $table_pre = $ci->db->dbprefix;
        $sql_table = <<<EOT
CREATE TABLE IF NOT EXISTS `{$table_pre}logadmin_{$time_table}` (
  `id` mediumint(8) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL,
  `adminid` varchar(16) NOT NULL,
  `dateline` timestamp  NOT NULL default current_timestamp,
  `ip` char(15) NOT NULL,
  `sql` text NOT NULL,
  `params` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL default '1',
  `message` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;		
EOT;

        $d = $ci->load->database('default', true);
        $d->query($sql_table);
        $sql_log = "INSERT INTO `{$table_pre}logadmin_{$time_table}`(`url`,`adminid`,`ip`,`sql`,`params`,`state`,`message`)VALUES('{$url}','{$adminid}','{$ip}','{$sql}','{$params}','{$state}','{$message}')";

        $d->query($sql_log);
    }

}



/**
 * 将数据格式化成树形结构
 * @author
 * @param array $items
 * @return array
 */
if (!function_exists("genTree9")) {

    function genTree9($items, $id = 'id', $pid = 'pid', $child = 'children') {
        $tree = array(); //格式化好的树
        foreach ($items as $item)
            if (isset($items[$item[$pid]]))
                $items[$item[$pid]][$child][] = &$items[$item[$id]];
            else
                $tree[] = &$items[$item[$id]];
        return $tree;
    }

}

/**
 * 格式化select
 * @author
 * @param array $parent
 * @deep int 层级关系 
 * @return array
 */
function getChildren($parent, $deep = 0) {
    foreach ($parent as $row) {
        $data[] = array("id" => $row['id'], "name" => $row['name'], "pid" => $row['parentid'], 'deep' => $deep, 'url' => $row['url']);
        if (isset($row['childs']) && !empty($row['childs'])) {
            $data = array_merge($data, getChildren($row['childs'], $deep + 1));
        }
    }
    return $data;
}

/**
 * 格式化select,生成options
 * @author
 * @param array $parent
 * @deep int 层级关系 
 * @return array
 */
function getChildren2($parent, $deep = 0, $id = 'id', $pid = 'pid', $name = 'typename', $children = 'children') {
    foreach ($parent as $row) {
        $data[] = array("id" => $row[$id], "name" => $row[$name], "pid" => $row[$pid], 'deep' => $deep);
        if (isset($row[$children]) && !empty($row[$children])) {
            $data = array_merge($data, getChildren2($row[$children], $deep + 1, $id, $pid, $name, $children));
        }
    }
    return $data;
}

/**
 * 格式化数组，
 * @author 
 * @param array $list
 * @return array
 */
function tree_format(&$list, $pid = 0, $level = 0, $html = '--', $pid_string = 'pid', $id_string = 'id') {
    static $tree = array();
    foreach ($list as $v) {
        if ($v[$pid_string] == $pid) {
            $v['sort'] = $level;
            $v['html'] = str_repeat($html, $level);
            $tree[] = $v;
            tree_format($list, $v[$id_string], $level + 1, $html);
        }
    }
    return $tree;
}

/**
 * 显示页面
 * @author
 * @param string $message 错误信息
 * @param string $url 页面跳转地址
 * @param string $timeout 时间
 * @param string $iserror 是否错误 1正确 0错误
 * @param string $params 其他参数前面加? 例如?id=122&time=333
 */
if (!function_exists('showmessage')) {

//跳转

    function showmessage($message = '', $url = '', $timeout = '3', $iserror = 1, $params = '') {
        if ($iserror == 1) {//正确
            include APPPATH . '/errors/showmessage.php';
        } else {
            include APPPATH . '/errors/showmessage_error.php';
        }

        die();
    }

}
/**
 * 获取后台登陆的数据，其中参数主要是为了 ，有时候用插件上传图片的时候 登陆状态消失
 * @author
 * @param $string 解密的值
 * @return array
 */
if (!function_exists("decode_data")) {

    function decode_data($string = '') {
        $data = array();
        $encode_string = '';
        $encode_string = ($string != "" ) ? $string : (isset($_COOKIE['admin_auth']) ? $_COOKIE['admin_auth'] : '');

//$encode_string = isset($_COOKIE['admin_auth'])?$_COOKIE['admin_auth']:'' ;
        if (empty($encode_string)) {
            return $data;
        }
        $encode_string = auth_code($encode_string, "DECODE", config_item("s_key"));
        $data = unserialize($encode_string);
        return $data;
    }

}

/*
  32	函数名称：verify_id()
  33	函数作用：校验提交的ID类值是否合法
  34	参　　数：$id: 提交的ID值
  35	返 回 值：返回处理后的ID
  36
 */
if (!function_exists("verify_id")) {

    function verify_id($id = null) {
        if (!$id) {
            return 0;
        } // 是否为空判断
        elseif (inject_check($id)) {
            return 0;
        } // 注射判断
        elseif (!is_numeric($id)) {
            return 0;
        } // 数字判断
        $id = intval($id); // 整型化		 
        return $id;
    }

}

/*
 * 检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 * 参　　数：$sql_str: 提交的变量
 * 返 回 值：返回检测结果，ture or false 
 */

if (!function_exists("inject_check")) {

    function inject_check($sql_str) {
//return @eregi('select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str); // 进行过滤
        return FALSE;
    }

}

/**
 * 
 * @param type $type   日志类型
 * @param type $message  日志内容
 * @param string $path  日志位置
 * @param type $month   是否按月存
 */
function write_log($type, $message, $path = '', $month = false) {
    if ($month) {
        $date = date('Ym');
    } else {
        $date = date('Ymd');
    }
    $logFileName = $type . '.' . $date . '.log';
    if (empty($path)) {
        $path = getcwd() . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR;
    } else {
        $path = getcwd() . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
    }
    $file = $path . $logFileName;
    $message = date('Y-m-d H:i:s') . "|" . $message . "\r\n";
    file_put_contents($file, $message, FILE_APPEND);
}

/**
 * 生成从开始月份到结束月份的月份数组
 * @param int $start 开始时间戳
 * @param int $end 结束时间戳
 */
function monthList($start, $end) {
    if (!is_numeric($start) || !is_numeric($end) || ($end <= $start))
        return '';
    $start = date('Y-m', $start);
    $end = date('Y-m', $end);
//转为时间戳
    $start = strtotime($start . '-01');
    $end = strtotime($end . '-01');
    $i = 0;
    $d = array();
    while ($start <= $end) {
//这里累加每个月的的总秒数 计算公式：上一月1号的时间戳秒数减去当前月的时间戳秒数
        $d[$i] = trim(date('Ym', $start), ' ');
        $start+=strtotime('+1 month', $start) - $start;
        $i++;
    }
    arsort($d);
    return $d;
}

/**
 * 元转化里
 */
function y2l($price) {
    return $price * 1000;
}

/**
 * 
 * 厘转化成元 
 */
function l2y($price) {
    return $price / 1000;
}

/**
 * 分转化里
 */
function f2l($price) {
    return $price * 10;
}

/**
 * 分转化元
 */
function y2f($price) {
    return $price * 100;
}

/**
 * 分转化元
 */
function f2y($price) {
    return $price / 100;
}

/**
 * 
 * 厘转化成分 
 */
function l2f($price) {
    return $price / 10;
}

/**
 * 当前时间
 * @return type
 */
function c_date() {
    return date("Y-m-d H:i:s");
}

function dump($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

/**
 * 加款订单
 * @return string string
 */
function stored_orderid() {
    return "R" . date("ymdHis") . substr(microtime(), 2, 2) . mt_rand(100, 999);
}

/**
 * 把秒变成年月日十分秒
 * @param type $second
 * @param type $return
 * @return boolean|string
 */
function _tranSecToMHDY($second, $return = '') {
    if (!is_numeric($second) || $second < 0)
        return false;
    if ($second > 30240000)
        return '超过50年'; //把值写大点。大概超过51年左右，就报错了  
    if (0 != $second) {
        if ($second < 60) {
            $return .= $second . '';
            $second = 0;
        } else if ($second >= 60 && $second < 60 * 60) {//分钟  
            $return .= floor($second / 60) . ':';
            $second %= 60;
        } else if ($second >= 60 * 60) {//小时  
            $return .= floor($second / (60 * 60)) . ':';
            $second %= (60 * 60);
        }
        return _tranSecToMHDY($second, $return);
    } else {
        return $return;
    }
}

/**
 * 发送CURL
 */
function sendCurl($url, array $params = array(), $mode = 'post') {
    $curlHandle = curl_init();
// curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    if ($mode == 'post') {
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, '20');
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制使用哪个版本  
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
    } else {
        $url .= ( strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
    }
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    if (substr($url, 0, 5) == 'https') {
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
    }

    $result = curl_exec($curlHandle);
    $curl_errno = curl_errno($curlHandle);
    $curl_error = curl_error($curlHandle);
    if ($curl_errno > 0) {
        log_message('curlerr', "cURL Error ($curl_errno): $curl_error\n");
    }
    curl_close($curlHandle);
    return $result;
}

function doFormatMoney($money) {
    $tmp_money = strrev($money);
    $format_money = "";
    for ($i = 3; $i < strlen($money); $i+=3) {
        $format_money .= substr($tmp_money, 0, 3) . ",";
        $tmp_money = substr($tmp_money, 3);
    }
    $format_money .=$tmp_money;
    $format_money = strrev($format_money);
    return $format_money;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
if (!function_exists("get_client_ip")) {

    function get_client_ip($type = 0) {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

}

/**
 * 获取面值类型
 * @return int  1定面值2多个面值3批量面值
 */
function flowtype($data) {
    if (strstr($data, ',')) {
        return 2;
    } else {
        return 1;
    }
}

function provincetype($data) {
    if (strstr($data, ',')) {
        return 2;
    } elseif ($data == 'All') {
        return 3;
    } else {
        return 1;
    }
}

/**
 *  一维或多维数组转换成字符串
 */
function array_to_string($arr) {
    if (is_array($arr)) {
        return implode(',', array_map('array_to_string', $arr));
    }
    return $arr;
}

/**
 * 
 * @param type $facevalue 面值（元）
 * @param type $profit_loss_price 损溢值
 * @param type $profit_loss 损溢百分比
 * @return type
 */
function getNewPrice($facevalue, $profit_loss_price, $profit_loss) {
    return $facevalue + $facevalue * $profit_loss + $profit_loss_price;
}

/**
 * 检查面值是否存在
 * @param type $string 面值 eg. 10,20,30
 * @param type $facevalue 面值  eg.10
 * @return boolean 
 */
function checkExistFacevalue($string, $facevalue) {
    $array = explode(',', $string);
    if (in_array($facevalue, $array)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * 检查省份是否存在
 * @param type $string All,010,020,021
 * @param type $province
 * @return boolean
 */
function checkExistProvince($string, $province) {
    if ($string == 'All') {
        return TRUE;
    }
    if (strpos($string, ',') === false) {
        if (strcmp($string, $province) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    $array = explode(',', $string);
    if (in_array($province, $array)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * 检查M数是否存在区间中
 * @param type $string M数 10M,20M,1G
 * @param type $flow 10M
 * @return boolean
 */
function checkFlow($string, $flow) {
    $return = FALSE;
    $array = explode(',', $string);
    if (in_array($flow, $array)) {
        return $return = TRUE;
    }
    return $return;
}

/**
 * 人民币小写转大写
 *
 * @param string $number   待处理数值
 * @param bool   $is_round 小数是否四舍五入,默认"四舍五入"
 * @param string $int_unit 币种单位,默认"元"
 * @return string
 */
function rmb_format($money = 0, $is_round = true, $int_unit = '元') {
    $chs = array(0, '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
    $uni = array('', '拾', '佰', '仟');
    $dec_uni = array('角', '分');
    $exp = array('', '万', '亿');
    $res = '';
    // 以 元为单位分割
    $parts = explode('.', $money, 2);
    $int = isset($parts [0]) ? strval($parts [0]) : 0;
    $dec = isset($parts [1]) ? strval($parts [1]) : '';
    // 处理小数点
    $dec_len = strlen($dec);
    if (isset($parts [1]) && $dec_len > 2) {
        $dec = $is_round ? substr(strrchr(strval(round(floatval("0." . $dec), 2)), '.'), 1) : substr($parts [1], 0, 2);
    }
    // number= 0.00时，直接返回 0
    if (empty($int) && empty($dec)) {
        return '零';
    }

    // 整数部分 从右向左
    for ($i = strlen($int) - 1, $t = 0; $i >= 0; $t++) {
        $str = '';
        // 每4字为一段进行转化
        for ($j = 0; $j < 4 && $i >= 0; $j ++, $i --) {
            $u = $int{$i} > 0 ? $uni [$j] : '';
            $str = $chs [$int {$i}] . $u . $str;
        }
        $str = rtrim($str, '0');
        $str = preg_replace("/0+/", "零", $str);
        $u2 = $str != '' ? $exp [$t] : '';
        $res = $str . $u2 . $res;
    }
    $dec = rtrim($dec, '0');
    // 小数部分 从左向右
    if (!empty($dec)) {
        $res .= $int_unit;
        $cnt = strlen($dec);
        for ($i = 0; $i < $cnt; $i ++) {
            $u = $dec {$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
            $res .= $chs [$dec {$i}] . $u;
        }
        if ($cnt == 1)
            $res .= '整';
        $res = rtrim($res, '0'); // 去掉末尾的0
        $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0
    } else {
        $res .= $int_unit . '整';
    }
    return $res;
}

?>