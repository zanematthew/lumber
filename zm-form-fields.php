<?php

// This should be agnostic of meta vs. option
// post_type is actually our "key"
// This class can be broken out into 3 abstract classes?
//      do_{$form_field}
//      get_{$something}
//      save_{$something}
//      sanitize_{$something}

if ( ! class_exists( 'ZM_Form_Fields' ) ) :
Abstract Class ZM_Form_Fields {

    /**
     *
     * @since 1.0
     *
     * @param $field
     * @param $current_form
     * @param $value
     *
     * @return
     */
    public function do_text( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . $required_html . '</label>';
        $row .= '<input type="text" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ) . '" placeholder="' . $placeholder . '" size="25" ' . $required . ' class="large-text ' . $field_class .'" />';
        $row .= $desc;
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    public function do_email( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . $required_html . '</label>';
        $row .= '<input type="email" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ) . '" placeholder="' . $placeholder . '" size="25" ' . $required . ' class="large-text ' . $field_class .'" />';
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    /**
     *
     * @since 1.0
     *
     * @param $field
     * @param $current_form
     * @param $value
     *
     * @return
     */
    public function do_hidden( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . '</label>';
        $row .= '<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ) . '" placeholder="' . $placeholder . '" size="25" ' . $style . '/>';
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    /**
     *
     * @since 1.0
     *
     * @param $field
     * @param $current_form
     * @param $value
     *
     * @return
     */
    public function do_url( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . $required_html . '</label>';
        $row .= '<input type="url" id="' . $id . '" name="' . $name . '" value="' . esc_url( $value ) . '" placeholder="' . $placeholder . '" size="25" class="large-text ' . $field_class . '" ' . $required . '/>';
        $row .= $desc;
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    /**
     *
     * @since 1.0
     *
     * @param $field
     *
     * @return
     */
    public function do_open_fieldset( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        if ( empty( $field['id'] ) ){
            $id = sanitize_title( $field['id'] );
        } else {
            $id = $field['id'];
        }

        $html = '<div class="' . $row_class . ' zm-form-open-fieldset" id="zm_form_open_fieldset_' . $field['id'] . '">';
        $html .= '<fieldset id="zm_form_' . $current_form . '_' . $id . '_fieldset"><legend>' . $title . '</legend>';
        return $html;
    }


    /**
     *
     * @since 1.0
     *
     *
     * @return
     */
    public function do_end_fieldset(){
        return '</fieldset></div>';
    }


    /**
     *
     * @since 1.0
     *
     *
     * @return
     */
    public function do_open_section( $field=array() ){

        extract( $this->get_attributes( $field ) );

        $html = '<div class="' . $row_class . ' open-section" id="zm_form_' . $field['id'] . '_section">';
        return $html;
    }


    /**
     *
     * @since 1.0
     *
     *
     * @return
     */
    public function do_end_section(){
        return '</div>';
    }


    /**
     *
     * @since 1.0
     *
     * do_select( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_select( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        if ( empty( $field['options'] ) )
            return;

        if ( empty( $value ) && ! empty( $std ) )
            $value = $std;

        $options = '<option value="">-- Select a Value --</option>';
        foreach( $field['options'] as $k => $v ) {
            $options .= '<option value="' . $k . '" ' . selected( $k, $value, false ) . '>' . $v . '</option>';
        }

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '"><label for="' . $for . '">' . $title . $required_html . '</label> ';
        $html .= '<select name="' . $name . '" ' . $required . ' id="' . $id . '">';
        $html .= $options;
        $html .= '</select>';
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_multiselect( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_multiselect( $field=array(), $current_form=null ){
        extract( $this->get_attributes( $field, $current_form ) );

        if ( empty( $field['options'] ) ){
            $html = 'No options';
        } else {

            if ( is_string( $value ) ){
                $value = explode(',', $value);
            }

            $options = '<option value="">-- Select a Value --</option>';
            $selected = '';
            foreach( $field['options'] as $k => $v ) {

                if ( is_array( $value ) ){
                    $selected = ( ! empty( $value ) && in_array( $k, $value ) ) ? 'selected=selected' : null;
                } else {
                    $selected = selected( $k, $value, false );
                }
                $options .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
            }

            $html  = '<p class="' . $row_class . '" id="' . $row_id . '"><label for="' . $for . '">' . $title . '</label> ';
            $html .= '<select name="' . $name . '[]" multiple id="' . $id . '">';
            $html .= $options;
            $html .= '</select>';
            $html .= $desc;
            $html .= '</p>';
        }

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_us_state_select( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_us_state_select( $field=array(), $current_form=null ){
        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
            );

        extract( $this->get_attributes( $field, $current_form ) );

        $options = '<option value="">-- Select a Value --</option>';
        foreach( $states as $k => $v ) {
            $options .= '<option value="' . $k . '" ' . selected( $k, $value, false ) . '>' . $v . '</option>';
        }

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '"><label for="' . $for . '">' . $title . $required_html . '</label>';
        $html .= '<select name="' . $name . '" ' . $required . ' id="' . $id . '">';
        $html .= $options;
        $html .= '</select></p>';
        $html .= $desc;

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_textarea( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_textarea( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '"><label for="' . $for . '">' . $title . '</label>';
        $html .= '<textarea id="' . $id . '" name="' . $name . '" rows="'.$rows.'" cols="'.$cols.'" class="large-text '.$field_class.'" placeholder="' . $placeholder . '">' . esc_textarea( $value ) . '</textarea>';
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_css_textarea( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_css_textarea( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '"><label for="' . $for . '">' . $title . '</label>';
        $html .= '<textarea class="large-text" name="' . $name . '" placeholder="' . $placeholder . '" rows="10">' . wp_kses( $value, '' ) . '</textarea>';
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_textarea_emails( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_textarea_emails( $field=array(), $current_form=null ){
        return $this->do_textarea( $field, $current_form );
    }


    public function do_textarea_ip( $field=array(), $current_form=null ){
        return $this->do_textarea( $field, $current_form );
    }


    /**
     *
     * @since 1.0
     *
     * do_checkbox( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_checkbox( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $html = '<p class="'.$row_class.'"><input type="checkbox" name="'.$name.'" id="' . $id .'" value="1" ' . checked( 1, $value, false ) . '/>';
        $html .= '<label for="' . $for . '_checkbox">' . $title . '</label>';
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    // @todo support for std value
    public function do_checkboxes( $field=array(), $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        if ( empty( $field['options'] ) )
            return;

        $options = null;

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        foreach( $field['options'] as $k => $v ) {

            $key = sanitize_title( $k );
            $id = $id . '_' . $key;

            // Multi-dimensional array support or
            // Associative array support
            if ( is_array( $v ) ){
                $title = $v['title'];

                if ( ! empty( $field['value'] ) && array_key_exists( $v['id'], $field['value'] ) ){
                    $checked = "checked=check";
                } else {
                    $checked = null;
                }

            } else {
                $title = $v;
                if ( ! empty( $field['value'] ) && in_array( $key, $field['value'] ) ){
                    $checked = "checked=check";
                } else {
                    $checked = null;
                }
            }

            $options .= '<input type="checkbox" class="" name="'.$name.'[]" id="' . $id . '" value="' . $key . '" ' . $checked . ' />';
            $options .= '<label for="' . $id . '">' . $title . $required_html . '</label><br />';
        }

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $html .= $options;
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_upload( $field=array(), $current_form=null, $value=null
     *
     * @return
     */
    public function do_upload( $field=array(), $current_form=null ){

        wp_enqueue_media();
        // wp_enqueue_script( 'custom-header' );

        wp_enqueue_script( 'zm-form-fields-upload',
            $this->get_base_dir_url() . 'assets/javascripts/scripts.js', array('jquery') );

        extract( $this->get_attributes( $field, $current_form ) );

        $value = empty( $current_value ) ? intval( $value ) : intval( $current_value );

        if ( $value ){
            $style = null;
            $image = '<img src="' . wp_get_attachment_thumb_url( $value ) . '" style="border: 1px solid #ddd;" />';
        } else {
            $style = 'style="display:none;"';
            $image = null;
        }

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . '</label>';


        $row .= '<span class="zm-form-fields-upload-container">';
        $row .= '<a href="#" class="button zm-form-fields-media-upload-handle" style="margin-bottom: 10px;">' . __('Upload', 'zm_alr_pro') . '</a><br />';
        $row .= '<span class="zm-form-fields-upload-image-container" ' . $style . '>';
        $row .= $image;
        $row .= '</span>';
        $row .= '<br /><a href="#" class="zm-form-fields-upload-remove-handle" ' . $style . '>' . __('Remove', 'zm_alr_pro_settings') . '</a>';
        $row .= '<input type="hidden" class="zm-form-fields-upload-attachment-id" id="'.$id.'" name="' . $name . '" value="' . $value . '"/>';
        $row .= '</span>';
        $row .= '<br />' . $desc;

        $row .= '</p>';



        if ( $echo )
            echo $row;
        else
            return $row;
    }


    /**
     *
     * @since 1.0
     *
     *
     * @return
     */
    public function do_radio( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        if ( empty( $field['options'] ) )
            return;

        $options = null;

        $required = ( $req == true ) ? ' required ' : null;
        $required_html = ( $req == true ) ? '<sup class="req">&#42;</sup>' : null;

        foreach( $field['options'] as $k => $v ) {

            $key = sanitize_title( $k );
            $id = $id . '_' . $key;

            $options .= '<input type="radio" class="" name="'.$name.'" id="' . $id . '" value="' . $key . '" ' . checked( $key, $value, false ) . ' /><label for="' . $id . '">' . $v . $required_html . '</label><br />';
        }

        $html  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $html .= $options;
        $html .= $desc;
        $html .= '</p>';

        if ( $echo )
            echo $html;
        else
            return $html;
    }


    /**
     *
     * @since 1.0
     *
     * do_html( $field=null, $current_form=null
     *
     * @return
     */
    public function do_html( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . '</label>';
        $row .= $std;
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    /**
     *
     * @since 1.0
     *
     * do_thickbox_url( $field=null, $current_form=null, $value=null
     * @uses add_thickbox() http://codex.wordpress.org/ThickBox
     * @uses add_query_arg() http://codex.wordpress.org/Function_Reference/add_query_arg
     * @uses esc_url() http://codex.wordpress.org/Function_Reference/esc_url
     * @return
     */
    public function do_thickbox_url( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        add_thickbox();

        $row  = '<p class="' . $row_class . '" id="' . $row_id . '">';
        $row .= '<label for="' . $for . '">' . $title . '</label>';
        $row .= '<a href="' . add_query_arg( array(
            'TB_iframe' => 'true',
            'width' => '600',
            'height' => '550'
            ), esc_url( $std ) ) . '" class="thickbox">' . $placeholder . '</a>';
        $row .= '</p>';

        if ( $echo )
            echo $row;
        else
            return $row;
    }


    // @todo finish this one
    public function do_touchtime( $field=null, $current_form=null ){

        extract( $this->get_attributes( $field, $current_form ) );

        global $wp_locale;

        if ( empty( $value ) ){
            $time_adj = current_time('timestamp');

            $jj = gmdate( 'd', $time_adj );
            $mm = gmdate( 'm', $time_adj );
            $aa = gmdate( 'Y', $time_adj );
            $hh = gmdate( 'H', $time_adj );
            $mn = gmdate( 'i', $time_adj );
        } else {
            $jj = $value['day'];
            $mm = $value['month'];
            $aa = $value['year'];
            $hh = $value['hour'];
            $mn = $value['minute'];
        }

        $month = '<select name="' . $name . '[month]">';

        for ( $i = 1; $i < 13; $i = $i +1 ) {

            $monthnum = zeroise( $i, 2 );
            $month .= '<option value="' . $monthnum . '" ' . selected( $monthnum, $mm, false ) . '>';

            /* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
            $month .= sprintf( '%1$s-%2$s',
                $monthnum,
                $wp_locale->get_month_abbrev( $wp_locale->get_month( $i )
            ) );

            $month .= "</option>";
        }
        $month .= '</select>';

        $day = '<input type="text" name="' . $name . '[day]" value="' . $jj . '" size="2" maxlength="2" autocomplete="off" />';
        $year = '<input type="text" name="' . $name . '[year]" value="' . $aa . '" size="4" maxlength="4" autocomplete="off" />';
        $hour = '<input type="text" name="' . $name . '[hour]" value="' . $hh . '" size="2" maxlength="2" autocomplete="off" />';
        $minute = '<input type="text" name="' . $name . '[minute]" value="' . $mn . '" size="2" maxlength="2" autocomplete="off" />';


        // Final HTML
        $html  = '<p class="' . $row_class . ' zm-form-touch-time" id="' . $row_id . '">';
        /* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
        $html .= sprintf( '%1$s %2$s, %3$s @ %4$s : %5$s', $month, $day, $year, $hour, $minute );
        $html .= '<span class="desc">'  . $desc . '</span>';
        $html .= '</p>';

        if ( $echo ){
            echo $html;
        } else {
            return $html;
        }
    }


    /**
     * Builds an array of all possible HTML attributes for a given form field.
     *
     * @since   1.0
     *
     * @param   $field (array) The field containing an array of values
     * @param   $current_form (string) Given post type
     *
     * @todo    use wp_parse_args()
     *
     * @return  HTML attributes for a given form field.
     */
    public function get_attributes( $field=null, $current_form=null ){

        $current_form = empty( $field['namespace'] ) ? $current_form : $field['namespace'];
        $field_id = isset( $field['id'] ) ? $field['id'] : null;

        // Other people can override the name, by passing it in with the field
        $name = '_' . $current_form . '_form[meta]['.$field_id.']';


        if ( isset( $field['value'] ) ){
            $value = $field['value'];
        } else {
            $value = empty( $field['std'] ) ? null : $field['std'];
        }

        $std = isset( $field['std'] ) ? $field['std'] : false;

        $attr = array(
            'for' => $current_form . '_' . $field_id,
            'title' => empty( $field['title'] ) ? null : $field['title'],
            'name' => empty( $field['name'] ) ? $name : $field['name'],
            'placeholder' => empty( $field['placeholder'] ) ? null : $field['placeholder'],
            'row_class' => ( empty( $field['extra_class'] ) ) ? 'zm-form-default-row' : 'zm-form-default-row ' . $field['extra_class'],
            'field_class' => ( empty( $field['field_class'] ) ) ? '' : $field['field_class'],
            'row_id' => 'zm_form_' . $current_form . '_' . $field_id . '_row',
            'id' => $current_form . '_' . $field_id,
            'req' => empty( $field['req'] ) ? null : $field['req'],
            'desc' => empty( $field['desc'] ) ? null : '<span class="description">' . $field['desc'] . '</span>',
            'echo' => empty( $field['echo'] ) ? false : true,
            'value' => $value,
            'style' => empty( $field['style'] ) ? false : $field['style'],
            'std' => $std,
            'rows' => empty( $field['rows'] ) ? 4 : $field['rows'],
            'cols' => empty( $field['cols'] ) ? 8 : $field['cols'],
            );

        return $attr;
    }


    /**
     * Gets an array of all the available form fields. Containing
     * id, type, title, etc.
     *
     * @since 1.0
     *
     * @return An array of all available fields.
     */
    public function get_fields(){
        // Our default fields
        $default_fields = array(
            array(
                'id' => 'email',
                'type' => 'text',
                'title' => 'Email Address',
                'value' => null
                ),
            array(
                'id' => 'cell',
                'type' => 'text',
                'title' => 'Cell Phone',
                'value' => null
                ),
            array(
                'id' => 'info_about',
                'type' => 'text',
                'title' => 'Send info about',
                'value' => null
                )
        );

        $forms = $this->get_forms();

        foreach( $forms as $form ){
            $fields[ $form['post_type'] ] = apply_filters( 'zm_form_fields_' . $form['post_type'], $default_fields );
        }
        return apply_filters( 'zm_form_fields_additional_fields', $fields );
    }


    /**
     * Sets up an empty array to retrieve all available forms
     * via a filter.
     *
     * @since 1.0
     *
     * @return  An array of all available forms
     */
    public function get_forms(){
        return apply_filters( 'zm_form_add_new', array() );
    }


    /**
     * Get our meta values for a given post_type
     *
     * @since   1.0
     *
     * @param   $post_id The post id.
     * @param   $key The key to retrieve the value for.
     *
     * @return
     */
    public function get_values( $post_id=null, $key=null ){
        $post_meta = get_post_meta( $post_id, '_zm_form_meta', true );
        if ( empty( $key ) ){
            $meta = apply_filters( 'zm_forms_meta_values', $post_meta, $post_id );
        } else {
            $meta = empty( $post_meta[ $key ] ) ? null : $post_meta[ $key ];
        }

        return $meta;
    }


    /**
     * Builds the markup needed for each form field based on the field type.
     *
     * @since   1.0
     *
     * @param   $post_id The post id
     * @param   $current_form The form, i.e., post type
     *
     * @todo    Move this switch to its on method of available field types.
     *
     * @return  The HTML form fields for the given form.
     */
    public function get_meta_fields_html( $post_id=null, $current_form=null ){

        $meta = $this->get_values( $post_id );

        $my_fields = $this->get_fields();
        $html = null;

        foreach( $my_fields as $form => $fields ){
            if ( $current_form == $form ){
                if ( empty( $fields ) ){
                    $html .= 'Using defaults';
                } else {
                    foreach( $fields as $field ) :

                        $field['value'] = empty( $meta[ $field['id'] ] ) ? null :  $meta[ $field['id'] ];

                        switch( $field['type'] ) {

                            case 'select' :
                                $html .= $this->do_select( $field, $current_form );
                                break;

                            case 'multiselect' :
                                $html .= $this->do_multiselect( $field, $current_form );
                                break;

                            case 'us_state' :
                                $html .= $this->do_us_state_select( $field, $current_form );
                                break;

                            case 'textarea' :
                            case 'textarea_email_template' :
                                $html .= $this->do_textarea( $field, $current_form );
                                break;

                            case 'textarea_emails' :
                                $html .= $this->do_textarea_emails( $field, $current_form );
                                break;

                            case 'open_fieldset' :
                                $html .= $this->do_open_fieldset( $field, $current_form );
                                break;

                            case 'end_fieldset' :
                                $html .= $this->do_end_fieldset();
                                break;

                            case 'open_section' :
                                $html .= $this->do_open_section( $field, $current_form );
                                break;

                            case 'end_section' :
                                $html .= $this->do_end_section();
                                break;

                            case 'checkbox' :
                                $html .= $this->do_checkbox( $field, $current_form );
                                break;

                            case 'checkboxes' :
                                $html .= $this->do_checkboxes( $field, $current_form );
                                break;

                            case 'radio' :
                                $html .= $this->do_radio( $field, $current_form );
                                break;

                            case 'hidden' :
                                $html .= $this->do_hidden( $field, $current_form );
                                break;

                            case 'upload' :
                                $html .= $this->do_upload( $field, $current_form );
                                break;

                            case 'html' :
                                $html .= $this->do_html( $field, $current_form );
                                break;

                            case 'thickbox_url' :
                                $html .= $this->do_thickbox_url( $field, $current_form );
                                break;

                            case 'email' :
                                $html .= $this->do_email( $field, $current_form );
                                break;

                            case 'touchtime' :
                                $html .= $this->do_touchtime( $field, $current_form );
                                break;

                            default:
                                $html .= $this->do_text( $field, $current_form );
                                break;
                        }

                    endforeach;
                }
            }
        }
        return $html;
    }



    /**
     * Merge the meta fields with that of the current form,
     * this allows us to get add additional data to the fields.
     * Essentially we are going from this:
     * array( 'first_name' ); to
     * array( 'first_name' => array( 'type' => 'text', 'id' => etc. ) )
     *
     * @since 1.0
     *
     * @param   $meta The meta fields we are merging with
     * @param   $current_form The current form, i.e., post type
     *
     * @return  An array of formated meta fields, array( 'first_name' => array( 'type' => 'text', 'id' => etc. ) )
     */
    public function get_formatted_meta( $meta=null, $current_form=null ){
        $fields = $this->get_fields();
        $current_form_fields = $fields[ $current_form ];
        foreach( $current_form_fields as $field ){
            foreach( $meta as $k => $v ){
                if ( $field['id'] == $k ){
                    $formatted[ $k ] = $field;
                    $formatted[ $k ]['value'] = $v;
                }
            }
        }
        return $formatted;
    }


    /**
     * Saves ALL post meta to a single serialized value "_zm_form_meta"
     *
     * @since 1.0
     *
     * @param    $post_id The post id
     * @param    $meta The values being saved
     * @param    $current_form The post type of the form or a unique key. This
     *           is only used for the available hooks.
     *
     * @return
     */
    public function save_meta( $post_id=null, $meta=null, $current_form=null ){
        $formatted_meta = $this->get_formatted_meta( $meta, $current_form );
        $multi_value = null;
        $sanitized = null;

        foreach( $formatted_meta as $k => $v ){

            if ( is_array( $v['value'] ) ){
                // for multiselect
                // we sanitize them individually then implode them on a , comma
                foreach( $v['value'] as $vv ){
                    $multi_value[] = $this->sanitize( $type, $vv );
                }
                $sanitized[ $k ] = implode( ',', $multi_value );
            } else {
                $value = trim( $v['value'] );
                if ( ! empty( $value ) ){
                    $type = empty( $v['type'] ) ? 'default' : $v['type'];

                    // sanitize by type
                    $value = $this->sanitize( $type, $v, $current_form );

                    // Build our array of values
                    $sanitized[ $k ] = $value;

                }
            }
        }

        $sanitized = apply_filters( 'zm_form_' . $current_form . '_sanitized_meta', $sanitized );
        $post_id = apply_filters( 'zm_form_' . $current_form . '_before_save_meta', $post_id, $meta );
        if ( $post_id !== false ){
            // http://codex.wordpress.org/Function_Reference/sanitize_meta
            update_post_meta( $post_id, '_zm_form_meta', $sanitized );
        }

        return $post_id;
    }


    /**
     * Sanitize form a form field value.
     *
     * @since 1.0
     *
     * @param   $type The field type being sanitized, i.e., text, radio, checkbox, etc.
     *          see get_meta_fields_html() for full list.
     * @param   $value The value to sanitize
     * @todo    Merge this with the ZM_Settings sanitize methods?
     *
     * @return  Sanitized value
     */
    public function sanitize( $type=null, $field=null, $current_form=null ){

        switch ( $type ) {
            case 'textarea':
                $value = esc_textarea( $field['value'] );
                break;
            case 'textarea_emails':
                $value = $this->sanitize_textarea_emails( $field['value'] );
                break;

            case 'checkbox' :
                $value = intval( $field['value'] );
                break;

            case 'email' :
                $value = sanitize_email( $field['value'] );
                break;

            case 'float' :
                $value = floatval( $field['value'] );
                break;

            case 'multiselect' :
                print_r( $value );
                break;

            // case 'number' :
                // $value = $this->sanitize_number( $field );
                // break;
            case 'text' :
            case 'us_state' :
            case 'select' :
            case 'phone' :
            default:
                $value = esc_attr( $field['value'] );
                break;
        }

        // sanitize by id
        // $value = apply_filters( 'zm_form_sanitize_' . $current_form . '_' . $field['id'], $value );

        return $value;
    }


    /**
     * This takes an array of "stuff" determines in it what are valid
     * emails and returns all the emails separated by a new line. For
     * use in a textarae.
     *
     * @since 1.1
     * @param $emails An array of emails to validate
     * @return Validated emails separated by a new line (for use in a textarea)
     */
    public function sanitize_validate_emails( $emails=null ){
        $valid_emails = array();
        foreach( $emails as $email ){
            $sanitized = sanitize_email( $email );
            if ( $sanitized ){
                $valid_emails[] = $sanitized;
            }
        }

        $valid_emails = implode(PHP_EOL, $valid_emails);
        return $valid_emails;
    }


    /**
     * Takes everything that is in our textarea and creates an array
     * based on new lines and blank spaces.
     *
     * @since 1.1
     * @param $input (string) The input being saved
     * @return Sanitized email addresses that are separated by a blank line.
     */
    public function sanitize_textarea_emails( $input ){

        // Explode on new lines, remove blank spaces, convert to array, then re-index
        $input = array_values( array_filter( explode( PHP_EOL, trim( $input ) ), 'trim' ) );
        $array_emails = array();

        // build an array of emails
        foreach( $input as $value ){

            if ( $this->sanitize_forward_comments( $value ) ){
                $array_emails[] = $value;
            } else {

                // check for ones that are NOT on a new line, but have a space
                $pos = strpos( $value, ' ' );
                if ( $pos !== false ){
                    $more_values = explode( ' ', $value );
                    foreach( $more_values as $more_value ){
                        if ( sanitize_email( $more_value ) )
                            $array_emails[] = $more_value;
                    }
                } else {
                    if ( sanitize_email( $value ) )
                        $array_emails[] = $value;
                }
            }
        }

        $string_emails = implode(PHP_EOL, $array_emails );

        return $string_emails;
    }


    public function sanitize_number( $field=null, $min=null, $max=null ){

        $value = trim( $field['value'] );

        if ( empty( $value ) ){
            $field['error'] = 'empty';
        } elseif ( ! is_numeric( $value ) ){
            $field['error'] = 'not a number';
        } elseif ( $value > $max ){
            $field['error'] = 'too big';
        } elseif ( $value < $min ){
            $field['error'] = 'too small';
        } else {

        }

        return $field;
    }


    public function sanitize_default( $value=null ){
        return esc_attr( $value );
    }


    public function sanitize_multiselect( $value=null ){
        $tmp = array();
        foreach( $value as $v ){
            $tmp[] = $this->sanitize_default( $v );
        }
        return $tmp;
    }


    /**
     * Validate the comment from a string. A valid comment starts with "//"
     *
     * @param (string)$value The value we want to sanitize
     * @return The comment if valid, otherwise false
     */
    public function sanitize_forward_comments( $value=null ){

        $comment = false;
        $pos = strpos( trim( $value ), '//' );

        if ( $pos !== false ){
            $comment = $value;
        }

        return $comment;
    }


    public function sanitize_textarea_ip( $value=null ){

        // textarea to array
        $textarea_values = array_values( array_filter( explode( PHP_EOL, $value ), 'trim' ) );
        $ips = array();

        foreach( $textarea_values as $textarea_value ){

            // Allow forward comments
            $comment = $this->sanitize_forward_comments( $textarea_value );
            if ( $comment ){
                $ips[] = $comment;
            }

            // Sanitize our IP address
            $valid_ip = $this->sanitize_ip( $textarea_value );
            if ( $valid_ip ){
                $ips[] = $valid_ip;
            }
        }


        $final_ip = null;
        foreach( $ips as $ip ){
            $final_ip .= $ip . "\n";
        }

        $escaped = str_replace("\n", '--FAKE_LINE--', $final_ip );
        $my_data = sanitize_text_field( $escaped );
        $final_ip = str_replace( '--FAKE_LINE--', "\n", $my_data);

        return $final_ip;
    }


    /**
     *
     * @since 1.0.0
     * @param
     * @return
     *//**
     * Validate an IP address
     *
     * @param $ip An IP address to validate
     * @return Valid IP
     */
    public function sanitize_ip( $ip=null ){
        $ip = trim( $ip );
        return ( filter_var( $ip, FILTER_VALIDATE_IP ) ) ? $ip : false;
    }


    public function sanitize_touchtime( $value=null ){
        return $value;
    }


    /**
     * Prints the meta fields, i.e., form fields
     *
     * @since   1.0
     * @uses    get_meta_fields_html()
     * @param   $post (object) The global post object
     * @todo    Move in-line CSS to style sheet?
     *
     * @return
     */
    public function meta_fields( $post ){ ?>
        <?php wp_nonce_field( 'zm_form_meta_box', 'zm_form_meta_box_nonce' ); ?>
        <style type="text/css">
            /*label { display: inline-block; width: 200px; }*/
        </style>
        <?php echo $this->get_meta_fields_html( $post->ID, $post->post_type ); ?>
    <?php }


    public function get_base_dir_url(){
        return apply_filters( 'zm_form_fields_dir_url', plugin_dir_url( __FILE__ ) );
    }
}
endif;