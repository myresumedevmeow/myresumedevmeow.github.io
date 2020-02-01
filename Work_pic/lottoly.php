<?php
header('content-type application/json charset=utf-8');
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
ini_set('max_execution_time', 300);

ini_set('memory_limit', '-1');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

$postdata = file_get_contents("php://input");

$web_copy="http://lotto.mthai.com/";
$extend_web = file_get_contents($web_copy);

if (isset($postdata)) {

    $request = json_decode($postdata);

    $DATA = $request->send1;

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML($extend_web);
    $xpathProcessor = new DOMXPath($dom);

    $tagCode = "//div[@class='text-center show-for-medium']/h2/a/span";
    $tagName = $xpathProcessor->query($tagCode);

    foreach ($tagName as $tag_list) {
        $tagshow = $tag_list->nodeValue;
    }

    $number_top = "//p[@class='title text-center']";
    $top = "//div[@class='result callout text-center']";
    $reward = "//p[@class='reward text-center']";

    $number_down = "//div[@class='title text-center']";
    $down = "//div[@class='row column small-up-3 medium-up-5 lotto-result']";
    $reward_down = "//div[@class='desc text-center']";

    $number_top_ = $xpathProcessor->query($number_top);
    $top_ = $xpathProcessor->query($top);
    $reward_ = $xpathProcessor->query($reward);

    $number_down_ = $xpathProcessor->query($number_down);
    $down_ = $xpathProcessor->query($down);
    $reward_down_ = $xpathProcessor->query($reward_down);

////top////

    foreach ($number_top_ as $number_top_list ) {
        $number_top_listshow = $number_top_list->nodeValue;
        $get_number_top[]=$number_top_listshow;
    }

    foreach ($top_ as $top_list ) {

        $topshow = $top_list->nodeValue;
        $topshow = str_replace(" ","|",$topshow);
        $topshow = str_replace("\n","",$topshow); //ตัด \n
        $topshow = str_replace("\t","",$topshow); //ตัด \t
        $get_result[]=$topshow;
    }

    foreach ($reward_ as $reward_list ) {
        $reward_show = $reward_list->nodeValue;
        $get_reward[]=$reward_show;
    }

//down
    foreach ($number_down_ as $number_down_list ) {
        $number_down_listshow = $number_down_list->nodeValue;
        $get_number_down[]=$number_down_listshow;
    }

    foreach ($down_ as $down_list ) {
        $downshow = $down_list->nodeValue;
        $downshow = str_replace("\t","",$downshow); //ตัด \t
        $downshow = str_replace("\n"," ",$downshow); //ตัด \n
        $downshow = str_replace(" ","|",trim($downshow)); //ตัด ช่องว่าง

        $get_result_down[]=trim($downshow);
    }
    foreach ($reward_down_ as $reward_down_list ) {
        $reward_down_show = $reward_down_list->nodeValue;

        $reward_down_ = explode(" ",$reward_down_show);
        $get_reward_down[]=$reward_down_[2]." ".$reward_down_[3]." ".$reward_down_[4];
    }


    $row_set = array();

    $row["lottoly_day"]=$tagshow;
    $row["send"] = $DATA['send1'];

    $index = 0;

    for($i = $index; $i < count($get_number_top); $i++){
        $row["date"][$i]["name_reward"] = $get_number_top[$i];
        $row["date"][$i]["result"] = explode("|",$get_result[$i]);
        $row["date"][$i]["reward"] = $get_reward[$i];
        $index = $i;
    }

    for($i = 0; $index+$i < $index+count($get_result_down); $i++){
        $row["date"][$index+$i]["name_reward"] = $get_number_down[$i];
        $row["date"][$index+$i]["result"]= explode("|",$get_result_down[$i]);
        $row["date"][$index+$i]["reward"] = $get_reward_down[$i];
    }

    $input_=$DATA;
    $row["input___"]=$input_;

    if($DATA!=''){

        $a0 =array( $row["date"][0]["result"]);
        $a1 =array( $row["date"][1]["result"]);
        $a2 =(explode("|",$row["date"][2]["result"]));
        $a3 =(explode("|",$row["date"][3]["result"]));
        $a4 =(explode("|",$row["date"][4]["result"]));
        $a5 =(explode("|",$row["date"][5]["result"]));
        $a6 =(explode("|",$row["date"][6]["result"]));
        $a7 =(explode("|",$row["date"][7]["result"]));


        if (in_array($input_, $a0, true)) {
            $show_finish = " ยินดีด้วย คุณถูก ".$row["date"][0]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a1, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][1]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a2, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][2]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a3, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][3]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a4, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][4]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a5, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][5]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a6, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][6]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else if (in_array($input_, $a7, true)) {
            $show_finish= " ยินดีด้วย คุณถูก ".$row["date"][7]["name_reward"];
            $row["check_"]=$show_finish;
        }
        else {
            $show_finish= "เสียใจด้วย..";
            $row["check_"]=$show_finish;
        }
    }
    else{
        $show_finish= "";
        $row["check_"]=$show_finish;
    }

    $row_set[] = $row;
    echo json_encode($row_set);
}

?>