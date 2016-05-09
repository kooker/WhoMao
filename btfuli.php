<?php
/*************************************************
* Github https://github.com/kooker/WhoMao
* https://www.whomao.com/
*************************************************/
header("X-Accel-Buffering: no");
header("Content-Encoding: none");
header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
#set_time_limit(0);
ini_set('max_execution_time', '180');

if($_SERVER['REQUEST_URI']) {
	$temp=urldecode($_SERVER['REQUEST_URI']);
if(strpos($temp, '<') !== false || strpos($temp, '>') !== false || strpos($temp, '(') !== false || strpos($temp, '"') !== false) {
	exit('Request Bad url');
    }
}

function escape($value){
	$value=is_array($value) ? array_map('escape',$value):htmlspecialchars(trim($value));
	return get_magic_quotes_gpc()?$value:addslashes($value);
}

$_GET=array_map('escape', $_GET);
$_POST=array_map('escape', $_POST);
$_COOKIE=array_map('escape', $_COOKIE);
$_REQUEST=array_map('escape', $_REQUEST);

function get_result($url){
	$header=array(
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36',
		'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
		'Accept language: zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4'
      );
	$ch=curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_HEADER,0);
	curl_setopt ($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	#if($error=curl_error($ch)){die($error);}
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

function html_header(){
	$html_header='<!doctype html><html lang="zh-cn"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" /><title>'.trim($_REQUEST['keyword']).' - 虎毛磁力种子搜索 - '.$_SERVER['HTTP_HOST'].'</title><link rel="shortcut icon" href="favicon.ico" /><link rel="stylesheet" href="bt.min.css" /></head><body><div><article><header><h1>'.trim($_REQUEST['keyword']).'的相关资源</h1></header></article>';
	return $html_header;
}

function html_footer(){
	$html_footer='</div><footer>&#169;&#160;'.date("Y").'&#160;<a href="https://blog.kooker.jp/">Hi kooker</a></footer></body></html>';
	return $html_footer;
}

echo html_header();

if(empty($_REQUEST["keyword"])) exit('<p>请输入关键词</p></div>'.html_footer());
$keyword=urldecode(trim($_REQUEST['keyword']));
$site_url='http://www.btfuli.net'; //目标网站
$page=isset($_GET['page']) ? $_GET['page'] : 1;
$search_url=$site_url.'/list/'.$keyword.'-s1d-'.$page.'.html'; //目标网站搜索URL
$result=get_result($search_url);
preg_match("/<div\sclass=\"rststat\">找到 (.*?) 条结果<\/div>/is", $result, $rststat);
if(0==$rststat[1]) exit('<p>对不起，暂时没有关于“'.trim($_REQUEST['keyword']).'”的下载资源！请尝试去掉特殊符号，增减关键词后重新搜索</p></div>'.html_footer());
$total=str_replace(',','',$rststat[1]); //去除统计中的逗号
$pagesize = 10; //目标网站每页显示搜索条数
$pagecount = $total % $pagesize == 0 ? $total/$pagesize : ceil($total/$pagesize);
$pagelimit='10'; //限制显示页数，默认为最大显示10页
if($pagecount>1){
for($i=1;$i<=$pagecount && $i<=$pagelimit;$i++){
	if($i==$page){
	echo '<div id="pagination"><a href="//'.$_SERVER['HTTP_HOST'].'btfuli.php?keyword='.$keyword.'&page='.$i.'" class="current"><span>'.$i.'</span></a></div>';
	}else{echo '<div id="pagination"><a href="//'.$_SERVER['HTTP_HOST'].'btfuli.php?keyword='.$keyword.'&page='.$i.'"><span>'.$i.'</span></a></div>';}
}}

preg_match_all("/<a\stitle=\"(.*?)\"\starget=\"_blank\"\shref=\"\/info\/(.*?)\">/is", $result, $hash_id);
preg_match_all("/<dt>[^>]+<span>(.*?)<\/span>[^>]+[^>]+<span>(.*?)<\/span>[^>]+[^>]+<span>(.*?)<\/span>[^>]+/is", $result, $file_info);
#print_r($hash_id);
#print_r($file_info);
$hash_count=count($hash_id[1]);
for($i=0;$i<$hash_count;$i++){
	@ob_flush();
	flush();
	usleep(500);
	echo  '<p>资源名称：',$hash_id[1][$i],'<br />';
	echo '创建时间：',$file_info[3][$i],'<br />';
	echo '资源大小：',$file_info[1][$i],'<br />';
	echo '资源文件：',$file_info[2][$i],'个<br />';
	echo '磁力链接：magnet:?xt=urn:btih:',strtoupper($hash_id[2][$i]),'<br />';
	echo '迅雷种子库下载：<a target="_blank" href="http://bt.box.n0808.com/',substr(strtoupper($hash_id[2][$i]), 0, 2),'/',substr(strtoupper($hash_id[2][$i]), -2, 2),'/',strtoupper($hash_id[2][$i]),'.torrent">',strtoupper($hash_id[2][$i]),'.torrent</a><br />';
	echo 'torrent.org.cn 备用下载：<a target="_blank" href="http://www.torrent.org.cn/Home/Torrent/download.html?hash=',strtoupper($hash_id[2][$i]),'">',strtoupper($hash_id[2][$i]),'.torrent</a><br />';
	echo 'torcache.net 备用下载：https://torcache.net/torrent/',strtoupper($hash_id[2][$i]),'.torrent</p>';
}
echo html_footer();