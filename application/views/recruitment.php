<?php
echo '<pre>';
ob_start();
//print_r( $res );
//print_r( array_slice($res, 0, 2) );
echo htmlspecialchars( ob_get_clean() );
echo '</pre>';
//exit;


// $page = isset($_GET['page']) ? $_GET['page'] : 1;
// $results_per_page = isset($get['pageCnt']) ? $get['pageCnt'] : 30;  
// $page_first_result = (($page-1) * $results_per_page);
// $last_page = ceil((count($res)) / $results_per_page);
// $page_last_result = $page_first_result + $results_per_page;
// if ($page === strval($last_page)) {
//     $page_last_result = count($res);
// }

// $res = array_slice($res, $page_first_result, $page_last_result);


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
                <?php foreach ($siteMap as $site) { ?>
                <option value="<?=$site?>" <?php if($get['type'] == $site){echo 'selected';}?>><?=$site?></option>
                <?php } ?>
            </select>
            <select class="form-select me-2" name="pageCnt" id="pageCnt">
                <option value="">전체</option>
                <option value="50" <?php if($get['pageCnt'] == '50'){echo 'selected';}?>>50개까지</option>
                <option value="100" <?php if($get['pageCnt'] == '100'){echo 'selected';}?>>100개까지</option>
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
                    <th scope="col">사이트</th>
                    <th scope="col">기업명</th>
                    <th scope="col">채용내용</th>
                    <th scope="col">접수기간</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $num = 1;
                    for ($i=0; $i<count($res); $i++) { 
                ?>
                <tr>
                    <td><?=$num++?></td>
                    <td><?=$res[$i]['site']?></td>
                    <td><?=$res[$i]['name']?></td>
                    <td><a href="<?=$res[$i]['contents']['link']?>" target="_blank"><?=$res[$i]['contents']['title']?></a></td>
                    <td><?=$res[$i]['date']?></td>
                </tr>
                <?php 
                    }
                ?>
                
            </tbody>
        </table>
        <?php } ?>

        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li> -->
                    <?php for($page = 1; $page<= $lastPage; $page++) :?>
                    <li class="page-item"><a class="page-link" href="<?=$_SERVER['PHP_SELF'].'?search='.$get['search'].'&type='.$get['type'].'&pageCnt='.$get['pageCnt'].'&page='.$page?>"><?=$page?></a></li>
                    <?php endfor ?>
                    <!-- <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>
