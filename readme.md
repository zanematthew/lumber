Description
==

Currently supports the following field types:

* Text
* Hidden
* URL
* Fieldset
* Section (div wrapper)
* Multiselect
* Select (US State)
* Select
* Textarea
* CSS Textarea
* Emails Textarea
* Checkbox
* Upload (admin only)
* Radio
* HTML
* Thickbox (admin only)


Usage
==
1. `git submodule add [repo url] lib/zm-form-fields`
1. At the top of your `php` file: `require_once dirname( __FILE__ ) . '/lib/zm-form-fields/zm-form-fields.php';`
1. Extend the class `Class My_Class Extends ZM_Form_Fields`
1. From within your class: `$this->do_text( $field=array(), $current_form=null, $value=null )`


The `$field=array()` param can contain the following:

```
// @todo use wp_parse_args() for these
$field = array(
    'for'           => $current_form . '_' . $field['id'],
    'title'         => empty( $field['title'] ) ? null : $field['title'],
    'name'          => empty( $field['name'] ) ? $name : $field['name'],
    'placeholder'   => empty( $field['placeholder'] ) ? null : $field['placeholder'],
    'row_class'     => ( empty( $field['extra_class'] ) ) ? 'zm-form-default-row' : 'zm-form-default-row ' . $field['extra_class'],
    'field_class'   => ( empty( $field['field_class'] ) ) ? '' : $field['field_class'],
    'row_id'        => 'zm_form_' . $current_form . '_' . $field['id'] . '_row',
    'input_id'      => $current_form . '_' . $field['id'],
    'req'           => empty( $field['req'] ) ? null : $field['req'],
    'desc'          => empty( $field['desc'] ) ? null : '<span class="description">' . $field['desc'] . '</span>',
    'echo'          => empty( $field['echo'] ) ? false : true,
    'current_value' => empty( $field['value'][ $field['id'] ] ) ? null : $field['value'][ $field['id'] ],
    'style'         => empty( $field['style'] ) ? null : $field['style'],
    'std'           => empty( $field['std'] ) ? null : $field['std'],
    'rows'          => empty( $field['rows'] ) ? 4 : $field['rows'],
    'cols'          => empty( $field['cols'] ) ? 8 : $field['cols'],
    );
```