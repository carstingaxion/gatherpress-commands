<?php
/**
 * Plugin Name:       Gatherpress commands
 * Description:       Experiments for GatherPress using the WordPress Commands Palette API.
 * Version:           0.1.0-alpha
 * Requires at least: 6.5-RC2
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gatherpress-commands
 *
 * @package           create-block
 */

namespace GatherPressCommands;

use GatherPress\Core\Event;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Start the engines.
 *
 * @return void
 */
function bootstrap(): void {
	add_action( 'init', __NAMESPACE__ . '\\register_assets', 1 );

	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_assets' );
}
bootstrap();


/**
 * Get backend-only editor assets.
 *
 * @return string[]
 */
function get_editor_assets(): array {
	return [
	// 'commands',
	];
}


/**
 * 
 *
 * @return void
 */
function register_assets(): void {

	\array_map(
		__NAMESPACE__ . '\\register_asset',
		\array_merge(
			get_editor_assets(),
			[
				'commands',
			]
		)
	);
}

/**
 * Enqueue all scripts.
 *
 * @return void
 */
function enqueue_assets(): void {
	\array_map(
		__NAMESPACE__ . '\\enqueue_asset',
		// get_editor_assets()
		[
			'commands',
		]
	);
}

/**
 * Enqueue a script.
 *
 * @param  string $asset Slug of the block to load the frontend scripts for.
 *
 * @return void
 */
function enqueue_asset( string $asset ): void {
	wp_enqueue_script( "gatherpress-commands--$asset" );
	// wp_enqueue_style( "gatherpress-commands--$asset" );
}


/**
 * Register a new script and sets translated strings for the script.
 *
 * @throws \Error If build-files doesn't exist errors out in local environments and writes to error_log otherwise.
 *
 * @param  string $asset Slug of the block to register scripts and translations for.
 *
 * @return void
 */
function register_asset( string $asset ): void {

	$dir = __DIR__;

	$script_asset_path = "$dir/build/$asset/$asset.asset.php";

	
	if ( ! \file_exists( $script_asset_path ) ) {
		$error_message = "You need to run `npm start` or `npm run build` for the '$asset' block-asset first.";
		if ( \in_array( wp_get_environment_type(), [ 'local', 'development' ], true ) ) {
			throw new \Error( esc_html( $error_message ) );
		} else {
			// Should write to the \error_log( $error_message ); if possible.
			return;
		}
	}

	$index_js     = "build/$asset/$asset.js";
	$script_asset = require $script_asset_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	\wp_register_script(
		"gatherpress-commands--$asset",
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	$index_css = "build/$asset/$asset.css";
	\wp_register_style(
		"gatherpress-commands--$asset",
		plugins_url( $index_css, __FILE__ ),
		[ 'wp-block-post-date','global-styles' ],
		time(),
		'screen'
	);
	wp_set_script_translations(
		"gatherpress-commands--$asset",
		'gatherpress',
		"$dir/languages"
	);
}
