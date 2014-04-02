<?php
class SearchForm extends CFormModel
{
   
    public $keyword;
    public $status;
    public $wanglai_number;
    public $activity_create;
    public $send_type;

    public function rules()
    {
        return array(
            array('keyword,status', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'keyword'=>'keyword',
        	'status' => 'status',
        	'wanglai_number' => 'wanglai_number',
        	'activity_create'=>'activity_create',
            'send_type'=>'send_type',
        );
    }
}
