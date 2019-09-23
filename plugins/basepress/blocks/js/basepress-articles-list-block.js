(function () {

	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.editor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var RangeControl = wp.components.RangeControl;
	var Spinner =wp.components.Spinner;
	var Fragment = wp.element.Fragment;
	var ServerSideRender = wp.components.ServerSideRender;
	var i18n = wp.i18n;
	var apiFetch = wp.apiFetch;
	var Component = wp.element.Component;

	var defaulPostCount = 5;

	var products = [];
	var sections = [];

	const iconEl = el('svg', { width: 24, height: 24,viewBox:"0 0 24 24", xmlns:"http://www.w3.org/2000/svg" },
		el('path', { d: "M 13.65 1 L 17.756 1 L 17.756 1 L 17.756 5.008 L 15.784 3.089 L 13.65 5.008 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 Z  M 2.768 22.951 C 1.463 22.951 1.05 22.221 1.05 20.911 L 1.05 3.089 C 1.05 1.578 1.428 1.049 2.768 1.049 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 Z  M 8 7 L 17.756 7 L 17.756 9 L 8 9 L 8 7 L 8 7 L 8 7 L 8 7 L 8 7 L 8 7 L 8 7 L 8 7 Z  M 5 7 L 7 7 L 7 9 L 5 9 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 L 5 7 Z  M 5 11 L 7 11 L 7 13 L 5 13 L 5 11 L 5 11 L 5 11 L 5 11 L 5 11 L 5 11 Z  M 5 15 L 7 15 L 7 17 L 5 17 L 5 15 L 5 15 L 5 15 L 5 15 L 5 15 Z  M 5 19 L 7 19 L 7 21 L 5 21 L 5 19 L 5 19 L 5 19 L 5 19 Z  M 8 11 L 17.756 11 L 17.756 13 L 8 13 L 8 11 L 8 11 L 8 11 Z  M 8 14.948 L 17.756 14.948 L 17.756 16.948 L 8 16.948 L 8 14.948 L 8 14.948 Z  M 13.65 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 C 1.082 0 0 1.031 0 2.3 L 0 21.7 C 0 22.969 1.082 24 2.415 24 L 18.585 24 C 19.918 24 21 22.969 21 21.7 L 21 2.3 C 21 1.031 19.918 0 18.585 0 L 17.756 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 Z  M 8 19 L 17.756 19 L 17.756 21 L 8 21 L 8 19 Z" } )
	);

	/**
	 * Products and Section dropdown class
	 */
	class Categories extends Component {

		constructor(){
			super( ...arguments );

			this.state = {
				products: [],
				sections: [],
				product: '',
				section: '',
			}
		}

		componentDidMount(){

			this.isStillMounted = true;

			if( ! products.length ){
				this.fetchRequest = apiFetch( {
					path: `/basepress_kb/v1/kb_categories`,
				} ).then(
					( data ) => {
						if( this.isStillMounted ){
							this.setState( { products: data.products, sections: data.sections } );
							products = data.products;
							sections = data.sections;
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
				this.setState( { products: products, sections: sections } );
			}
		}

		componentWillUnmount() {
			this.isStillMounted = false;
		}

		render() {
			const { products } = this.state;
			const { sections } = this.state;

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
					),
					el(	SelectControl,
						{
							label: i18n.__( 'Section', 'basepress' ),
							value: this.props.section,
							onChange: this.props.onChangeSection,
							options: this.props.product ? sections[this.props.product] : sections[0],
						}
					)
				)
			);
		}
	}


/**
 * Articles block type
 */


	registerBlockType( 'basepress-kb/articles-list-block', {
		title: i18n.__( 'KB Articles List', 'basepress' ),

		description: i18n.__( 'Adds a list of articles from the Knowledge Base', 'basepress' ),

		icon: iconEl,

		category: 'basepress-kb-block-cat',

		attributes: {
			product: {
				type: 'string',
				default: 0
			},
			section: {
				type: 'string',
				default: 0
			},
			orderby: {
				type: 'string',
				default: 'custom'
			},
			order: {
				type: 'string',
				default: 'asc'
			},
			count: {
				type: 'number',
				default: defaulPostCount
			}
		},

		edit: function( props ) {

			var orderby = props.attributes.orderby;
			var order = props.attributes.order;
			var count = props.attributes.count;

			var onChangeProduct = function( newProduct ){
				props.setAttributes( { product: newProduct } );
				props.setAttributes( { section: '' } );
			}

			var onChangeSection = function( newSection ){
				props.setAttributes( { section: newSection } );
			}

			var onChangeOrderBy = function( newOrderBy ){
				props.setAttributes( { orderby: newOrderBy } );
			}

			var onChangeOrder = function( newOrder ){
				props.setAttributes( { order: newOrder } );
			}

			var onChangeCount = function( newCount ){
				props.setAttributes( { count: newCount } );
			}

			return (
				el( Fragment, null,
					el(	InspectorControls, null,
						el( PanelBody, { title: i18n.__( 'KB Articles List settings', 'basepress' ) },
							el( Categories, {
								product: props.attributes.product,
								section: props.attributes.section,
								onChangeProduct: onChangeProduct,
								onChangeSection: onChangeSection
							}, '' ),
							el( SelectControl,
								{
									label: i18n.__( 'Order by', 'basepress' ),
									value: orderby,
									onChange: onChangeOrderBy,
									options: [
										{ label: i18n.__( 'Custom', 'basepress' ), value: 'custom' },
										{ label: i18n.__( 'Title', 'basepress' ), value: 'title' },
										{ label: i18n.__( 'Date', 'basepress' ), value: 'date' },
										{ label: i18n.__( 'Views', 'basepress' ), value: 'views' },
										{ label: i18n.__( 'Score', 'basepress' ), value: 'score' }
									],
								}
							),
							el( SelectControl,
								{
									label: i18n.__( 'Order', 'basepress' ),
									value: order,
									onChange: onChangeOrder,
									options: [
										{ label: i18n.__( 'Ascending', 'basepress' ), value: 'asc' },
										{ label: i18n.__( 'Descending', 'basepress' ), value: 'desc' }
									]
								}
							),
							el( RangeControl,
								{
									label: i18n.__( 'Number of articles', 'basepress' ),
									value: count,
									min: 1,
									max: 100,
									onChange: onChangeCount
								}
							)
						)
					),
				el( ServerSideRender, {
					block: "basepress-kb/articles-list-block",
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