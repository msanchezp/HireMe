<?php

namespace HireMe\Components;

use Illuminate\Html\FormBuilder as Form;
use Illuminate\View\Environment as View;
use Illuminate\Session\Store as Session;

use Illuminate\Translation\Translator as Lang;
use Illuminate\Filesystem\Filesystem as File;

class FieldBuilder{

	protected $form;
    protected $view;
    protected $session;
    protected $lang;
    protected $file;

	protected $defaultClasses = [
		'default' => 'form-control',
		'checkbox' => ''
	];

	public function __construct(Form $form, View $view, Session $session, Lang $lang, File $file)
    {
        $this->form = $form;
        $this->view = $view;
        $this->session = $session;
        $this->lang = $lang;
        $this->file = $file;
    }

	public function getDefaultClass($type){
		if(isset($this->defaultClasses[$type])){
			return $this->defaultClasses[$type];
		}

		return $this->defaultClasses['default'];
	}

	public function buildCssClasses($type, &$attributes){
		$defaultClasses = $this->getDefaultClass($type);

		if(isset($attributes['class'])){
			$attributes['class'] .= ' '.$defaultClasses;
		}else{
			$attributes['class'] = $defaultClasses;
		}
	}

	public function buildLabel($name){
		if($this->lang->has('validation.attributes.'.$name)){
			$label = $this->lang->get('validation.attributes.'.$name);
		}else{
			$label = str_replace('_', ' ', $name);
		}

		return ucfirst($label);
	}

	public function buildControl($type, $name, $value=null, $attributes=array(), $options=array()){
		switch($type){
			case 'select':
				$options = array(''=>'Seleccione') + $options;
				return $this->form->select($name,$options,$value,$attributes);
			case 'password':
				return $this->form->password($name, $attributes);
			case 'checkbox':
				return $this->form->checkbox($name);
			case 'textarea':
				return $this->form->textarea($name, $value, $attributes);
			default:
				return $this->form->input($type,$name,$value,$attributes);
		}
	}

	public function buildError($name){
		$error = null;

		if($this->session->has('errors')){
			$errors = $this->session->get('errors');

			if($errors->has($name)){
				$error = $errors->first($name);
			}			
		}

		return $error;
	}

	public function buildTemplate($type){
		if($this->file->exists('app/views/fields/'.$type.'.blade.php')){
			return 'fields/'.$type;
		}

		return 'fields/default';
	}

	public function input($type, $name, $value=null,$attributes=array(),$options = array()){
		$this->buildCssClasses($type,$attributes);
		$label = $this->buildLabel($name);
		$control = $this->buildControl($type, $name, $value, $attributes, $options);
		$error = $this->buildError($name);
		$template = $this->buildTemplate($type);

		return $this->view->make($template, compact('name','label','control','error'));
	}

	public function password($name, $attributes = array()){
		return $this->input('password',$name, null, $attributes);
	}

	public function select($name, $options, $value=null, $attributes= array()){
		return $this->input('select',$name,$value,$attributes,$options);
	}

	public function __call($method, $params){
		array_unshift($params, $method);
		
		return call_user_func_array([$this, 'input'], $params);
	}
}