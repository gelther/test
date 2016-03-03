<?php

if ( true )  {
  echo 'true';
}

$array[ 1 ] = '1';
$array[2] = '2';

			$values['field'] 		= 'select';
			$roles = array_keys( get_editable_roles() );
			$values['options'] 		= array_combine( $roles, $roles );
			$values['options']		= array_merge( $values['options'], array( 'not_logged_in' => 'Not logged in' ) );



			$values['field'] 		= 'select';
			$roles 				= array_keys( get_editable_roles() );
			$values['options'] 		= array_combine( $roles, $roles );
			$values['options']		= array_merge( $values['options'], array( 'not_logged_in' => 'Not logged in' ) );
