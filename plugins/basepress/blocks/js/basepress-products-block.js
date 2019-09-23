(function () {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var ServerSideRender = wp.components.ServerSideRender;
	var apiFetch = wp.apiFetch;
	var i18n = wp.i18n;
	var styleCss = '';

	const iconEl = el('svg', { width: 24, height: 24,viewBox:"0 0 24 24", xmlns:"http://www.w3.org/2000/svg" },
		el('path', { d: "M 13.65 1 L 17.756 1 L 17.756 1 L 17.756 5.008 L 15.784 3.089 L 13.65 5.008 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 Z  M 2.768 22.951 C 1.463 22.951 1.05 22.221 1.05 20.911 L 1.05 3.089 C 1.05 1.578 1.428 1.049 2.768 1.049 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 Z  M 5 15 L 11 15 L 11 21 L 5 21 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 Z  M 6 12 L 10 12 L 10 11 L 6 11 L 6 12 L 6 12 L 6 12 L 6 12 Z  M 14 12 L 18 12 L 18 11 L 14 11 L 14 12 L 14 12 L 14 12 Z  M 14 20 L 18 20 L 18 19 L 14 19 L 14 20 L 14 20 Z  M 13 15 L 18.756 15 L 18.756 21 L 13 21 L 13 15 L 13 15 L 13 15 L 13 15 L 13 15 L 13 15 L 13 15 Z  M 13 7 L 18.756 7 L 18.756 13 L 13 13 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 L 13 7 Z  M 5 7 L 11 7 L 11 13 L 5 13 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 Z  M 13.65 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 C 1.082 0 0 1.031 0 2.3 L 0 21.7 C 0 22.969 1.082 24 2.415 24 L 18.585 24 C 19.918 24 21 22.969 21 21.7 L 21 2.3 C 21 1.031 19.918 0 18.585 0 L 17.756 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 Z  M 6 20 L 10 20 L 10 19 L 6 19 L 6 20 Z" } )
	);

	registerBlockType( 'basepress-kb/products-block', {
		title: i18n.__( 'Knowledge Bases List', 'basepress' ),

		description: i18n.__( 'Displays the list of active Knowledge Bases.', 'basepress' ),

		icon: iconEl,

		category: 'basepress-kb-block-cat',

		supports: {
			multiple: false
		},

		edit: function( props ) {

			if( ! styleCss ){
				apiFetch( {
					path: `/basepress_kb/v1/kb_css_url`,
				} ).then(
					( data ) => {
						styleCss = data;
						loadBlockCss( data );
					}
				)
			}

			var loadBlockCss = function( path ) {
				var similar = 0;

				$("link").each(function(e) {
					if ($(this).attr("href") == path) similar++;
				});

				if ( similar == 0 ) {
					$('<link>').attr('rel', 'stylesheet')
						.attr('type', 'text/css')
						.attr('href', path)
						.appendTo('head');
				}
			}

			return (
				el(ServerSideRender, {
					block: "basepress-kb/products-block",
					attributes:  props.attributes
				})
			);
		},

		save: function() {
			return null;
		},
	} );
}());