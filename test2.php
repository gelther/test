
<?php
class EDD_Batch_Customers_Export extends EDD_Batch_Export {

	/**
	 * Set the properties specific to the Customers export
	 *
	 * @since 2.4.2
	 * @param array $request The Form Data passed into the batch processing
	 */
	public function set_properties( $request ) {
		$this->start = isset( $request['start'] )            ? sanitize_text_field( $request['start'] ) : '';
		$this->end   = isset( $request['end'] )             ? sanitize_text_field( $request['end'] )  : '';
		$this->download = isset( $request['download'] ) ? absint( $request['download'] )   : null;
		$this->price_id = empty( $request['edd_price_option'] ) && 0 !== $request['edd_price_option'] ? absint( $request['edd_price_option'] )   : null;
	}
	public function set_properties( $request ) {
		$this->start = isset( $request['start'] )            ? sanitize_text_field( $request['start'] ) : '';
		$this->end = isset( $request['end'] )             ? sanitize_text_field( $request['end'] )  : '';
		$this->download = isset( $request['download'] ) ? absint( $request['download'] )   : null;
		$this->price_id = empty( $request['edd_price_option'] ) && 0 !== $request['edd_price_option'] ? absint( $request['edd_price_option'] )   : null;
	}
	public function set_properties( $request ) {
		$thisstart = isset( $request['start'] )            ? sanitize_text_field( $request['start'] ) : '';
		$thisend   = isset( $request['end'] )             ? sanitize_text_field( $request['end'] )  : '';
		$thisdownload = isset( $request['download'] ) ? absint( $request['download'] )   : null;
		$thisprice_id = empty( $request['edd_price_option'] ) && 0 !== $request['edd_price_option'] ? absint( $request['edd_price_option'] )   : null;
	}
}
