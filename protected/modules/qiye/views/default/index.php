<?php 
$scriptFile = '/static/qiye/js/highcharts.js';
Yii::app()->clientScript->registerScriptFile($scriptFile,CClientScript::POS_HEAD);

$x = join(',',array_keys($latestEmployeeStat));
$d = join(',',array_values($latestEmployeeStat));


$script = <<<EOF
$(function () {
        $('#highcharts1').highcharts({
            title: {
                text: '员工数据',
            },
            xAxis: {
            	type: 'datetime',
                categories: [{$x}]
            },
            yAxis: {
                title: {
                    text: '人'
                },
                tickInterval:1,
                min:0
            },
            tooltip: {
                valueSuffix: '人'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '员工人数',
                data: [{$d}]
            }]
        });
    });

EOF;

Yii::app()->clientScript->registerScript('chart',$script);
?>

<?php 
$this->title = '首页';
?>

<div class="sortable row-fluid ui-sortable">
	<a data-rel="tooltip" class="well span3 top-block" href="<?php echo $this->createUrl('/qiye/employee'); ?>">
		<span class="icon32 icon-blue icon-users"></span>
		<div>员工总数</div>
		<div><?php echo $totalEmployee;?></div>
	</a>

	<a data-rel="tooltip" class="well span3 top-block" href="<?php echo $this->createUrl('/qiye/activity'); ?>">
		<span class="icon32 icon-blue icon-calendar"></span>
		<div>活动总数</div>
		<div><?php echo $totalActivity; ?></div>
	</a>

	<a data-rel="tooltip" class="well span3 top-block" href="<?php echo $this->createUrl('/qiye/contacts'); ?>">
		<span class="icon32 icon-blue icon-contacts"></span>
		<div>微群总数</div>
		<div><?php echo $totalContacts; ?></div>
	</a>
	
	<a data-rel="tooltip" class="well span3 top-block" href="<?php echo $this->createUrl('/qiye/verify'); ?>">
		<span class="icon32<?php if($totalApply>0) echo ' icon-red'?> icon-volume-on"></span>
		<div>申请总数</div>
		<div><?php echo $totalApply; ?></div>
	</a>
</div>

<div class="row-fluid sortable ui-sortable">
<div class="box span6">
	<div class="box-header well" data-original-title="">
		<h2>数据统计</h2>
	</div>
	<div class="box-content">
	<?php $pv = $model->stat->pv_counts;?>
	<p>总PV：<?php echo !empty($pv) ? $pv : 0;?></p>
	
	<p>总UV：<?php echo $model->views;?></p>
	
	
	</div>
</div>
<div class="box span6">
	<div class="box-header well" data-original-title="">
		<h2>最近7天员工数量曲线</h2>
	</div>
	<div class="box-content" id="highcharts1">
	
	
	
	</div>
</div>


</div>
