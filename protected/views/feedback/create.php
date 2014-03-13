<div data-role="page" id="myfriend">
    <div data-theme="b" data-role="header">
        <a href="#" data-rel="back">后退</a>
        <h3>意见反馈</h3>
    </div>
    
    <div data-role="content">
        <form action="<?php echo $this->createUrl('feedback/create'); ?>" name="feedback" method="post" data-ajax="false">
        <div data-role="fieldcontain">
            <textarea name="feedback" id="feedback" style="height:auto" rows="8" cols="40" data-val="true" data-val-required="反馈的问题不能为空" placeholder="请输入您要反馈的问题"></textarea>
        </div>
        <div data-role="fieldcontain">
            <input type="submit" value="提交" data-theme="a"/>
        </div>
        </form>
    </div>

    <p class="copyright">欢迎关注往来微信公众号：wanglairm<br />往来人脉-让人人都有自己的微名片<br />Powered by <a href="http://www.wanglai.cc">wanglai.cc</a></p>

    
</div>