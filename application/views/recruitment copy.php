<?php
echo '<pre>';
ob_start();
print_r( $res );
echo htmlspecialchars( ob_get_clean() );
echo '</pre>';
exit;

?>
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
<style>
    .wrap{padding:30px 50px;}
</style>
</head>
<body>
    <div class="wrap">
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" name="search" placeholder="키워드를 입력하세요" aria-label="Search" value="<?=$get['search']?>">
            <select class="form-select me-2" name="type" id="type">
                <option value="">전체</option>
                <?php foreach ($res['siteMap'] as $site) { ?>
                <option value="<?=$site?>" <?php if($get['type'] == $site){echo 'selected';}?>><?=$site?></option>
                <?php } ?>
            </select>
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        
        <!-- <form action="">
            <input type="text" name="search">
            <button type="submit">검색</button><br/>
        </form> -->
        <?php if ($get['search']) { ?>
        <table class="table mb-2">
            <thead>
                <tr>
                    <th scope="col">번호</th>
                    <!-- <th scope="col">사이트</th> -->
                    <th scope="col">기업명</th>
                    <th scope="col">채용내용</th>
                    <th scope="col">접수기간</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $num = 1;
                    for ($i=0; $i<count($res['name']); $i++) { 
                        for ($j=0; $j<count($res['name'][$i]); $j++) { 
                ?>
                <tr>
                    <td><?=$num++?></td>
                    <!-- <td><?=$res['site'][$i]?></td> -->
                    <td><?=$res['name'][$i][$j]?></td>
                    <td><a href="<?=$res['contents'][$i]['link'][$j]?>" target="_blank"><?=$res['contents'][$i]['title'][$j]?></a></td>
                    <td><?=$res['date'][$i][$j]?></td>
                </tr>
                <?php 
                        }
                    }
                ?>
                
            </tbody>
        </table>
        <?php } ?>
    </div>
</body>
</html>
