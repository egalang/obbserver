<?php

include( "editor/lib/DataTables.php" );

use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

Editor::inst( $db, 'help' )
	->where( 'help.email', $_POST['email'] )
	->where( 'help.status', 'Closed', '!=')
	->fields(
		Field::inst( 'help.id' ),
		Field::inst( 'help.email' )
			->validator( Validate::email( ValidateOptions::inst()
				->message( 'Please enter a valid e-mail address' )  
			) )
			->validator( Validate::notEmpty( ValidateOptions::inst()
				->message( 'An email is required' ) 
			) ),
		Field::inst( 'help.title' ),
		Field::inst( 'help.description' ),
		Field::inst( 'help.status' ),
		Field::inst( 'help.date' ),
		Field::inst( 'help.ticket_id' )
	)
	->process( $_POST )
	->json();
