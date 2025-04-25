

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
        Field::inst( 'payment_list.enrollment_id' )
			->options( Options::inst()
				->table( 'enrollment_list' )
				->value( 'id' )
				->label( array('lastname', 'firstname', 'middlename') )
				->render( function ( $row ) {
					return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
				} )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'enrollment_list.lastname' ),
		Field::inst( 'enrollment_list.firstname' ),
		Field::inst( 'enrollment_list.middlename' ),
		Field::inst( 'payment_list.level_id' )
			->options( Options::inst()
				->table( 'grade_levels' )
				->value( 'id' )
				->label( 'name' )
			),
		Field::inst( 'grade_levels.name' ),
		Field::inst( 'payment_list.term_id' ),
			//->options( Options::inst()
			//	->table( 'payment_terms' )
			//	->value( 'id' )
			//	->label( 'name' )
			//),
		Field::inst( 'payment_terms.name' ),
		Field::inst( 'payment_list.tranche' ),
		Field::inst( 'payment_list.amount' ),
		Field::inst( 'payment_list.due_date' ),
		Field::inst( 'payment_list.paid_date' ),
		Field::inst( 'payment_list.billed' ),
		Field::inst( 'payment_list.paid' ),
		Field::inst( 'payment_list.comments' ),
		Field::inst( 'payment_list.sy' ),
		Field::inst( 'payment_list.ecom' )
	)
	->leftJoin( 'enrollment_list', 'payment_list.enrollment_id', '=', 'enrollment_list.id' )
	->leftJoin( 'payment_terms', 'payment_list.term_id', '=', 'payment_terms.id' )
	->leftJoin( 'grade_levels', 'payment_list.level_id', '=', 'grade_levels.id' )
	->process( $_POST )
	->json();
