<?php
namespace Drupal\my_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Messenger;


class MyformController extends ControllerBase
{
	public function Table()
	{
		$header_table = ['id'=>t('ID'),'name'=>t('Name'),'age'=>t('Age'),'email'=>t('Email'),'gender'=>t('Gender'),'opt'=>t('Operation'),'opt1'=>t('Operation'),];
		$row = [];

		$conn = Database::getConnection();

		$query = $conn->select('my_form','m');
		$query->fields('m',['id','name','age','email','gender']);
		$result = $query->execute()->fetchAll();
 
		foreach($result as $value)
		{
			$delete = Url::fromUserInput('/my_form/form/delete/'.$value->id);
			$edit = Url::fromUserInput('/my_form/form/data?id='.$value->id);

			$row[]= ['id'=>$value->id,'name'=>$value->name,'age'=>$value->age,'email'=>$value->email,'gender'=>$value->gender,'opt'=>Link::fromTextAndUrl('Edit',$edit)->toString(),'opt1'=>Link::fromTextAndUrl('Delete',$delete)->toString(),];
		}
        
        $add = Url::fromUserInput('/my_form/form/data');

        $text = "Add User";

        $data['table'] = ['#type'=>'table','#header'=>$header_table,'#rows'=>$row,'#empty'=>t('No Record'),'#caption'=>Link::fromTextAndUrl($text,$add)->toString(),];

        $this->messenger()->addMessage('Record Found');

        return $data;
	}
}
?>