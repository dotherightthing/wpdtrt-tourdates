<?php
/**
 * ACF field exports
 * 	To re-export from UI: Custom Fields > Tools > Export Field Groups > DTRT Tour Dates
 * 	To show + edit in UI: Custom Fields > Tools > Import Field Groups > config/acf-export-2017-09-15.json
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_595c333a1fd7a',
	'title' => 'DTRT Tour Dates',
	'fields' => array (
		array (
			'key' => 'field_595c382ec7af6',
			'label' => 'Term type',
			'name' => 'wpdtrt_tourdates_acf_tour_category_type',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'region' => 'Region',
				'tour' => 'Tour',
				'tour_leg' => 'Tour Leg',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'value',
			'show_column' => 1,
			'show_column_weight' => 1000,
			'allow_quickedit' => 0,
			'allow_bulkedit' => 0,
			'placeholder' => '',
		),
		array (
			'key' => 'field_595c334dc1d4c',
			'label' => 'Start date',
			'name' => 'wpdtrt_tourdates_acf_tour_category_start_date',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_595c382ec7af6',
						'operator' => '!=',
						'value' => 'region',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'Y-n-j 00:01:00',
			'first_day' => 1,
			'show_column' => 0,
			'show_column_weight' => 1000,
			'allow_quickedit' => 0,
			'allow_bulkedit' => 0,
		),
		array (
			'key' => 'field_595c34a1dd4db',
			'label' => 'End date',
			'name' => 'wpdtrt_tourdates_acf_tour_category_end_date',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_595c382ec7af6',
						'operator' => '!=',
						'value' => 'region',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'Y-n-j 00:01:00',
			'first_day' => 1,
			'show_column' => 0,
			'show_column_weight' => 1000,
			'allow_quickedit' => 0,
			'allow_bulkedit' => 0,
		),
		array (
			'key' => 'field_595d6556e6b3d',
			'label' => 'First visit on tour',
			'name' => 'wpdtrt_tourdates_acf_tour_category_first_visit',
			'type' => 'true_false',
			'instructions' => 'Used in country traversal counts.',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_595c382ec7af6',
						'operator' => '==',
						'value' => 'tour_leg',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'First visit to this place on this tour',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
			'show_column' => 0,
			'show_column_weight' => 1000,
			'allow_quickedit' => 0,
			'allow_bulkedit' => 0,
		),
		array (
			'key' => 'field_595cd60e29d95',
			'label' => 'Number of unique tour legs',
			'name' => 'wpdtrt_tourdates_acf_tour_category_leg_count',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_595c382ec7af6',
						'operator' => '==',
						'value' => 'tour',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 1,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => '',
			'show_column' => 0,
			'show_column_weight' => 1000,
			'allow_quickedit' => 0,
			'allow_bulkedit' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'tours',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
