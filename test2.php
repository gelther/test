
	public function set_properties( $request ) {
		$this->start = isset( $request['start'] )            ? sanitize_text_field( $request['start'] ) : '';
		$this->end   = isset( $request['end'] )             ? sanitize_text_field( $request['end'] )  : '';
		$this->download = isset( $request['download'] ) ? absint( $request['download'] )   : null;
		$this->price_id = empty( $request['edd_price_option'] ) && 0 !== $request['edd_price_option'] ? absint( $request['edd_price_option'] )   : null;
	}
	public function set_properties( $request ) {
		$this->start = true;
		$this->end   = true;
		$this->download = true;
		$this->price_id = true;
	}
