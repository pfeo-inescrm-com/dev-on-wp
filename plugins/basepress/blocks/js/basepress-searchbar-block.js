(function () {
	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.editor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var Fragment = wp.element.Fragment;
	var ServerSideRender = wp.components.ServerSideRender;
	var i18n = wp.i18n;
	var Component = wp.element.Component;
	var Spinner =wp.components.Spinner;
	var apiFetch = wp.apiFetch;

	var products = [];
	var styleCss = '';

	const iconEl = el('svg', { width: 24, height: 24,viewBox:"0 0 24 24", xmlns:"http://www.w3.org/2000/svg" },
		el('path', { d: "M 13.65 1 Q 19.396 1 17.756 1 L 17.756 1 L 17.756 5.008 L 15.784 3.089 L 13.65 5.008 L 13.65 1 Z  M 13.65 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 C 1.082 0 0 1.031 0 2.3 L 0 21.7 C 0 22.969 1.082 24 2.415 24 L 18.585 24 C 19.918 24 21 22.969 21 21.7 L 21 2.3 C 21 1.031 19.918 0 18.585 0 L 17.756 0 L 13.65 0 Z  M 2.768 22.951 C 1.463 22.951 1.05 22.221 1.05 20.911 L 1.05 3.089 C 1.05 1.578 1.428 1.049 2.768 1.049 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 Z  M 8.282 7.868 C 9.496 6.686 11.492 6.668 12.736 7.828 C 13.98 8.987 14.004 10.888 12.79 12.07 L 12.323 11.635 C 13.286 10.698 13.267 9.191 12.28 8.271 C 11.294 7.352 9.711 7.366 8.748 8.303 L 8.282 7.868 L 8.282 7.868 L 8.282 7.868 Z  M 12.774 15.007 L 12.813 14.99 C 13.582 14.661 14.487 14.988 14.833 15.721 L 16.826 19.944 C 17.172 20.677 16.828 21.539 16.059 21.868 L 16.019 21.885 C 15.25 22.214 14.345 21.886 14 21.154 L 12.006 16.93 C 11.661 16.198 12.005 15.336 12.774 15.007 L 12.774 15.007 Z  M 6.402 10.004 C 6.402 7.817 8.261 6.042 10.551 6.042 C 12.841 6.042 14.7 7.817 14.7 10.004 C 14.7 12.19 12.841 13.965 10.551 13.965 C 8.261 13.965 6.402 12.19 6.402 10.004 L 6.402 10.004 L 6.402 10.004 L 6.402 10.004 L 6.402 10.004 Z  M 5.318 10.004 C 5.318 7.246 7.663 5.008 10.551 5.008 C 13.439 5.008 15.784 7.246 15.784 10.004 C 15.784 12.761 13.439 15 10.551 15 C 7.663 15 5.318 12.761 5.318 10.004 L 5.318 10.004 L 5.318 10.004 L 5.318 10.004 L 5.318 10.004 Z" } )
	);

	/**
	 * Products dropdown class
	 */
	class Products extends Component {

		constructor(){
			super( ...arguments );

			this.state = {
				products: [],
				product: '',
			}
		}

		componentDidMount(){

			this.isStillMounted = true;

			if( ! products.length ){
				apiFetch( {
					path: `/basepress_kb/v1/kb_categories/?products=true`,
				} ).then(
					( data ) => {
						if( this.isStillMounted ){
							this.setState( { products: data.products } );
							products = data.products;
						}
					}
				).catch(
					() => {
						if( this.isStillMounted ){
							this.setState( { products: [] } );
						}
					}
				);
			}
			else{
				this.setState( { products: products } );
			}
		}

		componentWillUnmount() {
			this.isStillMounted = false;
		}

		render() {
			const { products } = this.state;

			if( ! products.length ){
				return (
					el( Fragment, null,
						el( 'div', {
								style:{
									width: '30px',
									height: '40px',
								}
							},
							el(	Spinner )
						)
					)
				)
			}

			return (
				el( Fragment, null,
					el(	SelectControl,
						{
							label: i18n.__( 'Knowledge Base', 'basepress' ),
							value: this.props.product,
							onChange: this.props.onChangeProduct,
							options: products,
						}
					)
				)
			);
		}
	}

	/**
	 *
	 */
	registerBlockType( 'basepress-kb/searchbar-block', {
		title: i18n.__( 'KB Search Bar', 'basepress' ),

		description: i18n.__( 'Adds a search bar to find articles in your knowledge base', 'basepress' ),

		icon: iconEl,

		category: 'basepress-kb-block-cat',

		attributes: {
			product: {
				type: 'string',
				default: 0
			},
			width: {
				type: 'string',
				default: ''
			}
		},

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
						loadBlockCss( styleCss );
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

			var width = props.attributes.width;

			var onChangeProduct = function( newProduct ){
				props.setAttributes( { product: newProduct } );
			}

			var onChangeWidth = function( newWidth ){
				props.setAttributes( { width: newWidth } );
			}

			return (
				el( Fragment, null,
					el(	InspectorControls, null,
						el( PanelBody, { title: i18n.__( 'KB Search bar settings', 'basepress' ) },
							el(	Products,
								{
									product: props.attributes.product,
									onChangeProduct: onChangeProduct,
								}
							),
							el( TextControl,
								{
									label: i18n.__( 'Width', 'basepress' ),
									value: width,
									onChange: onChangeWidth
								}
							)
						)
					),
					el( ServerSideRender, {
						block: "basepress-kb/searchbar-block",
						attributes:  props.attributes
					})
				)
			)
		},

		save: function() {
			return null;
		},
	} );
}());