(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function () {
        $('#league-recent-users').DataTable( {
            columnDefs: [{
                targets: [ 0 ],
                orderData: [ 0, 1 ]
            },{
                targets: [ 1 ],
                orderData: [ 1, 0 ]
            },{
                targets: [ 2 ],
                orderData: [ 2, 0 ]
            } ]
        } );
        $('#league-duels-answered').DataTable( {
            columnDefs: [{
                targets: [ 2 ],
                orderData: [ 0, 1 ]
            } ]
        } );

    });

})( jQuery );
