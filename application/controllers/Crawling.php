<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once realpath(dirname(__FILE__).'/Base.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class Crawling extends CI_Controller {
    public $res;
    public $dic;
    public $lastPage;
    public $siteMap;
    public $cnt = 0;
    
    public function __construct()
    {
        parent::__construct();
        $this->siteMap = array('잡코리아', '사람인', '인크루트', '원티드');
        $this->dic = array(
                        '잡코리아' => array(
                            'url' => 'https://www.jobkorea.co.kr/Search/?stext=',
                            'pageInfo' => array('type'=>'page', 'pageName'=>'Page_No', 'limit'=>20),
                            'name' => '.recruit-info .list-default .post .post-list-corp a', 
                            'contents'=>'.recruit-info .list-default .post .post-list-info a', 
                            'date'=>'.recruit-info .post-list-info span.date'
                        ),
                        '사람인' => array(
                            'url' => 'https://www.saramin.co.kr/zf_user/search/recruit?searchword=',
                            'pageInfo' => array('type'=>'page', 'pageName'=>'recruitPage', 'limit'=>40),
                            'name'=>'.area_corp .corp_name a', 
                            'contents'=>'.item_recruit .area_job h2.job_tit a', 
                            'date'=>'.item_recruit .job_date span.date'
                        ),
                        '인크루트' => array(
                            'url' => 'https://search.incruit.com/list/search.asp?col=job&kw=',
                            'pageInfo' => array('type'=>'paging', 'pageName'=>'startno', 'limit'=>30),
                            'name'=>'.cBbslist_contenst .cl_top a.cpname', 
                            'contents'=>'.cBbslist_contenst ul.c_row .cell_mid .cl_top a', 
                            'date'=>'.cBbslist_contenst ul.c_row .cell_last .cl_btm span:nth-child(1)'
                        ),
                        // '원티드' => array(
                        //     'url' => 'https://www.wanted.co.kr/search?query=',
                        //     'name'=>'.JobCard_companyName__vZMqJ', 
                        //     'contents'=>'.JobCard_title__ddkwM', 
                        //     'date'=>'.cBbslist_contenst ul.c_row .cell_last .cl_btm span:nth-child(1)'
                        // ),
                    );
    }

    public function index()
    {
        $this->load->library('pagination');
        $config['base_url'] = 'http://localhost/ci/index.php/crawling';
        $config['total_rows'] = 500;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);
        //echo $this->pagination->create_links();exit;

        $data['res'] = [];
        $getSearch = isset($_GET['search']) ? $_GET['search'] : '';
        $getType = isset($_GET['type']) ? $_GET['type'] : '';
        $getPageCnt = isset($_GET['pageCnt']) ? $_GET['pageCnt'] : 30;
        $getPage = isset($_GET['page']) ? $_GET['page'] : 1;

        if ($getSearch)
        {
            if ($getType)
            {
                $arr = $this->dic[$getType];
                for ($i=0; $i<3; $i++) {
                    $url = $arr['url'].$getSearch."&".$arr['pageInfo']['pageName']."=";
                    if ($arr['pageInfo']['type'] == 'paging') {
                        $url .= ($arr['pageInfo']['limit'] * $i);
                    } else {
                        $url .= $i+1;
                    }
                    //$this->res['site'][$i] = $_GET['type'];
                    $this->scrapWebpage($url, $getType, array('name'=>$arr['name'],'contents'=>$arr['contents'],'date'=>$arr['date']));
                }
            } 
            else
            {
                $num = 0;
                foreach ($this->dic as $k => $arr) {
                    //$this->res['site'][$num++] = $k;
                    $this->scrapWebpage($arr['url'].$getSearch, $k, array('name'=>$arr['name'],'contents'=>$arr['contents'],'date'=>$arr['date']));
                }
                //잡코리아
                // $this->scrapWebpage('https://www.jobkorea.co.kr/Search/?stext='.$getSearch, array('name'=>'.recruit-info .list-default .post .post-list-corp a', 'contents'=>'.recruit-info .list-default .post .post-list-info a', 'date'=>'.recruit-info .post-list-info span.date'));
                // //사람인
                // $this->scrapWebpage('https://www.saramin.co.kr/zf_user/search?searchword='.$getSearch, array('name'=>'.area_corp .corp_name a', 'contents'=>'.item_recruit .area_job h2.job_tit a span', 'date'=>'.item_recruit .job_date span.date'));
                // //인크루트
                // $this->scrapWebpage('https://search.incruit.com/list/search.asp?col=job&kw='.$getSearch, array('name'=>'.cBbslist_contenst .cl_top a.cpname', 'contents'=>'.cBbslist_contenst ul.c_row .cell_mid .cl_top a', 'date'=>'.cBbslist_contenst ul.c_row .cell_last .cl_btm span:nth-child(1)'));
                //점핏
                //$this->scrapWebpage('https://www.jumpit.co.kr/search?keyword='.$getSearch, array('name'=>'header ul li a', 'contents'=>'#search_tabpanel_overview .JobCard_title__ddkwM'));
            }
        }
        
        $data['siteMap'] = $this->siteMap;
        $data['get']    = array("search" => $getSearch, "type" => $getType, "pageCnt" => (int)$getPageCnt, "page" => (int)$getPage);
        $data['list']    = $this->res;
        $data['res']    = $this->getList($data);
        $data['lastPage']    = $this->lastPage;
        
        $this->load->view('recruitment', $data);
    }

    public function test()
    {
        $this->load->view('test');
    }

    public function getList($data)
    {
        $get = $this->input->get();
        $page = $data['get']['page'];
        $perPage = $data['get']['pageCnt'];  

        $pageFirstResult = (($page-1) * $perPage);
        $lastPage = ceil((count($data['list'])) / $perPage);
        $this->lastPage = $lastPage;
        $pageLastResult = $pageFirstResult + $perPage;
        if ($page === strval($lastPage)) {
            $pageLastResult = count($data['list']);
        }

        return array_slice($data['list'], $pageFirstResult, $perPage);
    }

    public function scrapWebpage(string $url, string $site, array $selector = [])
    {
        // 열기
        try {
            $client = new GuzzleClient();
            $response = $client->request('GET', $url);
        } catch (GuzzleException $e) {
            die('Exception');
        }

        if ($response->getStatusCode() == 200) {
            // 찾기
            $html = strval($response->getBody());

            foreach ($selector as $key => $sel) {
                $num = $this->cnt;
                $selArr = [];
                $crawler = new Crawler($html);
                $crawler = $crawler->filter($sel);
            
                // 보기
                foreach ($crawler as $domElement) {
                    if ($key == 'contents') { 
                        $selArr['title'] = $domElement->nodeValue;
                        $href = $domElement->getAttribute('href');
                        $selArr['link'] = strpos($href, 'http') !== false ? $href : substr($url, 0, strpos($url, '/', 8)).$href;
                    } else {
                        $selArr = $domElement->nodeValue;
                    }
                    $this->res[$num]['site'] = $site;
                    $this->res[$num++][$key] = $selArr;
                    //$this->res['site'][] = $site;
                    //$this->res[$key][] = $domElement->nodeValue;
                    //$this->res[$key][] = $domElement->ownerDocument->saveHTML($domElement);
                }
                //$this->res[$key][] = $selArr;
            }
            $this->cnt = $num;
        }

        
        //return $res;
    }

}



