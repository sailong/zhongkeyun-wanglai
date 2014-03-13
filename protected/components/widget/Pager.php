<?php
class Pager extends CBasePager
{
	
	/**
	 * 首页链接文字
	 * @var string
	 */
	public $firstPageLabel = '首页';
	/**
	 * 上一页链接文字
	 * @var string 
	 */
	public $prevPageLabel = '上一页';
	
	/**
	 * 下一页链接文字
	 * @var string
	 */
	public $nextPageLabel = '下一页';
	
	/**
	 * 尾页链接文字
	 */
	public $lastPageLabel = '尾页';
	
	/**
	 * 每页大小
	 * @var int
	 */
	public $pageSize = 10;
	
	/**
	 * 总数
	 * @var int
	 */
	public $itemCount;
	
	/**
	 * html选项
	 * @var array
	 */
	public $htmlOptions = array('style' => 'margin-bottom:10px');
	
	/**
	 * 初始化,设置默认样式
	 * @see CWidget::init()
	 */
	public function init() 
	{
		if(!isset($this->htmlOptions['id'])) 
		{
			$this->htmlOptions['id']=$this->getId();
		}
		if(!isset($this->htmlOptions['class'])) 
		{
			$this->htmlOptions['class']='pages'; //默认CSS样式名为 
		}
	}
	
	
	public function run()
	{
		if ($this->getItemCount() <= $this->getPageSize())
			return;
		$pageLinks = $this->createPageLinks();
		echo CHtml::tag('div',$this->htmlOptions,join('',$pageLinks));
	}
	
	/**
	 * 创建分页连接
	 * @return array
	 */
	protected function createPageLinks()
	{
		$links = array();
		$totalPage = $this->getPageCount();
		$currentPage = $this->getCurrentPage(false);
		$links[] = $this->createPageLink($this->firstPageLabel,0);
		$prePage = ($currentPage-1) == 0 ? 0 : $currentPage-1;
		$links[] = $this->createPageLink($this->prevPageLabel, $prePage);
		$nextPage = ($currentPage+1) >= $totalPage ? $totalPage-1 : $currentPage+1;
		$links[] = $this->createPageLink($this->nextPageLabel, $nextPage);
		$links[] = $this->createPageLink($this->lastPageLabel, $totalPage-1);
		return $links;
	}
	
	/**
	 * 创建每一个链接
	 * @param string $label 链接文字
	 * @param int $page 要连接的页码
	 * @return string  完整的一个连接
	 */
	protected function createPageLink($label,$page)
	{
		$linkUrl = $this->createPageUrl($page);
		return CHtml::link($label,$linkUrl,array('data-ajax'=>"false",));
	}
	
}