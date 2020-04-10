// BLOCK DEPENDENCIES

// Import chart components/objects
//import attributes from './components/attributes';
//import BarChart from './components/bar-chart';
//import InspectorPanel from './components/inspector-panel';
//import IsSelected from './components/is-selected';
//import BlockAlignToolbar from '../_shared-components/block-align-toolbar';

// Import libraries and functionality
//import classnames from 'classnames';

// Import styles and media assets
//import customIcon from './components/icon';
//import './styles/style.scss';
//import './styles/editor.scss';

import { SelectCptTaxonomy } from '../_components/select-cpt-taxonomy';
import { SitemapCheckboxControl } from '../_components/checkbox';
//import { ColorDropdown } from '../_components/color-picker.js';

//import Select from 'react-select';

//  Import core block libraries
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const {
	PanelBody,
	PanelRow,
	ServerSideRender,
	TextControl,
	RadioControl,
	SelectControl,
	ColorPicker
} = wp.components;
const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;

/**
 * Register block
 */
export default registerBlockType(
	'wpgoplugins/simple-sitemap-group-block',
	{
		title: 'Simple Sitemap Group',
		icon: 'networking',
		category: 'simple-sitemap',
		attributes: {
			sitemap_container_margin: {
				type: 'string',
				default: '1em 0 0 0',
			},
			sitemap_item_line_height: {
				type: 'string',
				default: '',
			},
			show_excerpt: {
				type: 'boolean',
				default: false,
			},
			show_label: {
				type: 'boolean',
				default: true,
			},
			links: {
				type: 'boolean',
				default: true,
			},
			nofollow: {
				type: 'boolean',
				default: false,
			},
			image: {
				type: 'boolean',
				default: false,
			},
			list_icon: {
				type: 'boolean',
				default: true,
			},
			post_type_label_font_size: {
				type: 'string',
				default: '1.2em'
			},
			post_type_label_padding: {
				type: 'string',
				default: '10px 20px'
			},
			include_terms: {
				type: 'string',
				default: ''
			},
			exclude_terms: {
				type: 'string',
				default: ''
			},
			orderby: {
				type: 'string',
				default: 'title'
			},
			order: {
				type: 'string',
				default: 'asc'
			},
			visibility: {
				type: 'boolean',
				default: true
			},
			block_post_type: {
				type: 'string',
				default: 'post',
			},
			block_taxonomy: {
				type: 'string',
				default: 'category',
			},
			gutenberg_block: {
				type: 'boolean',
				default: true,
			}
		},
		edit: props => {
			const { attributes: { visibility, nofollow, image, list_icon, max_width, responsive_breakpoint, sitemap_container_margin, sitemap_item_line_height, post_type_label_padding, post_type_label_font_size, show_excerpt, show_label, links, block_post_type, block_taxonomy, exclude, include, order, orderby, exclude_terms, include_terms }, className, setAttributes, isSelected, attributes } = props;

			function updateShowExcerpt(isChecked) {
				setAttributes({ show_excerpt: isChecked });
			}

			function updateShowFeaturedImages(isChecked) {
				setAttributes({ image: isChecked });
			}

			function updateShowListIcons(isChecked) {
				setAttributes({ list_icon: isChecked });
			}

			function updateNofollow(isChecked) {
				setAttributes({ nofollow: isChecked });
			}

			function updateVisibility(isChecked) {
				setAttributes({ visibility: isChecked });
			}

			function updateShowLabel(isChecked) {
				setAttributes({ show_label: isChecked });
			}

			function updateLinks(isChecked) {
				setAttributes({ links: isChecked });
			}

			return [
				<InspectorControls>
					<PanelBody title={__('General Settings', 'simple-sitemap')}>
						<PanelRow className="simple-sitemap">
							<label style={{ marginBottom: '-12px', maxWidth: '100%' }} class="components-base-control__label" >Select post type and taxonomy</label>
						</PanelRow>
						<PanelRow className="simple-sitemap">
							<SelectCptTaxonomy setAttributes={setAttributes} multi={false} block_post_type={block_post_type} block_taxonomy={block_taxonomy} />
						</PanelRow>
						<PanelRow className="simple-sitemap order-label">
						<label style={{ marginBottom: '-12px', maxWidth: '100%' }} class="components-base-control__label" >Post ordering</label>
						</PanelRow>
						<PanelRow className="simple-sitemap order">
							<SelectControl
								label="Orderby"
								value={orderby}
								options={[
									{ label: 'Title', value: 'title' },
									{ label: 'Date', value: 'date' },
									{ label: 'ID', value: 'ID' },
									{ label: 'Author', value: 'author' },
									{ label: 'Name', value: 'name' },
									{ label: 'Modified', value: 'modified' }
								]}
								onChange={(value) => { setAttributes({ orderby: value }) }}
							/>
							<SelectControl
								label="Order"
								value={order}
								options={[
									{ label: 'Ascending', value: 'asc' },
									{ label: 'Descending', value: 'desc' }
								]}
								onChange={(value) => { setAttributes({ order: value }) }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap label-wide">
							<TextControl
								label="Exclude taxonomy terms"
								help="Comma separated list of terms"
								value={exclude_terms}
								onChange={(value) => { setAttributes({ exclude_terms: value }); }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap label-wide mb20">
							<TextControl
								label="Include taxonomy terms"
								help="Comma separated list of terms"
								value={include_terms}
								onChange={(value) => { setAttributes({ include_terms: value }); }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={show_label} label="Display post type label" updateCheckbox={updateShowLabel} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={nofollow} label="Nofollow links" updateCheckbox={updateNofollow} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={visibility} label="Display private posts" updateCheckbox={updateVisibility} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={links} label="Display sitemap links" updateCheckbox={updateLinks} />
						</PanelRow>
					</PanelBody>
					<PanelBody title={__('Heading Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<TextControl
								label="Font size"
								placeholder="e.g. 1em or 12px"
								help="Leave blank to use theme styles"
								value={post_type_label_font_size}
								onChange={(value) => { setAttributes({ post_type_label_font_size: value }); }}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title={__('Container Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<TextControl
								label="Sitemap margin"
								placeholder="e.g. 0 0 0 2em"
								help="Leave blank to use defaults"
								value={sitemap_container_margin}
								onChange={(value) => { setAttributes({ sitemap_container_margin: value }); }}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title={__('Sitemap Item Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<TextControl
								label="Line height"
								placeholder="e.g. 1em or 12px"
								help="Leave blank to use theme styles"
								value={sitemap_item_line_height}
								onChange={(value) => { setAttributes({ sitemap_item_line_height: value }); }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap">
							<SitemapCheckboxControl value={list_icon} label="Display list icons" updateCheckbox={updateShowListIcons} />
						</PanelRow>
					</PanelBody>
					<PanelBody title={__('Excerpt Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<SitemapCheckboxControl value={show_excerpt} label="Display post excerpt" updateCheckbox={updateShowExcerpt} />
						</PanelRow>
					</PanelBody>
					<PanelBody title={__('Featured Image Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<SitemapCheckboxControl value={image} label="Display featured images" updateCheckbox={updateShowFeaturedImages} />
						</PanelRow>
					</PanelBody>
				</InspectorControls>,
				<ServerSideRender
					block="wpgoplugins/simple-sitemap-group-block"
					attributes={attributes}
				/>
			];
		},
		save: function () {
			return null;
		}
	}
);