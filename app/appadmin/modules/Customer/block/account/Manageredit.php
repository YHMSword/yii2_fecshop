<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Customer\block\account;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit  extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
	public  $_saveUrl;
	
	
	public function init(){
		$this->_saveUrl = CUrl::getUrl('customer/account/managereditsave');
		parent::init();
	}
	# 传递给前端的数据 显示编辑form	
	public function getLastData(){
		
		return [
			'editBar' 	=> $this->getEditBar(),
			'textareas'	=> $this->_textareas,
			'lang_attr'	=> $this->_lang_attr,
			'saveUrl' 	=> $this->_saveUrl,
		];
	}
	
	public function setService(){
		$this->_service 	= Yii::$service->customer;
	}
	
	
	
	public function getEditArr(){
		$deleteStatus = Yii::$service->customer->getStatusDeleted();
		$activeStatus = Yii::$service->customer->getStatusActive();
		
		return [
			[
				'label'=>'firstname',
				'name'=>'firstname',
				'display'=>[
					'type' => 'inputString',
					
				],
				'require' => 0,
			],
			
			[
				'label'=>'lastname',
				'name'=>'lastname',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			
			[
				'label'=>'email',
				'name'=>'email',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 1,
			],
			
			[
				'label'=>'Password',
				'name'=>'password',
				'display'=>[
					'type' => 'inputPassword',
				],
				'require' => 0,
			],
			
			
			[
				'label'=>'用户状态',
				'name'=>'status',
				'display'=>[
					'type' => 'select',
					'data' => [
						$activeStatus 	=> '激活',
						$deleteStatus 	=> '关闭',
					]
				],
				'require' => 1,
				'default' => 1,
			],
			
			[
				'label'=>'订阅邮件',
				'name'=>'is_subscribed',
				'display'=>[
					'type' => 'select',
					'data' => [
						1 	=> '是',
						2 	=> '否',
					]
				],
				'require' => 1,
				'default' => 1,
			],
		];
	}
	/**
	 * save article data,  get rewrite url and save to article url key.
	 */
	public function save(){
		$request_param 		= CRequest::param();
		$this->_param		= $request_param[$this->_editFormData];
		/**
		 * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
		 * you must convert string datetime to time , use strtotime function.
		 */
		$this->_service->save($this->_param);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"save success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
	}
	
	
	# 批量删除
	public function delete(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->remove($ids);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"remove data  success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
}