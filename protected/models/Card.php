<?php

class Card extends CActiveRecord
{
	
	const IMG_PREFIX = '/static/weixin/images/card/';
	
	public $category = array(
		
		1 => array(
			'id'   	=> 1,
			'title' => '圣诞贺卡',
			'cover' => '/static/weixin/images/card/1/card_1.jpg',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '圣诞贺卡1',
					'img' => '/static/weixin/images/card/1/1_s.jpg',
				),
				2 => array(
					'id' => 2,
					'title' => '圣诞贺卡2',
					'img'  => '/static/weixin/images/card/1/2_s.jpg'		
				),
				3 => array(
					'id' => 3,
					'title' => '圣诞贺卡3',
					'img' => '/static/weixin/images/card/1/3_s.jpg',
				),
				4 => array(
					'id' => 4,
					'title' => '圣诞贺卡4',
					'img' => '/static/weixin/images/card/1/4_s.jpg',
				),
				5 => array(
					'id' => 5,
					'title' => '圣诞贺卡5',
					'img'  => '/static/weixin/images/card/1/5_s.jpg'
				),
				6 => array(
					'id' => 6,
					'title' => '圣诞贺卡6',
					'img' => '/static/weixin/images/card/1/6_s.jpg',
				),
				7 => array(
					'id' => 7,
					'title' => '圣诞贺卡7',
					'img' => '/static/weixin/images/card/1/7_s.jpg',
				),
				8 => array(
					'id' => 8,
					'title' => '圣诞贺卡8',
					'img'  => '/static/weixin/images/card/1/8_s.jpg'
				),
				9 => array(
					'id' => 9,
					'title' => '圣诞贺卡9',
					'img' => '/static/weixin/images/card/1/9_s.jpg',
				),
				10 => array(
					'id' => 10,
					'title' => '圣诞贺卡10',
					'img' => '/static/weixin/images/card/1/10_s.jpg',
				),
				11 => array(
					'id' => 11,
					'title' => '爱蒂宝贺卡',
					'img' => '/static/weixin/images/card/1/11_s.jpg',
				),
				
			),
			'blessing' => array(
				'圣诞来临，大礼送给你：没有烟囱没关系，没准备袜子别着急，只要你的手机开启，就会收到我送你的大礼。字虽短情不浅，收到之后，嘴角弯弯，幸福绵绵！',
				'圣诞树上，挂满我的祝愿；点点烛光，摇曳我的思念；铃儿叮当，送上我的礼物；一条短信，寄托我的挂念。一年又是一年，为你祝福为你祈安。圣诞快乐！',	
				'即使雪花未舞，即使驯鹿停驻，圣诞依然光顾；有了祝福，虽是寒冬冷酷，温暖依旧如故。圣诞节，我许愿：愿快乐和你相依，幸福与你作伴！',
				'冰雪飘，白茫茫，温馨关怀暖心房；风儿吹，寒气涨，朋友真情莫忘掉；手机响，问候暖，情真意切早送上。祝你圣诞佳节快乐无恙，朋友关怀心中藏！',
				'鹿铃儿敲敲，小红帽儿高高，长胡子儿飘飘，圣诞节喧嚣热闹。看圣诞老人绝招，把幸福抓上雪橇，穿过烟囱迢迢，冲破寒风呼啸，誓送你快乐圣诞今朝！',
				'圣诞送礼有妙招：精美卡片脑后抛，纸张省下一大把；烟火虽美却危险，静静享受心更美；朋友祝福传千里，一句真心抵万金。祝你圣诞佳节幸福甜蜜蜜！',
				'片片雪花，传递我的祝福；阵阵冬风，送达我的思念。我愿是暖暖阳光，让你不惧严寒；我愿是圣诞老人，给你礼物满满。又到一年圣诞，祝你快乐吉祥！',
				'点击了整个冬天，却没有搜到我的容颜；复制了许多思念，却没有粘贴我的心间；下载了一堆思念，却一直藏在心里。给你个机会，圣诞请吃顿饭应该不难吧？',
				'洁白的雪花，晶莹剔透；冉冉的烛光，摇曳多姿；暖暖的炉火，跳跃飞舞；金色圣诞树，华彩宜人；鼓鼓的袜子，礼物多多。亲爱的，请接受我的圣诞祝福！',
				'雪花，洁白；红酒，香醇；烛光，朦胧；圣诞树，绿绿；霓虹灯，闪耀；短信息，温馨；祝福，浪漫。愿这浓浓的圣诞味道将你包围，祝你圣诞快乐！'
			)
		),
		
		2 => array(
			'id' => 2,
			'title' => '元旦贺卡',
			'cover' => '/static/weixin/images/card/2/card_2.jpg',
			'children' => array(
					1 => array(
						'id' => 1,
						'title' => '2014元旦贺卡1',
						'img' => '/static/weixin/images/card/2/1_s.jpg',
					),
					2 => array(
						'id' => 2,
						'title' => '2014元旦贺卡2',
						'img' => '/static/weixin/images/card/2/2_s.jpg',
					),
					3 => array(
						'id' => 3,
						'title' => '2014元旦贺卡3',
						'img' => '/static/weixin/images/card/2/3_s.jpg',
					),
					4 => array(
						'id' => 4,
						'title' => '2014元旦贺卡4',
						'img' => '/static/weixin/images/card/2/4_s.jpg',
					),
					5 => array(
						'id' => 5,
						'title' => '2014元旦贺卡5',
						'img' => '/static/weixin/images/card/2/5_s.jpg',
					),
					6 => array(
						'id' => 6,
						'title' => '2014元旦贺卡6',
						'img' => '/static/weixin/images/card/2/6_s.jpg',
					),
					7 => array(
						'id' => 7,
						'title' => '2014元旦贺卡7',
						'img' => '/static/weixin/images/card/2/7_s.jpg',
					),
					8 => array(
						'id' => 8,
						'title' => '2014元旦贺卡8',
						'img' => '/static/weixin/images/card/2/8_s.jpg',
					),
					9 => array(
						'id' => 9,
						'title' => '2014元旦贺卡9',
						'img' => '/static/weixin/images/card/2/9_s.jpg',
					),
					10 => array(
						'id' => 10,
						'title' => '2014元旦贺卡10',
						'img' => '/static/weixin/images/card/2/10_s.jpg',
					),
					
			),
			'blessing' => array(
				'时光华丽丽地来到2014年，友情的温暖一直存留心底，把千般万种的祝福浓缩到你眼底，愿好运全握在你手底。祝元旦快乐！',
				'元旦到，送你快乐“同心圆”：天圆地圆，天地之间爱心圆；心圆梦圆，心想事成事事圆；月圆人圆，阖家欢乐大团圆；你圆我圆，开心快乐心更圆！',
				'一【元】复始，【旦】夕即至，加急特【快】，欢【乐】贺岁，气象一【新】，【年】年有余，宏图【大】展，【吉】祥如意。祝你元旦快乐，新年大吉！',
				'辞旧迎新锣鼓闹，元旦祝福发三条：第一祝你身体好，健康快乐每一秒；第二祝你财运罩，财源广进忙数钞；第三祝你步步高，青云直上冲云霄！新年快乐！',
				'谱不出雅韵，吟不出诗句，抄不来思念，借不了神笔，转不动经轮，写不下美文，但灿烂的新年就要来到，真诚的祝福依然要送给你：提前祝元旦快乐！',
				'立马千山外，元旦祝福如天籁。岁月又更改，思念依然在。问候万里外，新年好运与你同在，成功为你等待，快乐为你盛开，幸福花儿开不败。',
				'我愿：新年的第一颗露珠因你而美丽晶莹，新年的第一个黎明因你而惬意宁静，那新年的第一缕阳光因你而温暖舒心情。衷心祝愿，元旦快乐！',
				'新年的雪花飘飘洒洒，新年的脚步滴滴答答，新年的爆竹劈劈啪啪，新年的烟花雾里看花，新年的祝福稀里哗啦，我的问候准时送达，来年的你肯定大发！',
				'元旦是美好的总结，就像句号；元旦是未来的开启，就像冒号；元旦是惊喜的祝福，就像感叹号；元旦是幸福的未知，就像省略号。愿你新年写满快乐的标点！',
				'元旦之快乐操：脑袋摇一摇，金钱满腰包；脖子晃一晃，元宝一箩筐；胳膊挥一挥，越长越甜美；屁股翘一翘，健康来报到；伸腿踢一踢，天天笑眯眯；快乐操要练，快乐在元旦。'	
			)
		),
			
		3 => array(
			'id' => 3,
			'title' => '新年贺卡',
			'cover' => '/static/weixin/images/card/3/card_3.jpg',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '2014新年贺卡1',
					'img' => '/static/weixin/images/card/3/1_s.png',
				),
				2 => array(
					'id' => 2,
					'title' => '2014新年贺卡2',
					'img' => '/static/weixin/images/card/3/2_s.png',
				),
				3 => array(
					'id' => 3,
					'title' => '2014新年贺卡3',
					'img' => '/static/weixin/images/card/3/3_s.jpg',
				),
				4 => array(
					'id' => 4,
					'title' => '2014新年贺卡4',
					'img' => '/static/weixin/images/card/3/4_s.jpg',
				),
				5 => array(
					'id' => 5,
					'title' => '2014新年贺卡5',
					'img' => '/static/weixin/images/card/3/5_s.jpg',
				),
				6 => array(
					'id' => 6,
					'title' => '2014新年贺卡6',
					'img' => '/static/weixin/images/card/3/6_s.jpg',
				),
				7 => array(
					'id' => 7,
					'title' => '2014新年贺卡7',
					'img' => '/static/weixin/images/card/3/7_s.jpg',
				),
				8 => array(
					'id' => 8,
					'title' => '2014新年贺卡8',
					'img' => '/static/weixin/images/card/3/8_s.jpg',
				),
				9 => array(
					'id' => 9,
					'title' => '2014新年贺卡9',
					'img' => '/static/weixin/images/card/3/9_s.jpg',
				),
				10 => array(
					'id' => 10,
					'title' => '2014新年贺卡10',
					'img' => '/static/weixin/images/card/3/10_s.jpg',
				),
				/* 11 => array(
					'id' => 11,
					'title' => '2014新年贺卡11',
					'img' => '/static/weixin/images/card/3/11_s.jpg',
				),
				12 => array(
					'id' => 12,
					'title' => '2014新年贺卡12',
					'img' => '/static/weixin/images/card/3/12_s.png',
				), */
			),
			'blessing' => array(
				'马年到，一祝家庭幸福，福傳千里；二愿財運亨通，通上徹下；三望龍馬精神，神采飛揚。',
				'金蛇飞舞，福从天降。又是一年辞旧岁，鞭炮声声唱新春。祝君身体康健长寿如女娲，祝君事业如蛇长久久。祝君爱情金坚如素贞，祝君阖家幸福又团圆。马年吉祥，给您拜年了！',
				'新春到，在抢票，急回家，看爹娘。亲兄弟好姐妹，过年早点回家看爸妈；常陪父母多联系，大家都是好儿女；朋友话最知心，心里常记孝为本。新春佳节预祝天下父母健康长寿，朋友您万事如意！ ',
				'有福藏福家家福，享福见福时时福，金福银福处处福，大福小福天天福，接福纳福年年福，守福祈福岁岁福。春节快乐！',
				'春节到，大拜年：一拜全家好；二拜困难少；三拜烦恼消；四拜不变老；五拜儿女孝；六拜幸福绕；七拜忧愁抛；八拜收入高；九拜平安罩；十拜乐逍遥。',
				'愿欢快的歌声，时刻萦绕你；愿欢乐年华，永远伴随您；愿欢乐的祝福，永远追随您。祝福您：春节愉快，身体健康，阖家欢乐，万事顺意！',
				'如果我是一片森林，我愿把成片的绿意送给你；如果我是大海，我便所壮阔力量送给你；如果我是大山，会把最奇骏风景送给你，如果我是沙漠，我宁愿干涸全部我自己！朋友，新年新气象，新年新希望！',
				'鱼跃龙门好有福，马年贺岁来送福。大福小福全家福，有福享福处处福。知福来福有祝福，清福鸿福添幸福。接福纳福年年福，守福祈福岁岁福！',
				'依旧，物依然，又是一年；想也好，忘也罢，本是平凡；今儿好，明更好，衷心祝愿；情也真，意也切，常驻心间。祝大家春节愉快！',
				'一个灿烂的微笑，一缕淡淡的春风，一声亲切的问候，一个衷心的祝福 ，都在这个温馨的日子里 ，来到您的身旁 。 新年到了，衷心地祝福您：年年圆满如意，月月事事顺心，日日喜悦无忧虑！'
			)
		),
		6 => array(
			'id' => 6,
			'title' => '元宵贺卡',
			'cover' => '/static/weixin/images/card/6/card_6.jpg',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '元宵贺卡1',
					'img' => '/static/weixin/images/card/6/5_s.jpg',
				),
				2 => array(
					'id' => 2,
					'title' => '元宵贺卡2',
					'img' => '/static/weixin/images/card/6/2_s.jpg',
				),
				3 => array(
					'id' => 3,
					'title' => '元宵贺卡3',
					'img' => '/static/weixin/images/card/6/3_s.jpg',
				),
				4 => array(
					'id' => 4,
					'title' => '元宵贺卡4',
					'img' => '/static/weixin/images/card/6/4_s.jpg',
				),
				6 => array(
					'id' => 6,
					'title' => '元宵贺卡5',
					'img' => '/static/weixin/images/card/6/1_s.jpg',
				),
				7 => array(
					'id' => 7,
					'title' => '元宵贺卡6',
					'img' => '/static/weixin/images/card/7/4_s.jpg',
				),
			),
			'blessing' => array(
				'正月十五月儿圆，想着汤圆润心甜;想买烟花又太费钱,不如回家去团圆！朋友们：元宵节，记着早点回家吃汤圆哦！今天最大的愿望就是大家阖家欢乐！',
				'用“平安”做面，用“健康”做水，揉成“幸福”皮；用“吉祥”做汁，用“如意”做料，捏成“幸运”馅，包成汤圆，用“快乐”烧，用“开心”煮，煮的“团圆”跳出来，煮的“祝福”叫起来，让你日子美美满满，让你全家团团圆圆，祝你元宵节快乐！',
				'月亮露出圆圆的脸，初春的风吹来丝丝的甜，龙年元宵来一碗，甜蜜欢笑充满美妙。元宵快乐，祝你在马年里，天天都有好运气，事事都能如你意，幸福生活甜如蜜！',
				'“摘下”一缕柔和月光，“揉成”一团绵绵软糖，“投进”一个快乐汤圆，“寄予”一份美好祝愿，一心一意祝福你，四季平安万事吉，祝合家团圆，元宵节快乐！',
				'元宵汤圆圆又甜，好友热情照心间，距离相隔有点远，问候寻常不会变。元宵佳节，怀念我们曾经携手与共的日子，期待今年我们能够把酒言欢。祝元宵快乐，阖家幸福！',
				'我用“亿元诚心”，“万元真心”，“千元爱心”，“百元舒心”，“十元开心”，“一元用心”，“十分”尽心，煮碗幸福汤圆送给你，祝你元宵节快乐，永远开心！',
				'吃下快乐汤圆，让甜蜜留在心中，喝下如意水汤，让美满遍布全身，打气顺心灯笼，让辉煌照亮前方，收下祝福短信，让幸福弥漫一生！元宵节快乐！天天好心情！',
				'尝一口柔劲劲的汤圆皮，让你平安健康，心旷神怡；嚼一口甜蜜蜜的汤圆馅，让你吉祥如意，生活美满；喝一口热腾腾的汤圆汤，让你开心快乐，神清气爽，愿你吃满满一碗汤圆，你全家美美满满团团圆圆，祝你元宵节快乐！',
				'月亮圆、十五到，皓月当空元宵闹；龙狮舞、灯笼亮，张灯节彩焰火放；高跷踩、汤圆跳，团团圆圆大年好。恭祝元宵节快乐！',
				'圆圆的锅里煮元宵，圆圆的元宵放圆盘，圆圆的圆盘圆桌上放，团圆的人们幸福圆满。祝你好事连连好梦圆圆，元宵节快乐！'
			)
		),
		7 => array(
			'id' => 7,
			'title' => '情人节贺卡',
			'cover' => '/static/weixin/images/card/7/card_7.jpg',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '情人节贺卡1',
					'img' => '/static/weixin/images/card/7/1_s.jpg',
				),
				2 => array(
					'id' => 2,
					'title' => '情人节贺卡2',
					'img' => '/static/weixin/images/card/7/2_s.jpg',
				),
				3 => array(
					'id' => 3,
					'title' => '情人节贺卡3',
					'img' => '/static/weixin/images/card/7/3_s.jpg',
				),
				4 => array(
					'id' => 4,
					'title' => '情人节贺卡4',
					'img' => '/static/weixin/images/card/7/4_s.jpg',
				),
			)
		),
		4 => array(
			'id' => 4,
			'title' => '生日贺卡',
			'cover' => '/static/weixin/images/card/4/card_4.png',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '生日贺卡1',
					'img' => '/static/weixin/images/card/4/1_s.png',
				),	
				2 => array(
					'id' => 2,
					'title' => '生日贺卡2',
					'img' => '/static/weixin/images/card/4/2_s.png',
				)
			)	
		),
		5 => array(
			'id' => 5,
			'title' => '健康贺卡',
			'cover' => '/static/weixin/images/card/5/card_5.jpg',
			'children' => array(
				1 => array(
					'id' => 1,
					'title' => '健康贺卡1',
					'img' => '/static/weixin/images/card/5/1_s.jpg',
				),
				2 => array(
					'id' => 2,
					'title' => '健康贺卡2',
					'img' => '/static/weixin/images/card/5/2_s.jpg',
				),
				3 => array(
					'id' => 3,
					'title' => '健康贺卡3',
					'img' => '/static/weixin/images/card/5/3_s.jpg',
				),
				4 => array(
					'id' => 4,
					'title' => '健康贺卡4',
					'img' => '/static/weixin/images/card/5/4_s.jpg',
				),
			)
		),
		
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AR_Follow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'card';
	}
	
	public function rules()
	{
		return array(
			array('content,cid,sub_cid,send_time,from_user','required'),
			array('content,to_user,from_user','length','max'=>200),
			array('from_mid','numerical', 'integerOnly'=>true)
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			 'from_mid' => '发送用户ID',
			'from_user' => '发送人',
			'to_user' => '接收人',
			'cid' => '贺卡ID',
			'sub_cid' => '贺卡PID',
			'content'  => '内容',
			'add_time' => '添加时间',
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'CardCategory', 'cid')
		);
	}
	
	public function getCategory($id=NULL)
	{
		return !empty($id)&&isset($this->category[$id]) ? $this->category[$id] : $this->category;
	}

	
	
	
	
}