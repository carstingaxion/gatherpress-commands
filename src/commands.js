/**
 * WordPress dependencies
 */
import { store as commandsStore } from '@wordpress/commands';
import { dispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
// import { settings, comment, button } from '@wordpress/icons';

import { plus, mapMarker, calendar } from '@wordpress/icons';



/**
 * Internal dependencies
 */
const GPED = 'gatherpress-commands';
const GPED_CLASS_NAME   = 'gp-commands';


dispatch( commandsStore ).registerCommand( {
	name: 'gatherpress/add-new-venue',
	label: __( 'Add new venue' ),
	// icon: plus,
	// icon: 'location',
	icon: mapMarker,
	callback: () => {
		document.location.href = 'post-new.php?post_type=gatherpress_venue';
	},
} );

dispatch( commandsStore ).registerCommand( {
	name: 'gatherpress/add-new-event',
	label: __( 'Add new event' ),
	// icon: 'nametag',
	icon: calendar,
	callback: () => {
		document.location.href = 'post-new.php?post_type=gatherpress_event';
	},
} );