<div data-role="page" id="viewjob">
    <div data-theme="b" data-role="header" data-position="fixed">
        <h3>系统提示</h3>
    </div>
    <div class="" data-role="content">
        <p align="center"><?php echo $message;?></p>
        <!--  <a data-role="button" data-theme="b" href="" data-ajax="false">查看我的微名片</a>-->
    </div>
</div>
    <div id="operate-ok"></div>
   
 <script>
var code = "<?php echo $status;?>";
var url = "<?php echo $url;?>";
function j()
{
	if(url)
	{
		location.href="<?php echo $url;?>";
	}else
	{
		history.back(-1);
	}
}
setTimeout("j()", 3000)
 </script>