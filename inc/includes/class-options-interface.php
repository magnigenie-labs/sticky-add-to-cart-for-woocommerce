<?php
class Wsac_Options_Framework_Interface {

	/**
	 * Generates the tabs that are used in the options menu
	 */
	static function wsac_optionsframework_tabs() {
		$counter = 0;
		$options = & Wsac_Options_Framework::_wsac_optionsframework_options();
		$menu = '';

		foreach ( $options as $value ) {
			// Heading for Navigation
			if ( $value['type'] == "heading" ) {
				$counter++;
				$class = '';
				$class = ! empty( $value['id'] ) ? $value['id'] : $value['name'];
				$class = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower($class) ) . '-tab';
				$menu .= '<a id="options-group-'.  $counter . '-tab" class="nav-tab ' . $class .'" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#options-group-'.  $counter ) . '">' . esc_html( $value['name'] ) . '</a>';
			}
		}

		return $menu;
	}

	/**
	 * Generates the options fields that are used in the form.
	 */
	static function wsac_optionsframework_fields() {

		global $allowedtags;
		$wsac_optionsframework_settings = get_option( 'wsac_optionsframework' );

		// Gets the unique option id
		if ( isset( $wsac_optionsframework_settings['id'] ) ) {
			$option_name = $wsac_optionsframework_settings['id'];
		}
		else {
			$option_name = 'wsac_optionsframework';
		};

		$settings = get_option($option_name);
		$options = & Wsac_Options_Framework::_wsac_optionsframework_options();

		$counter = 0;
		$menu = '';

		foreach ( $options as $value ) {

			$val = '';
			$select_value = '';
			$output = '';

			// Wrap all options
			if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {

				// Keep all ids lowercase with no spaces
				$value['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['id']) );

				$id = 'section-' . $value['id'];

				$class = 'section';
				if ( isset( $value['type'] ) ) {
					$class .= ' section-' . $value['type'];
				}
				if ( isset( $value['class'] ) ) {
					$class .= ' ' . $value['class'];
				}

				// If there is a description save it for labels
				$explain_value = '';
				if ( isset( $value['desc'] ) ) {
					$explain_value = $value['desc'];
				}

				$desc_tip = '';
				if( isset($value['desc_tip']) ) {
					$desc_tip = $explain_value;
				}

				$output .= '<div id="' . esc_attr( $id ) .'" class="row ' . esc_attr( $class ) . '">'."\n";
				if ( isset( $value['name'] ) ) {
					$output .= '<div class="col-md-4 heading"><label class="explain" for="' . esc_attr( $value['id'] ) . '">' . esc_html( $value['name'] ) . '</label>';

					
					if( $explain_value != '' && $desc_tip == '' )
						$output .= '<span class="dashicons dashicons-editor-help pull-right" data-toggle="tooltip" data-placement="top" title="'. wp_kses( $explain_value, $allowedtags) .'"></span>';
					$output .= '</div>' . "\n";
				}
				if ( $value['type'] != 'editor' ) {
					$output .= '<div class="col-md-8 option">' . "\n" . '<div class="controls">' . "\n";
				}
				else {
					$output .= '<div class="option">' . "\n" . '<div>' . "\n";
				}
			}

			// Set default value to $val
			if ( isset( $value['std'] ) ) {
				$val = $value['std'];
			}

			// If the option is already saved, override $val
			if ( ( $value['type'] != 'heading' ) && ( $value['type'] != 'info') ) {
				if ( isset( $settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if ( !is_array($val) ) {
						$val = stripslashes( $val );
					}
				}
			}

			// Set the placeholder if one exists
			$placeholder = '';
			if ( isset( $value['placeholder'] ) ) {
				$placeholder = ' placeholder="' . esc_attr( $value['placeholder'] ) . '"';
			}

			if ( has_filter( 'wsac_optionsframework_' . $value['type'] ) ) {
				$output .= apply_filters( 'wsac_optionsframework_' . $value['type'], $option_name, $value, $val );
			}


			switch ( $value['type'] ) {

			// Basic text input
			case 'text':
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="form-control" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '"' . $placeholder . ' />';
				break;

			// Password input
			case 'password':
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="password" value="' . esc_attr( $val ) . '" />';
				break;

			// Textarea
			case 'textarea':
				$rows = '8';
				if ( isset( $value['settings']['rows'] ) ) {
					$custom_rows = $value['settings']['rows'];
					if ( is_numeric( $custom_rows ) ) {
						$rows = $custom_rows;
					}
				}

				$val = stripslashes( $val );
				$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" rows="' . $rows . '"' . $placeholder . '>' . esc_textarea( $val ) . '</textarea>';
				break;

			// Select Box
			case 'select':
				$output .= '<select class="form-control" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';

				foreach ($value['options'] as $key => $option ) {
					$output .= '<option'. selected( $val, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				}
				$output .= '</select>';
				break;


			// Radio Box
			case "radio":
				$name = $option_name .'['. $value['id'] .']';
				$output .= '<div class="btn-group" data-toggle="buttons">';
				foreach ($value['options'] as $key => $option) {
					$id = $option_name . '-' . $value['id'] .'-'. $key;
					$active = '';
					if( $val == $key ) $active = 'active';
					$output .= '<label class="btn btn-default '. $active .'" for="' . esc_attr( $id ) . '"><input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' autocomplete="off" />' . esc_html( $option ) . '</label>';
				}
				$output .= '</div>';
				break;

			// Image Selectors
			case "images":
				$name = $option_name .'['. $value['id'] .']';
				foreach ( $value['options'] as $key => $option ) {
					$selected = '';
					if ( $val != '' && ($val == $key) ) {
						$selected = ' of-radio-img-selected';
					}
					$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. checked( $val, $key, false ) .' />';
					$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
					$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" />';
				}
				break;

			case "productselect2":
			  $products = $val;
			  $output .= '<select class="wsac-select2 of-input" multiple="multiple" name="' .  $option_name . '[' . $value['id'] . '][]'  . '" id="' .  $option_name . '[' . $value['id'] . '][]'  . '">';
			  if( $products !== '' ) {

			  	if( ! is_array($products) ) {
			  		if( strpos($products, ',') !== false ) {
			  			$products = explode(',', $products);
			  		}
			  	}
			  	
			  	if( is_array($products) && count($products) > 0 ) {
			  		foreach( $products as $product ) {
			  			$product_id = $product;
			  			$product = wc_get_product( $product_id );
			  			$sku = $product->get_sku();
			  			$output .= '<option value='.$product_id.' selected>'.$product->get_title().'</option>';
			  		}
			  	}
			  }
				$output .= '</select>';
			  break;


			  case "excludeproductselect2":
			  $products = $val;
			  $output .= '<select class="wsac-select2 of-input" multiple="multiple" name="' .  $option_name . '[' . $value['id'] . '][]'  . '" id="' .  $option_name . '[' . $value['id'] . '][]'  . '">';
			  if( $products !== '' ) {

			  	if( ! is_array($products) ) {
			  		if( strpos($products, ',') !== false ) {
			  			$products = explode(',', $products);
			  		}
			  	}
			  	
			  	if( is_array($products) && count($products) > 0 ) {
			  		foreach( $products as $product ) {
			  			$product_id = $product;
			  			$product = wc_get_product( $product_id );
			  			$sku = $product->get_sku();
			  			$output .= '<option value='.$product_id.' selected>'.$product->get_title().'</option>';
			  		}
			  	}
			  }
				$output .= '</select>';
			  break;

			// Checkbox
			case "checkbox":
				$output .= '<label class="toggle"><input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 'yes', false) .' /><span class="handle"></span></label>';
				break;

			// Multicheck
			case "multicheck":
				foreach ($value['options'] as $key => $option) {
					$checked = '';
					$label = $option;
					$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

					$id = $option_name . '-' . $value['id'] . '-'. $option;
					$name = $option_name . '[' . $value['id'] . '][' . $option .']';

					if ( isset($val[$option]) ) {
						$checked = checked($val[$option], 1, false);
					}

					$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
				}
				break;

			// Color picker
			case "color":
				$default_color = '';
				if ( isset($value['std']) ) {
					if ( $val !=  $value['std'] )
						$default_color = ' data-default-color="' .$value['std'] . '" ';
				}
				$output .= '<input name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" class="of-color"  type="text" value="' . esc_attr( $val ) . '"' . $default_color .' />';
				break;

			// Uploader
			case "upload":
				$output .= Wsac_Options_Framework_Media_Uploader::wsac_optionsframework_uploader( $value['id'], $val, null );
				break;

			//Number field
			case "number":
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="number" value="' . esc_attr( $val ) . '"' . $placeholder . ' />';
				break;

			//Icon field
			case "icon":
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input wsac-icon-picker" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '"' . $placeholder . ' />';
				break;
			

			// Background
			case 'background':

				$background = $val;

				// Background Color
				$default_color = '';
				if ( isset( $value['std']['color'] ) ) {
					if ( $val !=  $value['std']['color'] )
						$default_color = ' data-default-color="' .$value['std']['color'] . '" ';
				}
				$output .= '<input name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" class="of-color of-background-color"  type="text" value="' . esc_attr( $background['color'] ) . '"' . $default_color .' />';

				// Background Image
				if ( !isset($background['image']) ) {
					$background['image'] = '';
				}

				$output .= Wsac_Options_Framework_Media_Uploader::wsac_optionsframework_uploader( $value['id'], $background['image'], null, esc_attr( $option_name . '[' . $value['id'] . '][image]' ) );

				$class = 'of-background-properties';
				if ( '' == $background['image'] ) {
					$class .= ' hide';
				}
				$output .= '<div class="' . esc_attr( $class ) . '">';

				// Background Repeat
				$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
				$repeats = wsac_of_recognized_background_repeat();

				foreach ($repeats as $key => $repeat) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				}
				$output .= '</select>';

				// Background Position
				$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
				$positions = wsac_of_recognized_background_position();

				foreach ($positions as $key=>$position) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
				}
				$output .= '</select>';

				// Background Attachment
				$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
				$attachments = wsac_of_recognized_background_attachment();

				foreach ($attachments as $key => $attachment) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				}
				$output .= '</select>';
				$output .= '</div>';
			break;

			// Editor
			case 'editor':
				// $output .= '<span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="'. wp_kses( $explain_value, $allowedtags) .'"></span>'."\n";
				echo $output;
				$textarea_name = esc_attr( $option_name . '[' . $value['id'] . ']' );
				$default_editor_settings = array(
					'textarea_name' => $textarea_name,
					'media_buttons' => true,
					'tinymce' => array( 'plugins' => 'wordpress' )
				);
				$editor_settings = array();
				if ( isset( $value['settings'] ) ) {
					$editor_settings = $value['settings'];
				}
				$editor_settings = array_merge( $default_editor_settings, $editor_settings );
				wp_editor( $val, $value['id'], $editor_settings );
				$output = '';
				break;

			// MultiSelect Field
			case "multiselect":
      
      	$cats = $val;

      	$output .= '<select multiple="multiple" name="' . esc_attr( $option_name . '[' . $value['id'] . '][]' ) . '" id="' . esc_attr( $value['id'] ) . '">';

      	foreach ($value['options'] as $key => $option ) {

      		if( $cats !== '' ) {
      			if( ! is_serialized($cats) ) {
      				if( strpos($cats, ',') !== false ) {
      					$data = explode(',', $cats);
      					if( is_array($data) && !empty($data) ) {
      						if( in_array($key, $data) ) {
      							$output .= '<option value="' . esc_attr( $key ) . '" selected="selected">' . esc_html( $option ) . '</option>';
      						}
      						else {
      							$output .= '<option value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
      						}
      					}
      				}
      				else {
      					$output .= '<option value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
      					if( $cats == $key ) {
      						$output .= '<option  value="' . esc_attr( $key ) . '" selected="selected">' . esc_html( $option ) . '</option>';
      					}
      					
      				}
      			}

      			if( is_serialized($cats) ) {
      				$product_cats = unserialize($cats);
      				if( is_array($product_cats) && !empty($product_cats) ) {
      					
      					if( in_array($key, $product_cats) ) {
      						$output .= '<option value="' . esc_attr( $key ) . '" selected="selected">' . esc_html( $option ) . '</option>';
      					}
      					else {
      						$output .= '<option value="' . esc_attr( $key ) . '" >' . esc_html( $option ) . '</option>';
      					}
      				}
      			}
      		}
      		else {
      			$output .= '<option value="' . esc_attr( $key ) . '" >' . esc_html( $option ) . '</option>';
      		}
      		
      	}

      	$output .= '</select>';
			break;

			// Info
			case "info":
				$id = '';
				$class = 'section';
				if ( isset( $value['id'] ) ) {
					$id = 'id="' . esc_attr( $value['id'] ) . '" ';
				}
				if ( isset( $value['type'] ) ) {
					$class .= ' section-' . $value['type'];
				}
				if ( isset( $value['class'] ) ) {
					$class .= ' ' . $value['class'];
				}

				$output .= '<div ' . $id . 'class="' . esc_attr( $class ) . '">' . "\n";
				if ( isset($value['name']) ) {
					$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
				}
				if ( isset( $value['desc'] ) ) {
					$output .= apply_filters('wsac_of_sanitize_info', $value['desc'] ) . "\n";
				}
				$output .= '</div>' . "\n";
				break;

			// Heading for Navigation
			case "heading":
				$counter++;
				if ( $counter >= 2 ) {
					$output .= '</div>'."\n";
				}
				$class = '';
				$class = ! empty( $value['id'] ) ? $value['id'] : $value['name'];
				$class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class) );
				$output .= '<div id="options-group-' . $counter . '" class="group ' . $class . '">';
				$output .= '<h3>' . esc_html( $value['name'] ) . '</h3>' . "\n";
				break;
			}

			if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
				$output .= '</div>';

				if( isset($value['desc_tip']) && isset($value['desc']) ) {
					$output .= '<p class="text-help-tooltip">'.$value['desc'].'</p>';
				}

				if ( ( $value['type'] != "checkbox" ) && ( $value['type'] != "editor" ) ) {
					// $output .= '<span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="'. wp_kses( $explain_value, $allowedtags) .'"></span>'."\n";
				}
				$output .= '</div></div>'."\n";
			}

			echo $output;
		}

		// Outputs closing div if there tabs
		if ( Wsac_Options_Framework_Interface::wsac_optionsframework_tabs() != '' ) {
			echo '</div>';
		}
	}

}