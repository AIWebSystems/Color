<?php namespace Pyro\FieldType;

use Pyro\Module\Streams\FieldType\FieldTypeAbstract;

class ColorPicker extends FieldTypeAbstract
{
    /**
     * Field type slug
     *
     * @var string
     */
    public $field_type_slug = 'color';

    /**
     * DB col type
     *
     * @var string
     */
    public $db_col_type = 'string';

    /**
     * Version
     *
     * @var string
     */
    public $version = '1.0';

    /**
     * Author
     *
     * @var array
     */
    public $author = array(
        'name' => 'Ryan Thompson - AI Web Systems, Inc.',
        );

    /**
     * Event
     */
    public function event()
    {
        $this->js('colorpicker.js');
        $this->css('colorpicker.css');
    }

    /**
     * Form input
     * @return string
     */
    public function formInput()
    {
        $out = form_input($this->form_slug, $this->value);

        $out .= '
            <script type="text/javascript">
                $(document).ready(function(){
                    $("input[name=\"'.$this->form_slug.'\"]").ColorPicker({
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
}
