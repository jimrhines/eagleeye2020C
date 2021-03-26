<?php

namespace WPGO_Plugins\Simple_Sitemap;

/*
 *      Class for the [simple-sitemap-menu] shortcode
 */

class WPGO_Simple_Sitemap_Menu_Shortcode
{

    /* Main class constructor. */
    public function __construct()
    {

        add_shortcode('simple-sitemap-menu', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Menu_Shortcode', 'render_menu_sitemap' ) );
        add_shortcode('ssm', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Menu_Shortcode', 'render_menu_sitemap' ) );
    }

    public function render_menu_sitemap($attr)
    {

        /* Get attributes from the shortcode. */
        $args = shortcode_atts(array(
            'menu' => '',
            'container' => false,
            'menu_class' => 'simple-sitemap-nav-menu',
            'horizontal_separator' => ', ',
            'list_icon' => 'true',
            'container_class' => '',
            'label' => '',
            'exclude_menu_ids' => '',
            'include_menu_ids' => '',
        ), $attr);

        $label = !empty($args['label']) ? '<h3>' . $args['label'] . '</h3>' : '';

        // ******************
        // ** OUTPUT START **
        // ******************

        // Start output caching (so that existing content in the [simple-sitemap] post doesn't get shoved to the bottom of the post
        ob_start();

        //$ids = "6181, 8664";
        $css = '';
        $unique_menu_id = 'ssm_' . uniqid(); // ssm_ >> simple sitemap menu

        if (!empty($args['exclude_menu_ids'])) {
            $idsArr = explode(",", $args['exclude_menu_ids']);
            $numItems = count($idsArr);
            $i = 0;
            foreach ($idsArr as $id) {
                if (++$i === $numItems) {
                    $css .= '#' . $unique_menu_id . ' li#menu-item-' . trim($id);
                } else {
                    $css .= '#' . $unique_menu_id . ' li#menu-item-' . trim($id) . ', ';
                }
            }
            $css .= ' { display: none; }';
        } else if (!empty($args['include_menu_ids'])) {
            $idsArr = explode(",", $args['include_menu_ids']);
            $numItems = count($idsArr);
						$i = 0;
						$css = '#' . $unique_menu_id . ' li { display: none; }';
            foreach ($idsArr as $id) {
                if (++$i === $numItems) {
                    $css .= '#' . $unique_menu_id . ' li#menu-item-' . trim($id);
                } else {
                    $css .= '#' . $unique_menu_id . ' li#menu-item-' . trim($id) . ', ';
                }
            }
            $css .= ' { display: list-item; }';
        }

        $container_format_class = ($args['list_icon'] == "true") ? '' : ' hide-icon';
        $container_classes = 'simple-sitemap-container simple-sitemap-menu' . $container_format_class;
        echo '<div id="' . $unique_menu_id . '" class="' . esc_attr($container_classes) . '">';
        echo "<style>" . $css . "</style>";
        echo $label;
        $menu_html = wp_nav_menu(array(
            'menu' => $args['menu'],
            'container' => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class' => $args['menu_class'],
            'echo' => false,
        ));
        echo $menu_html;
        echo '</div>'; // .simple-sitemap-container
        // @TODO check we still need this
        echo '<br style="clear: both;">'; // make sure content after the sitemap is rendered properly if taken out

        $sitemap = ob_get_contents();
        ob_end_clean();

        // ****************
        // ** OUTPUT END **
        // ****************

        return $sitemap;
    }

}
