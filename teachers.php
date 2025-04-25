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

Editor::inst( $db, 'teachers' )
	->fields(
		Field::inst( 'id' ),
		Field::inst( 'wp_id' ),
		Field::inst( 'lastname' ),
		Field::inst( 'firstname' ),
		Field::inst( 'middlename' )
	)
	->process( $_POST )
	->json();
