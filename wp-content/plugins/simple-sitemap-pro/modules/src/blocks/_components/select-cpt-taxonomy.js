import classnames from 'classnames';
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
  SelectControl
} = wp.components;
const { registerBlockType } = wp.blocks;
const { Component, Fragment } = wp.element;

export class SelectCptTaxonomy extends Component {

  constructor(props) {
    super(); // or super(props); ??

    this.state = {
      types: [],
      taxonomies: [],
      taxonomy_select_disabled_status: true,
      taxonomy_select_disabled_help: '',
      wrapperClass: ''
    };
    this.props = props;
  }

  // get post types to populate select box
  componentDidMount() {

    // render dropdowns
    this.fetchCPTs();
    this.fetchTaxonomies(null); // null is important here
  }

  // set the CPT dropdown options
  fetchCPTs() {

    const { block_post_type, block_taxonomy } = this.props;
    const post_type_url = 'simple-sitemap/v1/post-types';

    wp.apiFetch({ path: post_type_url, method: 'GET' }).then(
      (data) => {

        var post_types = [];
        const entries = Object.entries(data);
        for (const [key, value] of entries) {
          const tmp = {
            value: key,
            label: value
          };
          post_types.push(tmp);
        }

        this.setState({
          types: post_types
        });
        return data;
      },
      (err) => {
        return err;
      }
    );
  }

  // set the taxonomy dropdown options
  fetchTaxonomies(newCPT) {

    const { setAttributes, block_post_type, block_taxonomy } = this.props;

    // use the cpt from attribute (component did mount) or from new value when cpt drop down changed
    let current_post_type_arr;
    if (newCPT) {
      current_post_type_arr = newCPT;
    } else {
      current_post_type_arr = block_post_type;
    }

    const taxonomy_url = `simple-sitemap/v1/post-type-taxonomies/${current_post_type_arr}`;

    wp.apiFetch({ path: taxonomy_url, method: 'GET' }).then(
      (data) => {

        let msg = '';
        let disabled_status = false;
        let wrapperClass = '';
        var taxonomies = [];
        let tax_flag = true;

        if (data.length === 0) {
          msg = 'No taxonomies found for this post type';
          disabled_status = true;
          wrapperClass = 'disabled';
          setAttributes({ block_taxonomy: '' });
        } else {
          const entries = Object.entries(data);
          for (const [key, value] of entries) {
            const tmp = {
              value: key,
              label: value
            };
            taxonomies.push(tmp);

            // use attribute value?
            if(tmp.value === block_taxonomy) {
              tax_flag = false;
            }
          }
          // update attribute with first found taxonomy unless current taxonomy attr. is found in taxonomy array

          // only update tax attr. if current value not found in updated taxonomies array in which case just set to first taxonomy in array
          if(tax_flag) {
            setAttributes({ block_taxonomy: taxonomies[0].value });
          }
        }

        this.setState({
          taxonomy_select_disabled_status: disabled_status,
          taxonomy_select_disabled_help: msg,
          taxonomies: taxonomies,
          wrapperClass: wrapperClass
        });
        return data;
      },
      (err) => {
        return err;
      }
    );
  }

  updatePostTypeValues(val) {
    const { setAttributes } = this.props;
    setAttributes({ block_post_type: val });
    this.fetchTaxonomies(val);
  }

  updateTaxonomyValues(val) {
    const { setAttributes } = this.props;
    setAttributes({ block_taxonomy: val });
  }

  render() {
    const { setAttributes, block_post_type, block_taxonomy, multi = true, className } = this.props;

    return (
      <div className={this.state.wrapperClass}>
        <SelectControl
          value={block_post_type}
          options={this.state.types}
          onChange={(val) => this.updatePostTypeValues(val)}
          help={this.state.taxonomy_select_disabled_help}
        />
        <SelectControl
          value={block_taxonomy}
          options={this.state.taxonomies}
          onChange={(val) => this.updateTaxonomyValues(val)}
          disabled={this.state.taxonomy_select_disabled_status}
        />
      </div>
    );
  }
}