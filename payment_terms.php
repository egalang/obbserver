

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
Editor::inst( $db, 'payment_terms' )
	->fields(
		Field::inst( 'payment_terms.id' ),
		Field::inst( 'payment_terms.name' )
	)
	->process( $_POST )
	->json();
