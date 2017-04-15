<?php

    // header("Content-type:text/html;charset=utf-8");

    function check_login(){
        @session_start();
        return isset($_SESSION['user']);
    }

    function getIP() /*获取客户端IP*/
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }

    function cout($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    function stand_date($arr = array()){
        foreach($arr as $i => $item){
            $arr[$i]['createtime'] = date('Y-m-d H:i:s', $arr[$i]['createtime']);

            //缩减title
            if($arr[$i]['title']){
                $arr[$i]['title_detail'] = $arr[$i]['title'];
                if(strlen($arr[$i]['title']) > 45)
                    $arr[$i]['title'] = mb_substr($arr[$i]['title'], 0, 45)."···";
            }

            //获取回复数
            if($arr[$i]['messageid']){
                $arr[$i]['reply'] = D('Reply')->replyCounts($arr[$i]['messageid']);
            }
            elseif($arr[$i]['yq_message.messageid']){
                $arr[$i]['reply'] = D('Reply')->replyCounts($arr[$i]['yq_message.messageid']);
            }
            elseif($arr[$i]['a.messageid']){
                $arr[$i]['reply'] = D('Reply')->replyCounts($arr[$i]['a.messageid']);
            }else{$arr[$i]['reply'] = 0;}
        }
        return $arr;
    }

    function arr_clean($arr = array()){
        foreach($arr as $i =>$item){
            $arr[$i] = trim($arr[$i]);
            if($arr[$i] == "" || $arr[$i] == null){
                return 0;
            }
        }
        return 1;
    }

    function max_page($arr){
        return  ceil(count($arr)/10);
    }

