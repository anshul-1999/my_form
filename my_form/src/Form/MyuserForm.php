<?php
namespace Drupal\my_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use\Drupal\Core\Url;
use\Drupal\Core\messenger;
use\Drupal\Core\Link;

class MyuserForm extends FormBase
{
	public function getFormid()
	{
		return 'myuser_form';
	}
public function buildform(array $form, FormStateInterface $form_state)
{
	$conn = Database::getConnection();

	$record = [];
	if(isset($_GET['id']))
	{
		$query = $conn->select('my_form','m')->condition('id',$_GET['id'])->fields('m');
		$record = $query->execute()->fetchAssoc();
	}

$form['name']=['#type'=>'textfield','#title'=>t('Name'),'#required'=>TRUE,'#default_value'=>(isset($record['name'])&&$_GET['id'])? $record['name']:'',];
$form['age']=['#type'=>'textfield','#title'=>t('Age'),'#required'=>TRUE,'#default_value'=>(isset($record['age'])&&$_GET['id'])? $record['age']:'',];
$form['e-mail']=['#type'=>'textfield','#title'=>t('Email'),'#required'=>TRUE,'#default_value'=>(isset($record['email'])&&$_GET['id'])? $record['e-mail']:'',];
$form['gender']=['#type'=>'textfield','#title'=>t('Gender'),'#required'=>TRUE,'#default_value'=>(isset($record['gender'])&&$_GET['id'])? $record['gender']:'',];

$form['action']=['#type'=>'action',];

$form['action']['submit'] = ['type' => 'submit','#value'=>t('Save'),];

$form['action']['reset']=['#type'=>'button','#value'=>t('Reset'),'#attributes'=>['onclick'=>'this.form.reset(); return false;',],];

$link = Url::formUserInput('/my_form');

$form['action']['cancel']=['#markup'=>Link::fromTextAndurl(t('Back to page'),$link,['attributes'=>['class'=>'button']])->toString(),];
return $form;	
}	

public function validateForm(array &$form, FormStateInterface $form_state)
{
	$name = $form_state->getValue('name');

	if(preg_match('/[^A-za-z]/', $name))
	{
		$form_state->setErrorByName('name',$this->t('Invalid Name'));
	}

	$age = $form_state->getValue('age');

	if(!preg_match('/[^A-za-z]/', $age))
	{
		$form_state->setErrorByName('age',$this->t('Invalid Age'));
	}

parent::validateForm($form, $form_state);

}

public function submitForm(array &$form, FormStateInterface $form_state)
{
	$field = $form_state->getValues();

	$name = $field['name'];
	$age = $field['age'];
	$email = $field['email'];
	$gender = $field['gender'];

	if(isset($_GET['id']))
	{
		$field = ['name'=> $name,'age'=> $age,'email'=> $email,'gender'=> $gender,];
	    
	    $query =\Drupal::database();
	    $query->update('my_form')->fields($field)->condition('id',$_GET['id'])->execute();
	    $this->messenger()->addMessage('Succesfully Updated');

	}
     else
	{
		$field = ['name'=> $name,'age'=> $age,'email'=> $email,'gender'=> $gender,];
	    
	    $query =\Drupal::database();
	    $query->insert('my_form')->fields($field)->execute();
	    $this->messenger()->addMessage('Succesfully Saved');

	    'form_state'->setRedirect('my_form.myform_controller_table');

	}
}
}







?>