

<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "editor/lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'payment_list' )
	->where( 'payment_list.enrollment_id', $_POST['id'] )
	->fields(
		Field::inst( 'payment_list.id' ),
		Field::inst( 'payment_list.enrollment_id' ),
		Field::inst( 'payment_list.date' ),
		Field::inst( 'payment_list.amount' )
	)
	->process( $_POST )
	->json();
