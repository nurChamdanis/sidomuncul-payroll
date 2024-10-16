<?php 

function create_button($btnAccess = array(), $btnId = "", $extra_attr = '', $param = ""){
    if($btnAccess) {
        if (array_key_exists($btnId, $btnAccess)){
            if ( $btnAccess[$btnId]['onclick'] != "" && $param != "" ){
                $str1 = str_replace("{0}", base_url(), trim($btnAccess[$btnId]['onclick']) );
                $str2 = str_replace("{1}", $param, $str1 );
                $btnAccess[$btnId]['onclick'] = $str2;
            }
            //echo form_button($btnAccess[$btnId] , $extra_attr);
            echo form_button($btnAccess[$btnId], $btnAccess[$btnId]["content"] ,$extra_attr);
        }
    }
}

function search_for_key($id, $array, $btnId) {
    $k = array();
    foreach ($array as $key => $val) {
        if ($val['type'] === $id && strpos($val['id'], $btnId) !== false ) {
            array_push($k, $val);
        }
    }
    return $k;
}

function create_button_group($btnAccess = array(), $btnId = ""){
    if($btnAccess) {
        if (array_key_exists($btnId, $btnAccess)){
            $btnDropdown = $btnAccess[$btnId];

            $list = search_for_key('button_group_list', $btnAccess, $btnId);

            $html = "";
            $html = '<div class="btn-group dropup">';
            $html .= '<button type="button" class="'.$btnDropdown['class'].'" data-toggle="dropdown" aria-expanded="false"> '.$btnDropdown['content'].' <span class="caret"></span> </button>';
            $html .= '<ul class="dropdown-menu">';
            foreach ($list as $r) {
                $html .= '<li><a href="#" onclick="'.$r['onclick'].'">'.$r['content'].'</a></li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
            echo $html;
        }
    }
}

function create_menu($list = array()){
    $html = '';
    if ($list){
        
        $group = "";
        //$group_name = "";
        $i = 1;
        foreach ($list as $row) {
            if ($group != $row['function_grp']) {
                $group = $row['function_grp'];
                
                if ($i > 1) {
                    $html .= '</ul></li>';
                }
                $html .= '<li class="has_sub" id="intro-menu-'.$i.'">'
                . '<a href="javascript:void(0);" class="waves-effect menu-flex" id="intro-submenu-'.$i.'" style="display:flex; align-items:center;"><i class="'.$row['function_icon'].'" style="margin-right: 10px; width: 15px; font-size: 14px;"></i> <span style="font-size: 12px !important;"> '.$row['function_grp_nm'].' </span> <span class="menu-arrow"></span></a>'
                    . '<ul class="list-unstyled">';
                $i++;
            }
            
			if ($row['function_link_visible'] == '1')
			{
				$html .= '<li><a href="'.site_url($row['function_controller']).'">'.$row['function_name'].'</a></li>';
			}
            
        } 
        $html .= '</ul></li>';
    }
    
    echo $html;
}

// dipindahin kesini
function get_param_special_char() {
    $controller = uri_string(1);
    $method = uri_string(2);

    $arr = explode("$controller/$method/",uri_string());

    if(count($arr) == 2)
        return $arr[1];
    else
        return '';
}

function is_weekend($your_date) {
    $week_day = date('w', strtotime($your_date));
    //returns true if Sunday or Saturday else returns false
    return ($week_day == 0 || $week_day == 6);
}

function to_std_dt($dt) {
    if(empty($dt)) return $dt;
    return date('Y-m-d', strtotime(str_replace('/', '-', $dt)));
}

function to_std_dt_screen($dt) {
    if(empty($dt)) return $dt;
    return date('d/m/Y', strtotime(str_replace('-', '/', $dt)));
}
function to_std_dt_screen_my($dt) {
    if(empty($dt)) return $dt;
    return date('m/Y', strtotime(str_replace('-', '/', $dt)));
}

function to_std_dt_table($dt) {
    if(empty($dt)) return $dt;
    return date('d M Y', strtotime(str_replace('-', '/', $dt)));
}

function to_std_dt_table_full($dt) {
    if(empty($dt)) return $dt;
    return date('d M Y H:i:s', strtotime(str_replace('-', '/', $dt)));
}

function to_std_dt_table_month_year($dt) {
    if(empty($dt)) return $dt;
    return date('M Y', strtotime(str_replace('-', '/', $dt)));
}

function to_my_indo($dt) {
    if(empty($dt)){
        return $dt;
    } else{
        $date_indo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        return $date_indo[date('n', strtotime(str_replace('-', '/', $dt)))-1] .  date(' Y', strtotime(str_replace('-', '/', $dt)));
    }
}

function to_dt_indo($dt) {
    if(empty($dt)){
        return $dt;
    } else{
        $date_indo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        return date('d ', strtotime(str_replace('-', '/', $dt))) . $date_indo[date('n', strtotime(str_replace('-', '/', $dt)))-1] .  date(' Y', strtotime(str_replace('-', '/', $dt)));
    }
}

function extract_date_range_picker($dateStr) {
    $date_range_raw = explode('-', $dateStr);

    $start_date = '';
    $end_date = '';
    if(count($date_range_raw) == 2) {
        $start_date = to_std_dt($date_range_raw[0]);
        $end_date = to_std_dt($date_range_raw[1]);
    }

    return (object)[
                'start' => $start_date,
                'end' => $end_date];
}

function a($href, $title, $text) {
    return "<a href=".site_url($href).' title="'.$title.'">'.$text.'</a>';
}

function to_std_currency($val) {
    return 'Rp. ' . number_format($val, 2);
}


function timepicker_to_hour($val) {
    if(is_null($val)) return $val;

    $vals =  explode(":", $val);
    $hour = $vals[0] + ($vals[1]/60);
    return number_format($hour, 2);
}

function hour_to_timepicker($val) {
    if(is_null($val)) return $val;

    $vals =  explode(".", $val);

    if(count($vals) == 1) {
        return $val . ':00';
    } else if(count($vals) == 2) {
        $minute = doubleval("0.{$vals[1]}") * 60;
        return $vals[0] . ':' . intval($minute);
    }
}

function shorten_string($string, $wordsreturned)
{
    $retval = $string;
    $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
    $string = str_replace("\n", " ", $string);
    $array = explode(" ", $string);
    if (count($array)<=$wordsreturned)
    {
    $retval = $string;
    }
    else
    {
    array_splice($array, $wordsreturned);
    $retval = implode(" ", $array)." ...";
    }
    return $retval;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->days / 7);
    $diff->d -= $weeks * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'just now';
}

function getIpAddress()
{
    // updated by arka.rangga, Login Attempt records ip-address
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_title( $list = array(), $function_controller = ''){
    $serviceUri = service('uri');
    $uri = $serviceUri->setSilent()->getSegment(2) ? ucwords($serviceUri->setSilent()->getSegment(2)) : '';    
    $data = array(
        "stitle" => ''
        , "function_grp_name" => ""
        , "function_name" => ""
    );
    if ($list){
        foreach ($list as $row) {
            if($row['function_controller'] == $function_controller && $function_controller != ''){
                $stitle = ($uri ? lang($uri) . ' ' : '') . $row['function_name'];
                if($uri == 'id' && get_cookie('lang_code',true) == "EN"){
                    $stitle = $row['function_name'] . ($uri ?  ' ' . lang($uri) : '');
                }
                
                $data = array(
                    "stitle" => $stitle
                    , "function_grp_name" => $row['function_grp_nm']
                    , "function_name" => $row['function_name']
                );
            }
        } 
    }
    return $data;
}

