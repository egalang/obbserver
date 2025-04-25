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

Editor::inst( $db, 'grade_scale' )
	->fields(
		Field::inst( 'id' ),
		Field::inst( 'scale' ),
		Field::inst( 'grade' )
	)
	->process( $_POST )
	->json();
