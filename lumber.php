<?php

/**
 * Lumber
 *
 * License:     GPLv2 or later (of-course)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Author:      Zane M. Kolnik
 */

/**
 * This should be agnostic of meta vs. option
 * post_type is actually our "key"
 * This class can be broken out into 3 abstract classes?
 *      do_{$form_field}
 *      get_{$something}
 *      save_{$something}
 *      sanitize_{$something}
 */
if ( ! class_exists( 'Lumber' ) ) :
Abstract Class Lumber {


    /**
     * Creates a generic HTML input field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     * @param $type             (string)    The input type, must be a relevant HTML input type
     * @param $force_return     (bool)      Forces the field to be returned, even if the attr value
     *                                      is set to echo it.
     * @param $disabled         (string)    If the field should use the HTML disabled attribute
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doText( $attr=null, $current_form=null, $type=null, $force_return=null, $disabled=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        // Some defaults, maybe clean this up later
        $required = ( $attr['req'] ) ? ' required ' : null;
        $type     = empty( $type ) ? 'text' : $type;
        $disabled = empty( $disabled ) ? null : 'disabled';
        $checkbox = ( $type == 'checkbox' ) ? 'value="1" ' . checked( 1, $attr['value'], false ) : null;
        $size     = ( $type == 'checkbox' ) ? null : 'size="25"';

        if ( $type == 'number' ){

            $min = empty( $attr['min'] ) ? 'min="0"' : 'min="' . $attr['min'] . '"';
            $max = empty( $attr['max'] ) ? 'max="5"' : 'max="' . $attr['max'] . '"';

        } else {

            $min = null;
            $max = null;

        }

        if ( empty( $attr['extra'] ) ){
            $attr['extra'] = null;
        }

        // @todo clean this up!
        $field = '<input
        type="' . $type . '" ' . $disabled . ' ' . $checkbox . ' ' . $min . ' ' . $max . '
        id="' . $attr['id'] . '"
        name="' . $attr['name'] . '"
        value="' . esc_attr( $attr['value'] ) . '"
        placeholder="' . $attr['placeholder'] . '" ' . $size . $required . '
        class="' . $attr['field_class'] .'"
        autocomplete="' . $attr['autocomplete'] . '"
        ' . $attr['extra'] . '
        />';

        if ( $attr['echo'] && ! $force_return )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates an input field of a given HTML type, but with additional HTML markup
     *
     * @since 1.0.0
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     * @param $type             (string)    The input type, must be a relevant HTML input type
     * @param $disabled         (string)    If the field should use the HTML disabled attribute
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doFancyText( $attr=null, $current_form=null, $type=null, $disabled=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        $required = ( $attr['req'] == true ) ? ' required ' : null;
        $required_html = ( $attr['req'] == true ) ? '<sup class="req">&#42;</sup>' : null;
        $type = empty( $type ) ? 'text' : $type;
        $disabled = empty( $disabled ) ? false : true;

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= '<label for="' . $attr['for'] . '">' . $attr['title'] . $required_html . '</label>';
        $field .= $this->doText( $attr, $current_form, $type, true, $disabled );
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates an HTML input field
     *
     * @since 1.0.0
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doEmail( $attr=null, $current_form=null ){

        $field = $this->doFancyText( $attr, $current_form, 'email' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates an HTML5 number input field
     *
     * @since 1.0.0
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doNumber( $attr=null, $current_form=null ){

        $field = $this->doFancyText( $attr, $current_form, 'number' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a hidden HTML input field
     *
     * @since 1.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doHidden( $attr=null, $current_form=null ){

        $field = $this->doFancyText( $attr, $current_form, 'hidden' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a URL HTML input field
     *
     * @since 1.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doUrl( $attr=null, $current_form=null ){

        $field = $this->doFancyText( $attr, $current_form, 'url' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a Button HTML input field
     *
     * @since 1.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doButton( $attr=null, $current_form=null ){

        $attr['field_class'] = 'button';
        $field = $this->doFancyText( $attr, $current_form, 'button' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a hidden HTML input field
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doTextDisabled( $attr=null, $current_form=null ){

        $field = $this->doFancyText( $attr, $current_form, 'text', 'disabled' );

        $attr = $this->getAttributes( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a checkbox HTML input field
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doCheckbox( $attr=array(), $current_form=null ){

        $attr['field_class'] = 'checkbox';
        $attr = $this->getAttributes( $attr, $current_form );
        $field = $this->doFancyText( $attr, $current_form, 'checkbox' );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a group of radio buttons
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doRadio( $attr=null, $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        if ( empty( $attr['options'] ) )
            return;

        $options = null;

        $required = ( $attr['req'] == true ) ? ' required ' : null;
        $required_html = ( $attr['req'] == true ) ? '<sup class="req">&#42;</sup>' : null;

        foreach( $attr['options'] as $k => $v ) {

            $key = sanitize_title( $k );
            $id = $attr['id'] . '_' . $key;
            $container_class = $id . '_container';

            $options .= '<span class="'.$container_class.'">';
            $options .= '<input type="radio" class="" name="'.$attr['name'].'" id="' . $id . '" value="' . $key . '" ' . checked( $key, $attr['value'], false ) . ' />';
            $options .= '<label for="' . $id . '"><span>' . $v . $required_html . '</span></label><br />';
            $options .= '</span>';
        }

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= $options;
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates the open HTML tags for a fieldset
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (string)     The HTML attribute
     */
    public function doOpenFieldset( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        $field = '<div class="' . $attr['row_class'] . ' lumber-form-open-fieldset" id="lumber_form_open_fieldset_' . $field_id . '">';
        $field .= '<fieldset id="lumber_form_' . $current_form . '_' . $field_id . '_fieldset"><legend>' . $title . '</legend>';

        return $field;

    }


    /**
     * Creates a closing HTML fieldset
     *
     * @since 1.0.0
     *
     * @return $field   (string)    The HTML attribute
     */
    public function doEndFieldset(){

        return '</fieldset></div>';

    }


    /**
     * Creates the arbitrary opening HTML div wrapper
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doOpenSection( $attr=array() ){

        $attr = $this->getAttributes( $attr );

        $field = '<div class="' . $attr['row_class'] . ' open-section" id="lumber_form_' . $field['id'] . '_section">';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;

    }


    /**
     * Creates the arbitrary closing HTML div wrapper
     *
     * @since 1.0.0
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doEndSection(){
        return '</div>';
    }


    /**
     * Creates a select HTML field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     * @param $force_return     (bool)      Forces the field to be returned, even if the attr value
     *                                      is set to echo it.
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doSelect( $attr=array(), $current_form=null, $force_return=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        if ( empty( $attr['options'] ) )
            return;

        if ( empty( $attr['value'] ) && ! empty( $attr['std'] ) )
            $value = $attr['std'];

        $options = sprintf( '<option value="">%s</option>',
            __( '-- Select a Value --', 'lumber' ) );

        foreach( $attr['options'] as $k => $v ) {
            $options .= '<option value="' . $k . '" ' . selected( $k, $attr['value'], false ) . '>' . $v . '</option>';
        }

        $required = ( $attr['req'] == true ) ? ' required ' : null;

        $field = '<select name="' . $attr['name'] . '" ' . $required . ' id="' . $attr['id'] . '">';
        $field .= $options;
        $field .= '</select>';

        if ( $attr['echo'] && ! $force_return )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a select HTML field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doFancySelect( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        if ( empty( $attr['options'] ) )
            return;

        if ( empty( $attr['value'] ) && ! empty( $attr['std'] ) )
            $value = $attr['std'];

        $required_html = ( $attr['req'] == true ) ? '<sup class="req">&#42;</sup>' : null;

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= '<label for="' . $attr['for'] . '">' . $attr['title'] . $required_html . '</label> ';

        $field .= $this->doSelect( $attr, $current_form, true );
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a multiselect HTML field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doMultiselect( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        if ( empty( $attr['options'] ) ){

            $field = 'No options';

        } else {

            $value = $attr['value'];

            if ( is_string( $value ) ){
                $value = explode(',', $value);
            }

            $options = '<option value="">-- Select a Value --</option>';
            $selected = '';

            foreach( $attr['options'] as $k => $v ) {

                if ( is_array( $value ) ){
                    $selected = ( ! empty( $value ) && in_array( $k, $value ) ) ? 'selected=selected' : null;
                } else {
                    $selected = selected( $k, $value, false );
                }
                $options .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
            }

            $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '"><label for="' . $attr['for'] . '">' . $attr['title'] . '</label> ';
            $field .= '<select name="' . $attr['name'] . '[]" multiple id="' . $attr['id'] . '">';
            $field .= $options;
            $field .= '</select>';
            $field .= '<p class="description">' . $attr['desc'] . '</p>';
            $field .= '</div>';
        }

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a select field with US states as values
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doUsStateSelect( $attr=array(), $current_form=null ){

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

        $attr = $this->getAttributes( $attr, $current_form );
        $attr['options'] = $states;

        $field = $this->doFancySelect( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a select field with Mexico states as values
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doMexicoStateSelect( $attr=array(), $current_form=null ){

        $states = array(
            'AGS' => 'Aguascalientes',
            'BCN' => 'Baja California Norte',
            'BCS' => 'Baja California Sur',
            'CAM' => 'Campeche',
            'CHIS' => 'Chiapas',
            'CHIH' => 'Chihuahua',
            'COAH' => 'Coahuila',
            'COL' => 'Colima',
            'DF' => 'Distrito Federal',
            'DGO' => 'Durango',
            'GTO' => 'Guanajuato',
            'GRO' => 'Guerrero',
            'HGO' => 'Hidalgo',
            'JAL' => 'Jalisco',
            'EDM' => 'México - Estado de',
            'MICH' => 'Michoacán',
            'MOR' => 'Morelos',
            'NAY' => 'Nayarit',
            'NL' => 'Nuevo León',
            'OAX' => 'Oaxaca',
            'PUE' => 'Puebla',
            'QRO' => 'Querétaro',
            'QROO' => 'Quintana Roo',
            'SLP' => 'San Luis Potosí',
            'SIN' => 'Sinaloa',
            'SON' => 'Sonora',
            'TAB' => 'Tabasco',
            'TAMPS' => 'Tamaulipas',
            'TLAX' => 'Tlaxcala',
            'VER' => 'Veracruz',
            'YUC' => 'Yucatán',
            'ZAC' => 'Zacatecas)'
        );


        $attr = $this->getAttributes( $attr, $current_form );
        $attr['options'] = $states;

        $field = $this->doFancySelect( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a select field with Mexico states as values
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doCanadaStateSelect( $attr=array(), $current_form=null ){

        $states = array(
            'AB' => 'Alberta',
            'BC' => 'British Columbia',
            'MB' => 'Manitoba',
            'NB' => 'New Brunswick',
            'NL' => 'Newfoundland and Labrador',
            'NT' => 'Northwest Territories',
            'NS' => 'Nova Scotia',
            'NU' => 'Nunavut',
            'PE' => 'Prince Edward Island',
            'SK' => 'Saskatchewan',
            'ON' => 'Ontario',
            'QC' => 'Quebec',
            'YT' => 'Yukon'
        );

        $attr = $this->getAttributes( $attr, $current_form );
        $attr['options'] = $states;

        $field = $this->doFancySelect( $attr, $current_form );

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a textarea HTML input field
     *
     * @since 1.0.0
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doTextarea( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '"><label for="' . $attr['for'] . '">' . $attr['title'] . '</label>';
        $field .= '<textarea id="' . $attr['id'] . '" name="' . $attr['name'] . '" rows="'.$attr['rows'].'" cols="'.$attr['cols'].'" class="large-text '.$attr['field_class'].'" placeholder="' . $attr['placeholder'] . '">' . esc_textarea( $attr['value'] ) . '</textarea>';
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a hidden HTML input field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doCss( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '"><label for="' . $attr['for'] . '">' . $attr['title'] . '</label>';
        $field .= '<textarea class="large-text" name="' . $attr['name'] . '" placeholder="' . $attr['placeholder'] . '" rows="10">' . wp_kses( $attr['value'], '' ) . '</textarea>';
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</p>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a textarea to be used for a series of email addresses
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doEmails( $attr=array(), $current_form=null ){

        return $this->doTextarea( $attr, $current_form );

    }


    /**
     * Creates a textarea to be used for a series of IP addresses
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doIps( $attr=array(), $current_form=null ){

        return $this->doTextarea( $attr, $current_form );

    }


    /**
     * Creates a groupd of checkboxes
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doCheckboxes( $attr=array(), $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        if ( empty( $attr['options'] ) )
            return;

        $options = null;

        $required = ( $attr['req'] == true ) ? ' required ' : null;
        $required_html = ( $attr['req'] == true ) ? '<sup class="req">&#42;</sup>' : null;

        foreach( $attr['options'] as $k => $v ) {

            $key = sanitize_title( $k );
            $id = $attr['id'] . '_' . $key;

            // Multi-dimensional array support or
            // Associative array support
            if ( is_array( $v ) ){
                $title = $v['title'];

                if ( ! empty( $attr['value'] ) && array_key_exists( $v['id'], $attr['value'] ) ){
                    $checked = "checked=check";
                } else {
                    $checked = null;
                }

            } else {
                $title = $v;
                if ( ! empty( $attr['value'] ) && in_array( $key, $attr['value'] ) ){
                    $checked = "checked=check";
                } else {
                    $checked = null;
                }
            }

            $options .= '<input type="checkbox" class="" name="'.$attr['name'].'[]" id="' . $id . '" value="' . $key . '" ' . $checked . ' />';
            $options .= '<label for="' . $id . '">' . $title . $required_html . '</label><br />';
        }

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= $options;
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a WordPress Media Upload field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doUpload( $attr=array(), $current_form=null ){

        wp_enqueue_media();
        wp_enqueue_script( 'lumber-form-fields-upload',
            $this->getBaseDirUrl() . 'assets/javascripts/scripts.js', array('jquery') );

        $attr = $this->getAttributes( $attr, $current_form );

        $value = empty( $attr['current_value'] ) ? intval( $attr['value'] ) : intval( $attr['current_value'] );

        if ( $value ){
            $style = null;
            $image = '<img src="' . wp_get_attachment_thumb_url( $value ) . '" style="border: 1px solid #ddd;" />';
        } elseif ( ! empty( $attr['std'] ) ){
            $style = null;
            $image = '<img src="' . esc_url( $attr['std'] ) . '" style="border: 1px solid #ddd;" />';
        } else {
            $style = 'style="display:none;"';
            $image = null;
        }

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= '<label for="' . $attr['for'] . '">' . $attr['title'] . '</label>';
        $field .= '<span class="lumber-form-fields-upload-container">';
        $field .= '<a href="#" class="button lumber-form-fields-media-upload-handle" style="margin-bottom: 10px;">' . __('Upload', 'lumber_alr_pro') . '</a><br />';
        $field .= '<span class="lumber-form-fields-upload-image-container" ' . $style . '>';
        $field .= $image;
        $field .= '</span>';
        $field .= '<br /><a href="#" class="lumber-form-fields-upload-remove-handle" ' . $style . '>' . __('Remove', 'lumber_alr_pro_settings') . '</a>';
        $field .= '<input type="hidden" class="lumber-form-fields-upload-attachment-id" id="'.$attr['id'].'" name="' . $attr['name'] . '" value="' . $attr['value'] . '"/>';
        $field .= '</span>';
        $field .= '<p class="description">' . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates an arbitrary HTML field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doHtml( $field=null, $current_form=null ){

        $attr = $this->getAttributes( $field, $current_form );

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= '<label for="' . $attr['for'] . '">' . $attr['title'] . '</label>';
        $field .= $attr['std'];
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }


    /**
     * Creates a WordPress Thickbox HTML field
     *
     * @since 1.0.0
     *
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     *
     * @uses add_thickbox() http://codex.wordpress.org/ThickBox
     * @uses add_query_arg() http://codex.wordpress.org/Function_Reference/add_query_arg
     * @uses esc_url() http://codex.wordpress.org/Function_Reference/esc_url
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     */
    public function doThickbox( $attr=null, $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        add_thickbox();

        $field  = '<div class="' . $attr['row_class'] . '" id="' . $attr['row_id'] . '">';
        $field .= '<label for="' . $attr['for'] . '">' . $attr['title'] . '</label>';
        $field .= '<a href="' . add_query_arg( array(
            'TB_iframe' => 'true',
            'width' => '600',
            'height' => '550'
            ), esc_url( $attr['std'] ) ) . '" class="thickbox">' . $attr['placeholder'] . '</a>';
        $field .= '</div>';

        if ( $attr['echo'] )
            echo $field;
        else
            return $field;
    }



    /**
     * Builds an group of select fields to be used for date time
     *
     * @since  1.0
     *
     * @param  $field          (array)  The field containing an array of values
     * @param  $current_form   (string) Given post type
     *
     * @return $field           (mixed)  The HTML attribute, or prints
     */
    public function doTouchtime( $attr=null, $current_form=null ){

        $attr = $this->getAttributes( $attr, $current_form );

        global $wp_locale;

        if ( empty( $attr['value'] ) ){
            $time_adj = current_time('timestamp');

            $jj = gmdate( 'd', $time_adj );
            $mm = gmdate( 'm', $time_adj );
            $aa = gmdate( 'Y', $time_adj );
            $hh = gmdate( 'H', $time_adj );
            $mn = gmdate( 'i', $time_adj );
        } else {
            $jj = $attr['value']['day'];
            $mm = $attr['value']['month'];
            $aa = $attr['value']['year'];
            $hh = $attr['value']['hour'];
            $mn = $attr['value']['minute'];
        }

        $month = '<select name="' . $attr['name'] . '[month]">';
        $month .= '<option value="00" ' . selected( '00', $mm, false ) . '> MM </option>';

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

        $day = '<input type="text" name="' . $attr['name'] . '[day]" value="' . $jj . '" size="2" maxlength="2" autocomplete="off" />';
        $year = '<input type="text" name="' . $attr['name'] . '[year]" value="' . $aa . '" size="4" maxlength="4" autocomplete="off" />';
        $hour = '<input type="text" name="' . $attr['name'] . '[hour]" value="' . $hh . '" size="2" maxlength="2" autocomplete="off" />';
        $minute = '<input type="text" name="' . $attr['name'] . '[minute]" value="' . $mn . '" size="2" maxlength="2" autocomplete="off" />';


        // Final HTML
        $field  = '<div class="' . $attr['row_class'] . ' lumber-form-touch-time" id="' . $attr['row_id'] . '">';
        /* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
        $field .= sprintf( '%1$s %2$s, %3$s @ %4$s : %5$s', $month, $day, $year, $hour, $minute );
        $field .= '<p class="description">'  . $attr['desc'] . '</p>';
        $field .= '</div>';

        if ( $attr['echo'] ){
            echo $field;
        } else {
            return $field;
        }
    }


    /**
     * Displays a group of select boxes that allows relating a Role to a Page ID
     *
     * @since 1.0.1
     * @param array $args Arguments passed by the setting
     * @return void
     */
    public function doRoleToPage( $attr=null, $current_form=null ) {

        $attr = $this->getAttributes( $attr, $current_form );

        $options = null;

        foreach ( $attr['options'] as $option => $name ) {
            if ( ! empty( $name ) ){

                $pages = wp_dropdown_pages( array(
                    'name' => $attr['name'] .'[' . $option . ']',
                    'echo' => 0,
                    'show_option_none' => ' ',
                    'selected' => $attr['value'][ $option ] ) );

                $options .= '<tr><td style="padding: 0 10px 0 0;">' . $name . '</td><td style="padding: 0;">' . $pages . '</td></tr>';
            }
        }

        $html = '<table>' . $options . '</table>';
        // $html .= '<label for="' . $attr['for'] . '"> '  . $attr['desc'] . '</label>';
        $html .= '<p class="description">' . $attr['desc'] . '</p>';

        echo $html;

    }


    /**
     * Builds an array of all possible HTML attributes for a given form field.
     *
     * @since   1.0.0
     *
     * @param   $field          (array)     The field containing an array of values
     * @param   $current_form   (string)    Given post type
     *
     * @return  $attr           (array)     The attributes
     */
    public function getAttributes( $field=null, $current_form=null ){

        $current_form = empty( $field['namespace'] ) ? $current_form : $field['namespace'];
        $field_id = $this->getFieldId( $field );

        if ( isset( $field['value'] ) ){
            $value = $field['value'];
        } else {
            $value = empty( $field['std'] ) ? null : $field['std'];
        }

        $extra_class = ( empty( $field['extra_class'] ) ) ? null : $field['extra_class'];
        $row_classes = array(
            'lumber-form-default-row',
            $extra_class,
            $current_form,
            $field_id
            );

        $attr = wp_parse_args( $field, array(
            'for'         => $current_form . '_' . $field_id,
            'title'       => null,
            'name'        => '_' . $current_form . '_form[meta]['.$field_id.']', // Other people can override the name, by passing it in with the field
            'placeholder' => null,
            'row_class'   => implode( " ", $row_classes ),
            'field_class' => 'large-text',
            'row_id'      => 'lumber_form_' . $current_form . '_' . $field_id . '_row',
            'id'          => $this->getFieldHtmlId( $field ),
            'req'         => null,
            'desc'        => empty( $field['desc'] ) ? null : '<span class="description">' . $field['desc'] . '</span>',
            'echo'        => false,
            'value'       => $value,
            'style'       => false,
            'std'         => false,
            'rows'        => 4,
            'cols'        => 8,
            'autocomplete' => empty( $field['autocomplete'] ) ? null : $field['autocomplete']
        ) );

        return $attr;
    }


    /**
     * Gets an array of all the available form fields. Containing
     * id, type, title, etc.
     *
     * @since 1.0.0
     * @param $attr             (array)     An array containing the HTML attributes to be
     *                                      used in the field
     * @param $current_form     (string)    The current form this field is relevant to
     *
     * @return $field           (mixed)     The HTML attribute, or prints
     *
     * @return An array of all available fields.
     */
    public function getFields(){
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

        $forms = $this->getForms();

        foreach( $forms as $form ){
            $fields[ $form['post_type'] ] = apply_filters( 'lumber_' . $form['post_type'], $default_fields );
        }
        return apply_filters( 'lumber_additional_fields', $fields );
    }


    /**
     * Sets up an empty array to retrieve all available forms
     * via a filter.
     *
     * @since 1.0.0
     *
     * @return  An array of all available forms
     */
    public function getForms(){

        return apply_filters( 'lumber_form_add_new', array() );

    }


    /**
     * Get our meta values for a given post_type
     *
     * @since   1.0.0
     *
     * @param   $post_id    (int)    The post id.
     * @param   $key        (string) The key to retrieve the value for.
     *
     * @return  $meta       (array)  The values for a given meta field
     */
    public function getValues( $post_id=null, $key=null ){

        $post_meta = get_post_meta( $post_id, '_lumber_form_meta', true );

        if ( empty( $key ) ){
            $meta = apply_filters( 'lumber_forms_meta_values', $post_meta, $post_id );
        } else {
            $meta = empty( $post_meta[ $key ] ) ? null : $post_meta[ $key ];
        }

        return $meta;
    }


    /**
     * Builds the markup needed for each form field based on the field type.
     *
     * @since   1.0.0
     *
     * @param   $post_id        (int)    The post id.
     * @param   $current_form   (string) The key to retrieve the value for.
     *
     * @todo    Move this switch to its on method of available field types.
     *
     * @return  The HTML form fields for the given form.
     */
    public function getMetaFieldsHtml( $post_id=null, $current_form=null ){

        $meta = $this->getValues( $post_id );

        $my_fields = $this->getFields();
        $field = null;

        foreach( $my_fields as $form => $fields ){

            if ( $current_form == $form ){

                if ( empty( $fields ) ){

                    $field .= 'Using defaults';

                } else {

                    foreach( $fields as $field ) :

                        $field_id = $this->getFieldId( $field );

                        // Set default value
                        //
                        // If the ID is empty or the meta field is empty
                        // we set the default value to null
                        if ( empty( $feidl_id ) || empty( $meta[ $feidl_id ] ) ){
                            $field['value'] = null;
                        } else {
                            $field['value'] = $meta[ $feidl_id ];
                        }

                        switch( $field['type'] ) {

                            case 'select' :
                                $field .= $this->doFancySelect( $field, $current_form );
                                break;

                            case 'multiselect' :
                                $field .= $this->doMultiselect( $field, $current_form );
                                break;

                            case 'us_state' :
                                $field .= $this->doUsStateSelect( $field, $current_form );
                                break;

                            case 'textarea' :
                            case 'textarea_email_template' :
                                $field .= $this->doTextarea( $field, $current_form );
                                break;

                            case 'textarea_emails' :
                                $field .= $this->doEmails( $field, $current_form );
                                break;

                            case 'open_fieldset' :
                                $field .= $this->doOpenFieldset( $field, $current_form );
                                break;

                            case 'end_fieldset' :
                                $field .= $this->doEndFieldset();
                                break;

                            case 'open_section' :
                                $field .= $this->doOpenSection( $field, $current_form );
                                break;

                            case 'end_section' :
                                $field .= $this->doEndSection();
                                break;

                            case 'checkbox' :
                                $field .= $this->doCheckbox( $field, $current_form );
                                break;

                            case 'checkboxes' :
                                $field .= $this->doCheckboxes( $field, $current_form );
                                break;

                            case 'radio' :
                                $field .= $this->doRadio( $field, $current_form );
                                break;

                            case 'hidden' :
                                $field .= $this->doHidden( $field, $current_form );
                                break;

                            case 'upload' :
                                $field .= $this->doUpload( $field, $current_form );
                                break;

                            case 'html' :
                                $field .= $this->doHtml( $field, $current_form );
                                break;

                            case 'thickbox_url' :
                                $field .= $this->doThickbox( $field, $current_form );
                                break;

                            case 'email' :
                                $field .= $this->doEmail( $field, $current_form );
                                break;

                            case 'touchtime' :
                                $field .= $this->doTouchtime( $field, $current_form );
                                break;

                            default:
                                $field .= $this->doFancyText( $attr, $current_form );
                                break;
                        }

                    endforeach;
                }
            }
        }

        return $field;

    }



    /**
     * Merge the meta fields with that of the current form,
     * this allows us to add additional data to the fields.
     * Essentially we are going from this:
     * array( 'first_name' ); to
     * array( 'first_name' => array( 'type' => 'text', 'id' => etc. ) )
     *
     * @since 1.0.0
     *
     * @param   $meta The meta fields we are merging with
     * @param   $current_form The current form, i.e., post type
     *
     * @return  An array of formated meta fields, array( 'first_name' => array( 'type' => 'text', 'id' => etc. ) )
     */
    public function getFormattedMeta( $meta=null, $current_form=null ){
        $fields = $this->getFields();
        $current_form_fields = $fields[ $current_form ];
        foreach( $current_form_fields as $field ){
            foreach( $meta as $k => $v ){
                if ( ! empty( $field['id'] ) ) {
                    if ( $field['id'] == $k ){
                        $formatted[ $k ] = $field;
                        $formatted[ $k ]['value'] = $v;
                    }
                }
            }
        }
        return $formatted;
    }


    /**
     * Saves ALL post meta to a single serialized value "_lumber_form_meta"
     *
     * @since 1.0.0
     *
     * @param    $post_id The post id
     * @param    $meta The values being saved
     * @param    $current_form The post type of the form or a unique key. This
     *           is only used for the available hooks.
     *
     * @return  $post_id (mixed)    Post ID on success false on failure
     */
    public function saveMeta( $post_id=null, $meta=null, $current_form=null ){
        $formatted_meta = $this->getFormattedMeta( $meta, $current_form );
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

        $sanitized = apply_filters( 'lumber_form_' . $current_form . '_sanitized_meta', $sanitized );
        $post_id = apply_filters( 'lumber_form_' . $current_form . '_before_save_meta', $post_id, $meta );
        if ( $post_id !== false ){
            // http://codex.wordpress.org/Function_Reference/sanitize_meta
            update_post_meta( $post_id, '_lumber_form_meta', $sanitized );
        }

        return $post_id;
    }


    /**
     * Sanitize form a form field value.
     *
     * @since 1.0.0
     *
     * @param   $type The field type being sanitized, i.e., text, radio, checkbox, etc.
     *          see getMetaFieldsHtml() for full list.
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
                $value = $this->sanitizeEmails( $field['value'] );
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
                // $value = $this->sanitizeNumber( $field );
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
        // $value = apply_filters( 'lumber_form_sanitize_' . $current_form . '_' . $field['id'], $value );

        return $value;
    }


    /**
     * This takes an array of "stuff" determines in it what are valid
     * emails and returns all the emails separated by a new line. For
     * use in a textarae.
     *
     * @since 1.0.0
     *
     * @param $emails An array of emails to validate
     *
     * @return Validated emails separated by a new line (for use in a textarea)
     */
    public function sanitizeValidateEmails( $emails=null ){
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
     * @since 1.0.0
     *
     * @param $input (string) The input being saved
     *
     * @return Sanitized email addresses that are separated by a blank line.
     */
    public function sanitizeEmails( $input ){

        // Explode on new lines, remove blank spaces, convert to array, then re-index
        $input = array_values( array_filter( explode( PHP_EOL, trim( $input ) ), 'trim' ) );
        $array_emails = array();

        // build an array of emails
        foreach( $input as $value ){

            if ( $this->sanitizeForwardComments( $value ) ){
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


    /**
     * Determines if a value is numeric and between the min, max value if set.
     *
     * @since 1.0.0
     *
     * @param $field    (array) The field
     * @param $min      (int)   The min value
     * @param $max      (int)   The max value
     *
     * @return $field   (array)
     */
    public function sanitizeNumber( $field=null, $min=null, $max=null ){

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
            $field['error'] = false;
        }

        return $field;
    }


    /**
     * Default sanitize
     *
     * @since 1.0.0
     * @param $value (array)   The value to sanitize
     *
     * @return Sanitized value
     */
    public function sanitizeDefault( $value=null ){

        return esc_attr( $value );

    }


    /**
     * Allows for sanitizing over an array of values
     *
     * @since 1.0.0
     * @param $value (array)   The value to sanitize
     *
     * @return
     */
    public function sanitizeMultiselect( $value=null ){

        $tmp = array();
        foreach( $value as $v ){
            $tmp[] = $this->sanitizeDefault( $v );
        }

        return $tmp;

    }


    /**
     * Validate the comment from a string. A valid comment starts with "//"
     *
     * @since 1.0.0
     *
     * @param (string)$value The value we want to sanitize
     *
     * @return The comment if valid, otherwise false
     */
    public function sanitizeForwardComments( $value=null ){

        $comment = false;
        $pos = strpos( trim( $value ), '//' );

        if ( $pos !== false ){
            $comment = $value;
        }

        return $comment;
    }


    public function sanitizeIps( $value=null ){

        // textarea to array
        $textarea_values = array_values( array_filter( explode( PHP_EOL, $value ), 'trim' ) );
        $ips = array();

        foreach( $textarea_values as $textarea_value ){

            // Allow forward comments
            $comment = $this->sanitizeForwardComments( $textarea_value );

            if ( $comment ){
                $ips[] = $comment;
            }

            // Sanitize our IP address
            $valid_ip = $this->sanitizeIp( $textarea_value );

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
     * Validate an IP address
     *
     * @since 1.0.0
     *
     * @param $ip An IP address to validate
     *
     * @return Valid IP
     */
    public function sanitizeIp( $ip=null ){
        $ip = trim( $ip );
        return ( filter_var( $ip, FILTER_VALIDATE_IP ) ) ? $ip : false;
    }


    /**
     *
     * @since 1.0.0
     * @param
     *
     * @return
     */
    public function sanitizeTouchtime( $value=null ){

        return $value;

    }


    /**
     * Prints the meta fields, i.e., form fields
     *
     * @since   1.0.0
     * @uses    getMetaFieldsHtml()
     * @param   $post (object) The global post object
     *
     * @return  Displays the meta fields
     */
    public function metaFields( $post ){ ?>
        <?php wp_nonce_field( 'lumber_form_meta_box', 'lumber_form_meta_box_nonce' ); ?>
        <?php echo $this->getMetaFieldsHtml( $post->ID, $post->post_type ); ?>
    <?php }


    /**
     * Filter the base URL
     *
     * @since 1.0.0
     * @param
     *
     * @return The base URL
     */
    public function getBaseDirUrl(){

        return apply_filters( 'lumber_dir_url', plugin_dir_url( __FILE__ ) );

    }


    /**
     * Generates a dynamic field id.
     *
     * @since   1.0.1
     * @todo    make duplicate IDs dynamic by prefixing _$i
     * @return  The dynamic field id
     */
    public function getFieldId( $field=null ){

        if ( empty( $field['id'] ) ){
            $field_id = trim( strtolower( str_replace( array(' ', '-'), '_', $field['title'] ) ) );
        } else {
            $field_id = $field['id'];
        }

        return $field_id;

    }


    /**
     * Generates a dynamic field id.
     *
     * @since   1.0.1
     * @todo    make duplicate IDs dynamic by prefixing _$i
     * @return  The dynamic field id
     */
    public function getFieldHtmlId( $field=null ){

        return $field['namespace'] . '_' . $this->getFieldId( $field );

    }
}
endif;