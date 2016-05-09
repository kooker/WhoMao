<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
    <title>虎毛磁力种子搜索 - <?=$_SERVER['HTTP_HOST'];?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/0.4.2/weui.min.css" />
    <style>.hidden{display:none;}.hd{padding:2em 0 0.8em;}.hd h1{text-align:center;font-size:34px;color:#3cc51f;font-size:400;margin:0 15%;}</style>
</head>
<body>
<div class="page">
    <div class="hd">
        <h1>磁力种子搜索</h1>
    </div>
    <form autocomplete="off" action="btfuli.php" method="get">
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <div class="weui_label">关键词</div>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input type="text" name="keyword" class="weui_input" placeholder="例如您可以尝试番号、演员名等">
                </div>
            </div>
        <div class="weui_btn_area">
            <input type="submit" class="weui_btn weui_btn_primary" value="磁力搜索">
        </div>
    </form>
</div>
<footer align="center">&#169;&#160;<?=date("Y");?>&#160;<a href="https://blog.kooker.jp/">Hi kooker</a></footer>
</body>
</html>