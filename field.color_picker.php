<?php defined('BASEPATH') or exit('No direct script access allowed');

class Field_Color_picker {

	public $field_type_slug             = 'color_picker';
	public $db_col_type                 = 'varchar';
	public $version                     = '1.0';
	public $author                      = array('name' => 'Ryan Thompson - AI Web Systems, Inc.');
	public $custom_parameters           = array('default_color', 'options');

	private $ci;

	public function __construct()
	{
		// Determine the field type path
		if (file_exists(SHARED_ADDONPATH.'field_types/'.$this->field_type_slug.'/field.'.$this->field_type_slug.'.php'))
		{
			$this->path = SHARED_ADDONPATH.'field_types/'.$this->field_type_slug.'/';
		}
		else
		{
			$this->path = ADDONPATH.'field_types/'.$this->field_type_slug.'/';
		}
	}

	public function event()
	{
		ci()->type->add_js('color_picker', 'jquery.miniColors.js');
		ci()->type->add_css('color_picker', 'jquery.miniColors.css');
		ci()->type->add_misc(
			'<script type="text/javascript">
			$(document).ready(function(){
			$(".color_picker").miniColors();
			});
			</script>

			<style type="text/css">
			.color-picker-swatch
			{
			height: 22px;
			width: 22px;
			display: inline-block;
			margin-bottom: -5px;
			background: url('.site_url($this->path.'/img/swatch.png').') no-repeat center center;
			}

			.color-picker-swatch.disabled
			{
			background-color: #aaa !important;
			}
			</style>
			'
		);
	}

	public function field_setup_event($stream, $method, $field)
	{
		ci()->type->add_js('color_picker', 'jquery.miniColors.js');
		ci()->type->add_css('color_picker', 'jquery.miniColors.css');
	}

	public function form_output($params, $entry_id, $field)
	{
		$options['name'] 	= $params['form_slug'];
		$options['id']		= $params['form_slug'];
		$options['value']	= empty($params['value']) ? $field->field_data['default_color'] : $params['value'];
		$options['class']   = 'color_picker';

		if(isset($field->field_data['options']['disable']))
		{
		if($field->field_data['options']['disable'] == 'yes')
		{
		$options['disabled'] = 'disabled';
		}
		}

		if(isset($field->field_data['options']['readonly']))
		{
		if($field->field_data['options']['readonly'] == 'yes')
		{
		$options['readonly'] = 'readonly';
		}
		}

		if(isset($field->field_data['options']['ishidden']))
		{
		if($field->field_data['options']['ishidden'] == 'yes')
		{
		$out = '<input type="hidden" value="'.$params['value'].'" name="'.$params['form_slug'].'" id="'.$params['form_slug'].'" />';
		}
		else
		{
		$out = form_input($options);
		}
		}
		else
		{
		$out = form_input($options);
		}

		return $out;
	}

	public function pre_output($input, $data)
	{
		return $input;
	}

	public function pre_output_plugin($input, $params, $row_slug)
	{
		ci()->type->add_misc(
		'
		<style type="text/css">
		.color-picker-swatch
		{
		height: 22px;
		width: 22px;
		display: inline-block;
		margin-bottom: -5px;
		background: url('.$this->path.'/img/swatch.png) no-repeat center center;
		}

		.color-picker-swatch.disabled
		{
		background-color: #aaa !important;
		}
		</style>
		'
		);

		return array(
			'code'      => $input,
			'swatch'    => '<span class="color-picker-swatch" style="background-color: '.$input.'"></span>',
			);
	}

	public function param_default_color($value = null)
	{
		$out = form_input('default_color', $value, 'class="color_picker"');
		$out .=
		'<script type="text/javascript">
		$(".color_picker").miniColors();
		</script>';
		return $out;
	}

	public function param_options($value = null)
	{
		$line_end = (defined('ADMIN_THEME')) ? '<br />' : null;
		$out = '';
		$out .= '<label class="checkbox">'.form_checkbox('options[disable]', 'yes', isset($value['disable'])).'&nbsp;'.lang('streams:color_picker.disabled').'</label>'.$line_end;
		$out .= '<label class="checkbox">'.form_checkbox('options[readonly]', 'yes', isset($value['readonly'])).'&nbsp;'.lang('streams:color_picker.readonly').'</label>'.$line_end;
		$out .= '<label class="checkbox">'.form_checkbox('options[ishidden]', 'yes', isset($value['ishidden'])).'&nbsp;'.lang('streams:color_picker.ishidden').'</label>';
		return $out;
	}

}