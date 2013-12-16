<?php namespace Pyro\FieldType;

use Pyro\Module\Streams_core\AbstractFieldType;

class ColorPicker extends AbstractFieldType
{
	public $field_type_slug = 'color_picker';
	
	public $db_col_type = 'string';
	
	public $version = '1.0';

	public $custom_parameters = array(
		'default_color',
		'options'
		);

	public $author = array(
		'name' => 'Ryan Thompson - AI Web Systems, Inc.'
		);

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
		$this->js('colorpicker.js');
		$this->css('colorpicker.css');
	}

	public function formInput()
	{
		$options['name'] = $this->form_slug;
		$options['id'] = $this->form_slug;
		$options['value'] = $this->value;
		$options['class'] = 'form-control';

		$out = form_input($options);

		$out .= '
			<script type="text/javascript">
				$(document).ready(function(){
					$("#'.$this->form_slug.'").ColorPicker({
						onChange: function (hsb, hex, rgb) {
							$("input[name=\''.$this->form_slug.'\']").val("#" + hex);
						},
						onBeforeShow: function () {
							$(this).ColorPickerSetColor(this.value);
						}
					});
				});
			</script>';

		return $out;
	}

	public function stringOutput()
	{
		return $this->input;
	}

	public function preOutputPlugin()
	{
		return array(
			'code'      => $this->input,
			'swatch'    => '<span class="color-picker-swatch" style="background-color: '.$this->input.'"></span>',
			);
	}

	public function paramDefaultColor($value = null)
	{
		$out = form_input('default_color', $value, 'class="color_picker"');
		$out .=
		'<script type="text/javascript">
		$(".color_picker").miniColors();
		</script>';
		return $out;
	}

	public function paramOptions($value = null)
	{
		$line_end = (defined('ADMIN_THEME')) ? '<br />' : null;
		$out = '';
		$out .= '<label class="checkbox">'.form_checkbox('options[disable]', 'yes', isset($value['disable'])).'&nbsp;'.lang('streams:color_picker.disabled').'</label>'.$line_end;
		$out .= '<label class="checkbox">'.form_checkbox('options[readonly]', 'yes', isset($value['readonly'])).'&nbsp;'.lang('streams:color_picker.readonly').'</label>'.$line_end;
		$out .= '<label class="checkbox">'.form_checkbox('options[ishidden]', 'yes', isset($value['ishidden'])).'&nbsp;'.lang('streams:color_picker.ishidden').'</label>';
		return $out;
	}
}
