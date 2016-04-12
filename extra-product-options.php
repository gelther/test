<?php

add_filter( 'woocommerce_order_get_items', 'tm_woocommerce_order_get_items', 10, 2 );

/** Adds options to the array of items/products of an order **/
	function tm_woocommerce_order_get_items( $items=array(), $order=false ) {

		if ( ! is_array( $items ) || defined( 'TM_IS_SUBSCRIPTIONS_RENEWAL' ) || ($this->tm_epo_global_prevent_options_from_emails=='yes') ) {
			return $items;;
		}

		$order_currency = $order->get_order_currency();
		$mt_prefix      = $order_currency;;

		foreach ( $items as $item_id => $item ) {
			$has_epo = isset( $item['item_meta'] ) && isset( $item['item_meta']['_tmcartepo_data'] ) && isset( $item['item_meta']['_tmcartepo_data'][0] );

			if ( $has_epo ) {
				$epos = maybe_unserialize( $item['item_meta']['_tmcartepo_data'][0] );
				if ( ! is_array( $epos ) ) {
					return $items;
				}
				$current_product_id  =$item['product_id'];
				$original_product_id = floatval( TM_EPO_WPML()->get_original_id( $current_product_id, 'product' ) );
				if ( TM_EPO_WPML()->get_lang()==TM_EPO_WPML()->get_default_lang() && $original_product_id!=$current_product_id ) {
					$current_product_id = $original_product_id;
				}
				$wpml_translation_by_id =TM_EPO_WPML()->get_wpml_translation_by_id( $current_product_id );
				$_unique_elements_added =array();
				$_items_to_add          =array();
				foreach ( $epos as $key => $epo ) {
					if ( $epo && is_array( $epo ) && isset( $epo['section'] ) ) {
						if ( ! isset( $epo['quantity'] ) ) {
							$epo['quantity'] = 1;
						}
						if ( $epo['quantity']<1 ) {
							$epo['quantity'] = 1;
						}
						if ( isset( $wpml_translation_by_id[ $epo['section'] ] ) ) {
							$epo['name'] = $wpml_translation_by_id[ $epo['section'] ];
						}
						if ( ! empty( $epo['multiple'] ) && ! empty( $epo['key'] ) ) {
							$pos = strrpos( $epo['key'], '_' );
							if ( $pos!==false ) {
								if ( isset( $wpml_translation_by_id['options_' . $epo['section']] ) && is_array( $wpml_translation_by_id['options_' . $epo['section']] ) ) {
									$av =array_values( $wpml_translation_by_id['options_' . $epo['section']] );
									if ( isset( $av[ substr( $epo['key'], $pos+1 ) ] ) ) {
										$epo['value'] = $av[ substr( $epo['key'], $pos+1 ) ];
									}
								}
							}
						}
						$epo['value'] = $this->tm_order_item_display_meta_value( $epo['value'] );

						$new_currency             = false;
						$_current_currency_prices = $epo['price_per_currency'];
						if ( $mt_prefix!==''
								&& $_current_currency_prices!==''
								&& is_array( $_current_currency_prices )
								&& isset( $_current_currency_prices[ $mt_prefix ] )
								&& $_current_currency_prices[ $mt_prefix ]!='' ){

							$new_currency = true;
							$epo['price'] = $_current_currency_prices[ $mt_prefix ];

						}
						if ( ! $new_currency ) {
							$epo['price'] = apply_filters( 'woocommerce_tm_epo_price2', $epo['price'] );
						}

						if ( ! empty( $epo['multiple_values'] ) ) {
							$display_value_array =explode( $epo['multiple_values'], $epo['value'] );
							$display_value       ='';
							foreach ( $display_value_array as $d => $dv ) {
								$display_value .='<span class="cpf-data-on-cart">' . $dv . '</span>';
							}
							$epo['value'] = $display_value;
						}

						$epovalue = '';
						if ( $this->tm_epo_hide_options_prices_in_cart=='normal' && ! empty( $epo['price'] ) ) {
							$epovalue .= ' ' . ((! empty( $item['item_meta']['tm_has_dpd'] ))?'':(wc_price( (float) $epo['price']/(float) $epo['quantity'] )));
						}
						if ( $epo['quantity']>1 ) {
							$epovalue .= ' &times; ' . $epo['quantity'];
						}
						if ( $epovalue!=='' ) {
							$epo['value'] .= '<small>' . $epovalue . '</small>';
						}

						if ( is_array( $epo['value'] ) ) {
							$epo['value'] = array_map( array( TM_EPO_HELPER(), 'html_entity_decode' ), $epo['value'] );

						} else {
							$epo['value'] =TM_EPO_HELPER()->html_entity_decode( $epo['value'] );
						}

						if ( $this->tm_epo_strip_html_from_emails=='yes' ) {
							$epo['value'] =strip_tags( $epo['value'] );
						} else {
							if ( ! empty( $epo['images'] ) && $this->tm_epo_show_image_replacement=='yes' ) {
								$display_value ='<span class="cpf-img-on-cart"><img alt="" class="attachment-shop_thumbnail wp-post-image epo-option-image" src="' .
												apply_filters( 'tm_image_url', $epo['images'] ) . '" /></span>';
								$epo['value']  =$display_value . $epo['value'];
							}
						}
						if ( empty( $epo['hidelabelinorder'] ) || empty( $epo['hidevalueinorder'] ) ) {
							if ( ! empty( $epo['hidelabelinorder'] ) ) {
								$epo['name'] ='';
							}
							if ( ! empty( $epo['hidevalueinorder'] ) ) {
								$epo['value'] ='';
							}
							if ( isset( $_unique_elements_added[ $epo['section'] ] ) && isset( $_items_to_add[ $epo['section'] ] ) ) {
								$_ta                              =$_items_to_add[ $epo['section'] ];
								$_ta[ $epo['name'] ][]            =$epo['value'];
								$_items_to_add[ $epo['section'] ] =$_ta;
							} else {
								$_ta                              =array();
								$_ta[ $epo['name'] ]              =array( $epo['value'] );
								$_items_to_add[ $epo['section'] ] =$_ta;
							}
							$_unique_elements_added[ $epo['section'] ] =$epo['section'];
						}
					}
				}

				foreach ( $_items_to_add as $uniquid => $element ) {
					foreach ( $element as $key => $value ) {
						if ( ! is_array( $value ) ) {
							$value =implode( ',', $value );
						}
						$items[ $item_id ]['item_meta'][ $key ][]                                               = $value;
						$items[ $item_id ]['item_meta_array'][ count( $items[ $item_id ]['item_meta_array'] ) ] = (object) array( 'key' => $key, 'value' => $value );
					}
				}
			}
		}
		return $items;

	}
