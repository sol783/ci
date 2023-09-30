<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../assets/js/code.jquery.com_jquery-3.7.1.min.js"></script>
    <title>Document</title>
</head>
<body>
<?php
    header("Content-Type: text/html; charset=utf-8"); 
    ini_set("allow_url_fopen",1);
    include "simple_html_dom.php";

    // $data = file_get_html("https://www.jobkorea.co.kr/");
    // $codeArr = $data->find("li#gnbGi a");

    // foreach($codeArr as $d){
    //     echo "<div>{$d}</div>";
    // }
    // exit;

    $data = file_get_html("https://www.jumpit.co.kr");
    $codeArr = $data->find(".sc-uojGG.hkKUcJ .sc-XxNYO.hxPJPt h2.position_card_info_title");

    foreach($codeArr as $d){
        echo "<div>{$d}</div>";
    }
    exit;

?>
    <form action="">
        <input type="text" name="code">
        <button type="submit">검색</button><br/>
    </form>
    <table>
        <thead>
            <tr>
                <th>코드</th>
                <th>종목</th>
                <th>단가</th>
            </tr>
        </thead>
        <tbody>
<?php

    foreach($code as $val){
        $data = file_get_html("https://finance.naver.com/item/main.nhn?code={$val}");
        //echo "https://finance.naver.com/item/main.nhn?code={$val}";
        //exit;

        echo "<tr><td>{$val}</td>";

        $c = $data->find("#middle .wrap_company h2 a");
        foreach($c as $d){
            echo "<td>{$d}</td>";
        }
        $a = $data->find(".rate_info .today p.no_today em span.blind");
        //$a = $data->find(".sptxt.sp_txt2");
        foreach($a as $b){
            echo "<td>{$b}</td></tr>";
        }
    }
?>
        </tbody>
    </table>
</body>
</html>
<script>
$(document).ready(function() {
   
});
</script>