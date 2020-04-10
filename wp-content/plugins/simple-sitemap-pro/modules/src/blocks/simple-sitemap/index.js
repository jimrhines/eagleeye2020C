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

import { SelectCPT } from '../_components/select-cpt';
import { SitemapCheckboxControl } from '../_components/checkbox';
import { ColorDropdown } from '../_components/color-picker.js';

import Select from 'react-select';

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
	'wpgoplugins/simple-sitemap-block',
	{
		title: 'Simple Sitemap',
		icon: 'editor-ul',
		category: 'simple-sitemap',
		attributes: {
			max_width: {
				type: 'string',
				default: '',
			},
			responsive_breakpoint: {
				type: 'string',
				default: '500px',
			},
			sitemap_container_margin: {
				type: 'string',
				default: '1em 0 0 0',
			},
			sitemap_item_line_height: {
				type: 'string',
				default: '',
			},
			render_tab: {
				type: 'boolean',
				default: false,
			},
			show_excerpt: {
				type: 'boolean',
				default: false,
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
			tab_header_bg: {
				type: 'string',
				default: '#de5737'
			},
			tab_color: {
				type: 'string',
				default: '#ffffff'
			},
			post_type_label_padding: {
				type: 'string',
				default: '10px 20px'
			},
			exclude: {
				type: 'string'
			},
			include: {
				type: 'string'
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
			horizontal: {
				type: 'boolean',
				default: false
			},
			page_depth: {
				type: 'number',
				default: 0
			},
			block_post_types: {
				type: 'string',
				default: '[{ "value": "page", "label": "Pages" }]'
			},
			gutenberg_block: {
				type: 'boolean',
				default: true,
			},
			show_label: {
				type: 'boolean',
				default: true
			},
			links: {
				type: 'boolean',
				default: true
			}
		},
		edit: props => {
			const { attributes: { show_label, links, horizontal, visibility, page_depth, nofollow, image, list_icon, max_width, responsive_breakpoint, sitemap_container_margin, sitemap_item_line_height, tab_color, tab_header_bg, post_type_label_padding, post_type_label_font_size, render_tab, show_excerpt, block_post_types, exclude, include, order, orderby }, className, setAttributes, isSelected, attributes } = props;

			function updateTabBGColor(newCol) {
				setAttributes({ tab_header_bg: newCol.hex });
			}

			function updateTabColor(newCol) {
				setAttributes({ tab_color: newCol.hex });
			}

			function updateToggleTabs(isChecked) {
				setAttributes({ render_tab: isChecked });
			}

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

			function updateHorizontal(isChecked) {
				setAttributes({ horizontal: isChecked });
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
							<label style={{ marginBottom: '-14px' }} class="components-base-control__label" >Select post types to display</label>
						</PanelRow>
						<PanelRow className="simple-sitemap">
							<SelectCPT setAttributes={setAttributes} block_post_types={block_post_types} />
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
						<PanelRow className="simple-sitemap">
							<TextControl
								label="Exclude posts"
								help="Enter comma separated list of post ID's"
								value={exclude}
								onChange={(value) => { setAttributes({ exclude: value }); }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap mb20">
							<TextControl
								label="Include posts"
								help="Enter comma separated list of post ID's"
								value={include}
								onChange={(value) => { setAttributes({ include: value }); }}
							/>
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={show_label} label="Show post type label" updateCheckbox={updateShowLabel} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={visibility} label="Display private posts" updateCheckbox={updateVisibility} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={horizontal} label="Enable horizontal sitemap" updateCheckbox={updateHorizontal} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={nofollow} label="Nofollow links" updateCheckbox={updateNofollow} />
						</PanelRow>
						<PanelRow className="simple-sitemap general-chk">
							<SitemapCheckboxControl value={links} label="Enable sitemap links" updateCheckbox={updateLinks} />
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
					<PanelBody title={__('Tab Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<SitemapCheckboxControl value={render_tab} label="Enable tabs" updateCheckbox={updateToggleTabs} />
						</PanelRow>
						{render_tab && <Fragment>
							<PanelRow className="tab-colors simple-sitemap">
								<Fragment>
									<h4 style={{ marginBottom: '10px' }}>Tab colors</h4>
									<ColorDropdown label="Active tab background" color={tab_header_bg} updateColor={updateTabBGColor} />
									<ColorDropdown label="Active tab text" color={tab_color} updateColor={updateTabColor} />
								</Fragment>
							</PanelRow>
							<PanelRow className="simple-sitemap">
								<TextControl
									label="Tab header padding"
									placeholder="e.g. 10px 40px"
									value={post_type_label_padding}
									onChange={(value) => { setAttributes({ post_type_label_padding: value }); }}
								/>
							</PanelRow>
							<PanelRow className="simple-sitemap">
								<TextControl
									label="Responsive breakpoint"
									placeholder="e.g. 500px"
									help="Width that responsive styles are enabled"
									value={responsive_breakpoint}
									onChange={(value) => { setAttributes({ responsive_breakpoint: value }); }}
								/>
							</PanelRow>
							<PanelRow className="simple-sitemap">
								<TextControl
									label="Maximum width"
									placeholder="e.g. 500px"
									help="Leave blank for no max. width"
									value={max_width}
									onChange={(value) => { setAttributes({ max_width: value }); }}
								/>
							</PanelRow>
						</Fragment>}
					</PanelBody>
					<PanelBody title={__('Page Settings', 'simple-sitemap')} initialOpen={false}>
						<PanelRow className="simple-sitemap">
							<p>Affects sitemap pages only.</p>
						</PanelRow>
						<PanelRow className="simple-sitemap">
							<TextControl
								type="number"
								label="Page indentation"
								min="0"
								max="5"
								help="Leave at zero for auto-depth"
								value={page_depth}
								onChange={(value) => { setAttributes({ page_depth: parseInt(value) }); }}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>,
				<ServerSideRender
					block="wpgoplugins/simple-sitemap-block"
					attributes={attributes}
				/>
			];
		},
		save: function () {
			return null;
		}
	}
);