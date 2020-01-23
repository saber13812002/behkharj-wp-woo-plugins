<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/public
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Api Keys
     */
    public function keys()
    {

        global $woocommerce;

        global $wpdb;
        $table_name = $wpdb->prefix . "postmeta";
        $query = "SELECT max(cast(meta_value as unsigned)) FROM $table_name WHERE meta_key='_price'";
        $max_price = $wpdb->get_var($query);

        $currency = get_woocommerce_currency();

        $data = array();

        $options = get_option('mstoreapp_options');

        $data['blocks'] = array();
        $id = 0;
        for ($i = 0; $i < 100; $i++) {
            if (isset($options['switch_' . $i]) && $options['switch_' . $i] == 1) {

                $filter_by = isset($options['filter_by_' . $i]) ? $options['filter_by_' . $i] : 'category';
                $link_id = isset($options['link_id_' . $i]) ? $options['link_id_' . $i] : 0;
                $sale_ends = isset($options['sale_ends_' . $i]) ? $options['sale_ends_' . $i] : 0;
                $slides = isset($options['slides_' . $i]) ? $options['slides_' . $i] : array();

                if ($options['block_type_' . $i] === 'product_block' || $options['block_type_' . $i] === 'flash_sale_block') {
                    $products = $this->get_products($filter_by, $link_id);
                } else $products = array();

                $data['blocks'][] = array(
                    'id' => $id,
                    'children' => $slides,
                    'products' => $products,
                    'title' => $options['title_' . $i],
                    'header_align' => $options['header_align_' . $i],
                    'title_color' => $options['title_color_' . $i],
                    'style' => $options['style_' . $i],
                    'banner_shadow' => $options['shadow_' . $i],
                    'padding' => $options['padding_' . $i]['padding-top'] . ' ' . $options['padding_' . $i]['padding-right'] . ' ' . $options['padding_' . $i]['padding-bottom'] . ' ' . $options['padding_' . $i]['padding-left'],
                    'margin' => $options['margin_' . $i]['margin-top'] . ' ' . $options['margin_' . $i]['margin-right'] . ' ' . $options['margin_' . $i]['margin-bottom'] . ' ' . $options['margin_' . $i]['margin-left'],
                    'bg_color' => $options['background_color_' . $i],
                    'sort' => $options['sort_' . $i],
                    'block_type' => $options['block_type_' . $i],
                    'filter_by' => $filter_by,
                    'link_id' => $link_id,
                    'border_radius' => $options['border_radius_' . $i]['width'],
                    'margin_between' => $options['margin_between_' . $i]['width'],
                    'child_width' => $options['child_width_' . $i],
                    'sale_ends' => $sale_ends . ' 23:59'
                );
                $id = $id + 1;
            }
        }

        usort($data['blocks'], function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        $data['pages'] = (array) $options['pages'];

        $data['settings'] = array(
            'max_price' => (int) $max_price,
            'currency' => $currency,
            'show_featured' => (int) $options['show_featured'],
            'show_onsale' => (int) $options['show_onsale'],
            'show_latest' => (int) $options['show_latest'],
            'pull_to_refresh' => (int) $options['pull_to_refresh'],
            'onesignal_app_id' => $options['onesignal_app_id'],
            'google_project_id' => $options['google_project_id'],
            'google_web_client_id' => $options['google_web_client_id'],
            'rate_app_ios_id' => $options['rate_app_ios_id'],
            'rate_app_android_id' => $options['rate_app_android_id'],
            'rate_app_windows_id' => $options['rate_app_windows_id'],
            'share_app_android_link' => $options['share_app_android_link'],
            'share_app_ios_link' => $options['share_app_ios_link'],
            'support_email' => $options['support_email'],
            'enable_product_chat' => (int) $options['enable_product_chat'],
            'enable_home_chat' => (int) $options['enable_home_chat'],
            'whatsapp_number' => $options['whatsapp_number'],
            'app_dir' => $options['app_dir'],
            'switchLocations' => (int) $options['switchLocations'],
            'language' => 'english',
            'product_shadow' => $options['product_shadow'],
            'enable_sold_by' => (int) $options['enable_sold_by'],
            'enable_sold_by_product' => (int) $options['enable_sold_by_product'],
            'enable_vendor_chat' => (int) $options['enable_vendor_chat'],
            'enable_vendor_map' => (int) $options['enable_vendor_map'],
            'enable_wallet' => (int) $options['enable_wallet'],
            'enable_refund' => (int) $options['enable_refund'],
            'switchWpml' => (int) $options['switchWpml'],
            'switchAddons' => (int) $options['switchAddons'],
            'switchRewardPoints' => (int) $options['switchRewardPoints'],
        );

        $data['theme'] = array(
            'header' => 'custom1',
            'tabBar' => 'custom1',
            'button' => $options['button'],
        );

        $data['dimensions'] = array(
            'imageHeight' => $options['imageHeight'],
            'productSliderWidth' => $options['productSliderWidth'],
            'latestPerRow' => $options['latestPerRow'],
            'productsPerRow' => $options['productsPerRow'],
            'searchPerRow' => $options['searchPerRow'],
            'productBorderRadius' => $options['productBorderRadius'],
            'suCatBorderRadius' => $options['suCatBorderRadius'],
            'productPadding' => $options['productPadding']
        );

        $data['featured'] = array();
        if ($data['settings']['show_featured'])
            $data['featured'] = $this->get_products('featured');
        $data['on_sale'] = array();
        if ($data['settings']['show_onsale'])
            $data['on_sale'] = $this->get_products('on_sale');

        $data['categories'] = $this->get_categories();

        //Support for older apps
        $data['max_price'] = (int) $max_price;
        $data['login_nonce'] = wp_create_nonce('woocommerce-login');
        $data['currency'] = get_woocommerce_currency();

        $data['languages'] = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

        // Translation from backend
        //$data['en'] = $options['en']; 

        //$data['ar'] = $options['ar'];

        if (is_user_logged_in()) {

            $user_id = get_current_user_id();

            $data['user'] = wp_get_current_user();
            $data['user']->status = true;
            $data['user']->url = wp_logout_url();
            $data['user']->avatar = get_avatar($data['user']->ID, 128);
            $data['user']->avatar_url = get_avatar_url($data['user']->ID);

            /* Reward Points */
            if (is_plugin_active('woocommerce-points-and-rewards/woocommerce-points-and-rewards.php')) {
                $data['user']->points = WC_Points_Rewards_Manager::get_users_points($user_id);
                $data['user']->points_vlaue = WC_Points_Rewards_Manager::get_users_points_value($user_id);
            }
            /* Reward Points */


            wp_send_json($data);
        }

        $data['status'] = false;

        wp_send_json($data);

        die();
    }

    public function product_attributes()
    {

        $attributes = array();

        $category = $_REQUEST['category'];

        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => $category,
                    'operator' => 'IN',
                )
            ),
            'post_status' => 'publish',
        );

        foreach (wc_get_products($args) as $product) {

            foreach ($product->get_attributes() as $attr_name => $attr) {

                if (array_search($attr_name, array_column($attributes, 'id')) === false)
                    $attributes[] = array(
                        'id' => $attr_name,
                        'name' => wc_attribute_label($attr_name),
                        'terms' => $this->get_attribute_terms($attr_name)
                    );
            }
        }

        wp_send_json($attributes);

        die();
    }

    public static function get_attribute_terms($attr_name)
    {
        $terms = get_terms($attr_name, array(
            "hide_empty" => true,
        ));
        if (is_array($terms))
            return $terms;
        else return array();
    }

    public function test()
    {

        $data = get_post_meta(99, 'onesignal_user_id');
        wp_send_json($data);
    }

    public function get_products($filter_by, $id = 0)
    {

        $tax_query = array();

        if ($filter_by == 'category') {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $id,
            );
        }

        if ($filter_by == 'tag') {
            $tax_query[] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => $id,
            );
        }

        // Filter featured.
        if ($filter_by == 'featured') {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => true === 'IN',
            );
        }

        // Filter by on sale products.
        if ($filter_by == 'on_sale') {
            $on_sale_key = 'post__in';
            $on_sale_ids = wc_get_product_ids_on_sale();

            // Use 0 when there's no on sale products to avoid return all products.
            $on_sale_ids = empty($on_sale_ids) ? array(0) : $on_sale_ids;

            $args[$on_sale_key] = $on_sale_ids;
        }

        $args = array(
            'status' => 'publish'
        );

        $args['post_type'] = array('product', 'product_variation');

        $args['tax_query'] = $tax_query;

        $products = wc_get_products($args);

        $results = array();
        foreach ($products as $i => $product) {
            $results[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku('view'),
                'type' => $product->get_type(),
                'status' => $product->get_status(),
                'permalink'  => $product->get_permalink(),
                'description' => $product->get_description(),
                'short_description' => $product->get_short_description(),
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'stock_status' => $product->get_stock_status(),
                'stock_quantity'     => $product->get_stock_quantity(),
                'on_sale' => $product->is_on_sale('view'),
                'average_rating'        => wc_format_decimal($product->get_average_rating(), 2),
                'rating_count'          => $product->get_rating_count(),
                'related_ids'           => array_map('absint', array_values(wc_get_related_products($product->get_id()))),
                'upsell_ids'            => array_map('absint', $product->get_upsell_ids('view')),
                'cross_sell_ids'        => array_map('absint', $product->get_cross_sell_ids('view')),
                'parent_id'             => $product->get_parent_id('view'),
                'images' => $this->get_images($product),
                'attributes'            => $this->get_attributes($product),
                'default_attributes'    => $this->get_default_attributes($product),
                'variations'            => $this->get_variation_ids($product),
                'meta_data'             => $product->get_meta_data(),
            );
        }

        return $results;
    }

    protected function get_variation_ids($product)
    {
        $variations = array();

        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation || !$variation->exists()) {
                continue;
            }

            $variations[] = $variation->get_id();
        }

        return $variations;
    }

    protected function get_variation_data($product)
    {
        $variations = array();

        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation || !$variation->exists()) {
                continue;
            }

            $variations[] = array(
                'id'                 => $variation->get_id(),
                'permalink'          => $variation->get_permalink(),
                'sku'                => $variation->get_sku(),
                'price'              => $variation->get_price(),
                'regular_price'      => $variation->get_regular_price(),
                'sale_price'         => $variation->get_sale_price(),
                'on_sale'            => $variation->is_on_sale(),
                'purchasable'        => $variation->is_purchasable(),
                'visible'            => $variation->is_visible(),
                'virtual'            => $variation->is_virtual(),
                'downloadable'       => $variation->is_downloadable(),
                'download_limit'     => '' !== $variation->get_download_limit() ? (int) $variation->get_download_limit() : -1,
                'download_expiry'    => '' !== $variation->get_download_expiry() ? (int) $variation->get_download_expiry() : -1,
                'stock_quantity'     => $variation->get_stock_quantity(),
                'in_stock'           => $variation->is_in_stock(),
                'image'              => $this->get_images($variation),
                'attributes'         => $this->get_attributes($variation),
            );
        }

        return $variations;
    }

    protected function get_images($product)
    {
        $images         = array();
        $attachment_ids = array();

        // Add featured image.
        if ($product->get_image_id()) {
            $attachment_ids[] = $product->get_image_id();
        }

        // Add gallery images.
        $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());

        // Build image data.
        foreach ($attachment_ids as $position => $attachment_id) {
            $attachment_post = get_post($attachment_id);
            if (is_null($attachment_post)) {
                continue;
            }

            $attachment = wp_get_attachment_image_src($attachment_id, 'full');
            if (!is_array($attachment)) {
                continue;
            }

            $images[] = array(
                'id'                => (int) $attachment_id,
                'src'               => current($attachment),
                'name'              => get_the_title($attachment_id),
                'alt'               => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
                'position'          => (int) $position,
            );
        }

        // Set a placeholder image if the product has no images set.
        if (empty($images)) {
            $images[] = array(
                'id'                => 0,
                'src'               => wc_placeholder_img_src(),
                'name'              => __('Placeholder', 'woocommerce'),
                'alt'               => __('Placeholder', 'woocommerce'),
                'position'          => 0,
            );
        }

        return $images;
    }

    protected function get_attribute_taxonomy_name($slug, $product)
    {
        $attributes = $product->get_attributes();

        if (!isset($attributes[$slug])) {
            return str_replace('pa_', '', $slug);
        }

        $attribute = $attributes[$slug];

        // Taxonomy attribute name.
        if ($attribute->is_taxonomy()) {
            $taxonomy = $attribute->get_taxonomy_object();
            return $taxonomy->attribute_label;
        }

        // Custom product attribute name.
        return $attribute->get_name();
    }

    /**
     * Get default attributes.
     *
     * @param WC_Product $product Product instance.
     *
     * @return array
     */
    protected function get_default_attributes($product)
    {
        $default = array();

        if ($product->is_type('variable')) {
            foreach (array_filter((array) $product->get_default_attributes(), 'strlen') as $key => $value) {
                if (0 === strpos($key, 'pa_')) {
                    $default[] = array(
                        'id'     => wc_attribute_taxonomy_id_by_name($key),
                        'name'   => $this->get_attribute_taxonomy_name($key, $product),
                        'option' => $value,
                    );
                } else {
                    $default[] = array(
                        'id'     => 0,
                        'name'   => $this->get_attribute_taxonomy_name($key, $product),
                        'option' => $value,
                    );
                }
            }
        }

        return $default;
    }

    /**
     * Get attribute options.
     *
     * @param int   $product_id Product ID.
     * @param array $attribute  Attribute data.
     *
     * @return array
     */
    protected function get_attribute_options($product_id, $attribute)
    {
        if (isset($attribute['is_taxonomy']) && $attribute['is_taxonomy']) {
            return wc_get_product_terms(
                $product_id,
                $attribute['name'],
                array(
                    'fields' => 'names',
                )
            );
        } elseif (isset($attribute['value'])) {
            return array_map('trim', explode('|', $attribute['value']));
        }

        return array();
    }

    /**
     * Get the attributes for a product or product variation.
     *
     * @param WC_Product|WC_Product_Variation $product Product instance.
     *
     * @return array
     */
    protected function get_attributes($product)
    {
        $attributes = array();

        if ($product->is_type('variation')) {
            $_product = wc_get_product($product->get_parent_id());
            foreach ($product->get_variation_attributes() as $attribute_name => $attribute) {
                $name = str_replace('attribute_', '', $attribute_name);

                if (empty($attribute) && '0' !== $attribute) {
                    continue;
                }

                // Taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`.
                if (0 === strpos($attribute_name, 'attribute_pa_')) {
                    $option_term  = get_term_by('slug', $attribute, $name);
                    $attributes[] = array(
                        'id'     => wc_attribute_taxonomy_id_by_name($name),
                        'name'   => $this->get_attribute_taxonomy_name($name, $_product),
                        'option' => $option_term && !is_wp_error($option_term) ? $option_term->name : $attribute,
                    );
                } else {
                    $attributes[] = array(
                        'id'     => 0,
                        'name'   => $this->get_attribute_taxonomy_name($name, $_product),
                        'option' => $attribute,
                    );
                }
            }
        } else {
            foreach ($product->get_attributes() as $attribute) {
                $attributes[] = array(
                    'id'        => $attribute['is_taxonomy'] ? wc_attribute_taxonomy_id_by_name($attribute['name']) : 0,
                    'name'      => $this->get_attribute_taxonomy_name($attribute['name'], $product),
                    'position'  => (int) $attribute['position'],
                    'visible'   => (bool) $attribute['is_visible'],
                    'variation' => (bool) $attribute['is_variation'],
                    'options'   => $this->get_attribute_options($product->get_id(), $attribute),
                );
            }
        }

        return $attributes;
    }

    //TODO:get_categories
    public function get_categories()
    {

        $taxonomy     = 'product_cat';
        $orderby      = 'name';
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';
        $empty        = 0;

        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title'     => $title,
            'hide_empty'   => $empty
        );

        $categories = get_categories($args);

        $data = array();

        foreach ($categories as $key => $value) {

            $image_id = get_term_meta($value->term_id, 'thumbnail_id', true);
            $image = array();
            if ($image_id) {
                $attachment = get_post($image_id);

                $image = array(
                    'id'     => (int) $image_id,
                    'src'    => wp_get_attachment_url($image_id),
                    'name'   => get_the_title($attachment),
                    'alt'    => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                );
            }

            $data[] = array(
                'id' => $value->term_id,
                'name' => $value->name,
                'slug' => $value->slug,
                'description' => $value->description,
                'parent' => $value->parent,
                'count' => $value->count,
                'image' => $image,
            );
        }

        return $data;
    }


    /**
     * AJAX apply coupon on checkout page.
     */
    public static function apply_coupon()
    {

        //check_ajax_referer( 'apply-coupon', 'security' );

        if (!empty($_POST['coupon_code'])) {
            WC()->cart->add_discount(sanitize_text_field($_POST['coupon_code']));
        } else {
            wc_add_notice(WC_Coupon::get_generic_coupon_error(WC_Coupon::E_WC_COUPON_PLEASE_ENTER), 'error');
        }

        wc_print_notices();

        die();
    }

    /**
     * AJAX remove coupon on cart and checkout page.
     */
    public static function remove_coupon()
    {

        //check_ajax_referer( 'remove-coupon', 'security' );

        $coupon = wc_clean($_POST['coupon']);

        if (!isset($coupon) || empty($coupon)) {
            wc_add_notice(__('Sorry there was a problem removing this coupon.', 'woocommerce'), 'error');
        } else {

            WC()->cart->remove_coupon($coupon);

            wc_add_notice(__('Coupon has been removed.', 'woocommerce'));
        }

        wc_print_notices();

        die();
    }

    /**
     * AJAX update shipping method on cart page.
     */
    public function update_shipping_method()
    {

        //check_ajax_referer( 'update-shipping-method', 'security' );

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

        if (isset($_POST['shipping_method']) && is_array($_POST['shipping_method'])) {
            foreach ($_POST['shipping_method'] as $i => $value) {
                $chosen_shipping_methods[$i] = wc_clean($value);
            }
        }

        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);


        $data = WC()->cart;
        WC()->cart->calculate_totals();

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = wc_get_cart_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = wc_get_price_including_tax($_product);
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();
        }

        $data->cart_nonce = wp_create_nonce('woocommerce-cart');

        $data->cart_totals = WC()->cart->get_totals();

        //$data->shipping = WC()->shipping->load_shipping_methods($packages);

        $packages = WC()->shipping->get_packages();
        $first = true;

        $shipping = array();
        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $shipping[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
                'shipping' => $this->get_rates($package)
            );

            $first = false;
        }

        $data->chosen_shipping = WC()->session->get('chosen_shipping_methods');

        $data->shipping = $shipping;


        wp_send_json($data);


        die();
    }

    /**
     * AJAX receive updated cart_totals div.
     */
    public static function get_cart_totals()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        WC()->cart->calculate_totals();

        woocommerce_cart_totals();

        die();
    }

    public function get_rates($package)
    {

        $shipping = array();

        //if($package['rates'])
        foreach ($package['rates'] as $i => $method) {
            $shipping[$i]['id'] = $method->get_id();
            $shipping[$i]['label'] = $method->get_label();
            $shipping[$i]['cost'] = $method->get_cost();
            $shipping[$i]['method_id'] = $method->get_method_id();
            $shipping[$i]['taxes'] = $method->get_taxes();
        }

        return $shipping;
    }

    /**
     * AJAX update order review on checkout.
     */
    public static function update_order_review()
    {
        check_ajax_referer('update-order-review', 'security');

        wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);

        if (WC()->cart->is_empty() && !is_customize_preview() && apply_filters('woocommerce_checkout_update_order_review_expired', true)) {
            self::update_order_review_expired();
        }

        do_action('woocommerce_checkout_update_order_review', isset($_POST['post_data']) ? wp_unslash($_POST['post_data']) : ''); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
        $posted_shipping_methods = isset($_POST['shipping_method']) ? wc_clean(wp_unslash($_POST['shipping_method'])) : array();

        if (is_array($posted_shipping_methods)) {
            foreach ($posted_shipping_methods as $i => $value) {
                $chosen_shipping_methods[$i] = $value;
            }
        }

        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
        WC()->session->set('chosen_payment_method', empty($_POST['payment_method']) ? '' : wc_clean(wp_unslash($_POST['payment_method'])));
        WC()->customer->set_props(
            array(
                'billing_country'   => isset($_POST['billing_country']) ? wc_clean(wp_unslash($_POST['billing_country'])) : null,
                'billing_state'     => isset($_POST['billing_state']) ? wc_clean(wp_unslash($_POST['billing_state'])) : null,
                'billing_postcode'  => isset($_POST['billing_postcode']) ? wc_clean(wp_unslash($_POST['billing_postcode'])) : null,
                'billing_city'      => isset($_POST['billing_city']) ? wc_clean(wp_unslash($_POST['billing_city'])) : null,
                'billing_address_1' => isset($_POST['billing_address']) ? wc_clean(wp_unslash($_POST['billing_address'])) : null,
                'billing_address_2' => isset($_POST['billing_address_2']) ? wc_clean(wp_unslash($_POST['billing_address_2'])) : null,
            )
        );

        if (wc_ship_to_billing_address_only()) {
            WC()->customer->set_props(
                array(
                    'shipping_country'   => isset($_POST['billing_country']) ? wc_clean(wp_unslash($_POST['billing_country'])) : null,
                    'shipping_state'     => isset($_POST['billing_state']) ? wc_clean(wp_unslash($_POST['billing_state'])) : null,
                    'shipping_postcode'  => isset($_POST['billing_postcode']) ? wc_clean(wp_unslash($_POST['billing_postcode'])) : null,
                    'shipping_city'      => isset($_POST['billing_city']) ? wc_clean(wp_unslash($_POST['billing_city'])) : null,
                    'shipping_address_1' => isset($_POST['billing_address']) ? wc_clean(wp_unslash($_POST['billing_address'])) : null,
                    'shipping_address_2' => isset($_POST['billing_address_2']) ? wc_clean(wp_unslash($_POST['billing_address_2'])) : null,
                )
            );
        } else {
            WC()->customer->set_props(
                array(
                    'shipping_country'   => isset($_POST['shipping_country']) ? wc_clean(wp_unslash($_POST['shipping_country'])) : null,
                    'shipping_state'     => isset($_POST['shipping_state']) ? wc_clean(wp_unslash($_POST['shipping_state'])) : null,
                    'shipping_postcode'  => isset($_POST['shipping_postcode']) ? wc_clean(wp_unslash($_POST['shipping_postcode'])) : null,
                    'shipping_city'      => isset($_POST['shipping_city']) ? wc_clean(wp_unslash($_POST['shipping_city'])) : null,
                    'shipping_address_1' => isset($_POST['shipping_address']) ? wc_clean(wp_unslash($_POST['shipping_address'])) : null,
                    'shipping_address_2' => isset($_POST['shipping_address_2']) ? wc_clean(wp_unslash($_POST['shipping_address_2'])) : null,
                )
            );
        }

        if (isset($_POST['has_full_address']) && wc_string_to_bool(wc_clean(wp_unslash($_POST['has_full_address'])))) {
            WC()->customer->set_calculated_shipping(true);
        } else {
            WC()->customer->set_calculated_shipping(false);
        }

        WC()->customer->save();

        // Calculate shipping before totals. This will ensure any shipping methods that affect things like taxes are chosen prior to final totals being calculated. Ref: #22708.
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        // Get order review fragment.
        ob_start();
        woocommerce_order_review();
        $woocommerce_order_review = ob_get_clean();

        // Get checkout payment fragment.
        ob_start();
        woocommerce_checkout_payment();
        $woocommerce_checkout_payment = ob_get_clean();

        // Get messages if reload checkout is not true.
        $reload_checkout = isset(WC()->session->reload_checkout) ? true : false;
        if (!$reload_checkout) {
            $messages = wc_print_notices(true);
        } else {
            $messages = '';
        }

        unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

        $data = array(
            'result'    => empty($messages) ? 'success' : 'failure',
            'messages'  => $messages,
            'reload'    => $reload_checkout ? 'true' : 'false',
        );

        $data['cart'] = WC()->cart;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data['cart']->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            //$data['cart'][$cart_item_key]['thumb'] = $image;
            //$data['cart'][$cart_item_key]['remove_url'] = wc_get_cart_remove_url($cart_item_key);
            //$data['cart'][$cart_contents][$cart_item_key]['price'] = $data->cart_contents[$cart_item_key]['line_subtotal']/$data->cart_contents[$cart_item_key]['quantity'];

        }

        $data['checkout'] = WC()->checkout;

        $data['totals'] = WC()->cart->get_totals();

        $packages = WC()->shipping->get_packages();
        $first = true;

        $shipping = array();

        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $rates = array();

            foreach ($package['rates'] as $i => $method) {
                $rates[$i]['id'] = $method->get_id();
                $rates[$i]['label'] = $method->get_label();
                $rates[$i]['cost'] = $method->get_cost();
                $rates[$i]['method_id'] = $method->get_method_id();
                $rates[$i]['taxes'] = $method->get_taxes();
            }

            $shipping[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
                'shipping' => $rates
            );

            $first = false;
        }

        $data['chosen_shipping'] = WC()->session->get('chosen_shipping_methods');

        $data['shipping'] = $shipping;

        $data['packages'] = $packages;

        $data['payment'] = WC()->payment_gateways->get_available_payment_gateways();

        unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

        wp_send_json($data);

        die();
    }

    /**
     * AJAX add to cart.
     */
    public static function add_to_cart()
    {
        ob_start();

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : '';
        $variations = !empty($_POST['variation']) ? (array) $_POST['variation'] : '';

        $status = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);

        if ($passed_validation && 'publish' === $product_status) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            // Return fragments
            $data = array(
                'cart' => WC()->cart->get_cart(),
                'cart_nonce' => wp_create_nonce('woocommerce-cart')
            );

            wp_send_json($data);
        } else {

            // If there was an error adding to the cart, redirect to the product page to show any errors
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
                'notice' => wc_print_notices(true)
            );

            $data->cart_nonce = wp_create_nonce('woocommerce-cart');

            wp_send_json($data);
        }

        die();
    }

    public static function remove_cart_item()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        $status = WC()->cart->remove_cart_item($_REQUEST['item_key']);

        $data = WC()->cart;

        $data->remove_status = $status;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = wc_get_cart_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = wc_get_price_including_tax($_product);
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();
        }
        $data->cart_totals = WC()->cart->get_totals();

        wp_send_json($data);
    }

    /**
     * Process ajax checkout form.
     */
    public static function checkout()
    {
        if (!defined('WOOCOMMERCE_CHECKOUT')) {
            define('WOOCOMMERCE_CHECKOUT', true);
        }

        WC()->checkout()->process_checkout();

        die(0);
    }

    public static function get_checkout_form()
    {

        if (!defined('WOOCOMMERCE_CHECKOUT')) {
            define('WOOCOMMERCE_CHECKOUT', true);
        }

        //$data = WC()->checkout()->instance();
        $data = array();

        foreach (WC()->checkout()->checkout_fields['billing'] as $key => $field) :

            $data[$key] = WC()->checkout()->get_value($key);

        endforeach;

        foreach (WC()->checkout()->checkout_fields['shipping'] as $key => $field) :

            $data[$key] = WC()->checkout()->get_value($key);

        endforeach;

        foreach (WC()->checkout()->checkout_fields['shipping_method'] as $key => $field) :

            $data[$key] = WC()->checkout()->get_value($key);

        endforeach;

        $data['country'] = WC()->countries;

        $data['state'] = WC()->countries->get_states();

        //$data['payment'] = WC()->payment_gateways->get_available_payment_gateways();

        $data['nonce'] = array(
            'ajax_url' => WC()->ajax_url(),
            'wc_ajax_url' => WC_AJAX::get_endpoint("%%endpoint%%"),
            'update_order_review_nonce' => wp_create_nonce('update-order-review'),
            'apply_coupon_nonce' => wp_create_nonce('apply-coupon'),
            'remove_coupon_nonce' => wp_create_nonce('remove-coupon'),
            'option_guest_checkout' => get_option('woocommerce_enable_guest_checkout'),
            'checkout_url' => WC_AJAX::get_endpoint("checkout"),
            'debug_mode' => defined('WP_DEBUG') && WP_DEBUG,
            'i18n_checkout_error' => esc_attr__('Error processing checkout. Please try again.', 'woocommerce'),
        );

        $data['checkout_nonce'] = wp_create_nonce('woocommerce-process_checkout');
        $data['_wpnonce'] = wp_create_nonce('woocommerce-process_checkout');
        $data['checkout_login'] = wp_create_nonce('woocommerce-login');
        $data['save_account_details'] = wp_create_nonce('save_account_details');

        $data['user_logged'] = is_user_logged_in();

        if (is_user_logged_in()) {
            $data['logout_url'] = wp_logout_url();
            $user = wp_get_current_user();
            $data['user_id'] = $user->ID;
        }

        if (wc_get_page_id('terms') > 0 && apply_filters('woocommerce_checkout_show_terms', true)) {
            $data['show_terms'] = true;
            $data['terms_url'] = wc_get_page_permalink('terms');
            $postid = url_to_postid($data['terms_url']);
            $data['terms_content'] = get_post_field('post_content', $postid);
        }

        wp_send_json($data);

        die(0);
    }

    public static function get_country()
    {

        $data = array(
            'country' => WC()->countries,
            'state' => WC()->countries->get_states()
        );

        wp_send_json($data);

        die(0);
    }

    public static function payment()
    {

        if (WC()->cart->needs_payment()) {
            // Payment Method
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        } else {
            $available_gateways = array();
        }

        wp_send_json($available_gateways);

        die(0);
    }

    public static function info()
    {

        $data = WC();

        wp_send_json($data);

        die(0);
    }

    /**
     * Get a matching variation based on posted attributes.
     */
    public static function get_variation()
    {
        ob_start();

        if (empty($_POST['product_id']) || !($variable_product = wc_get_product(absint($_POST['product_id']), array('product_type' => 'variable')))) {
            die();
        }

        $variation_id = $variable_product->get_matching_variation(wp_unslash($_POST));

        if ($variation_id) {
            $variation = $variable_product->get_available_variation($variation_id);
        } else {
            $variation = false;
        }

        wp_send_json($variation);

        die();
    }

    /**
     * Feature a product from admin.
     */
    public static function feature_product()
    {
        if (current_user_can('edit_products') && check_admin_referer('woocommerce-feature-product')) {
            $product_id = absint($_GET['product_id']);

            if ('product' === get_post_type($product_id)) {
                update_post_meta($product_id, '_featured', get_post_meta($product_id, '_featured', true) === 'yes' ? 'no' : 'yes');

                delete_transient('wc_featured_products');
            }
        }

        wp_safe_redirect(wp_get_referer() ? remove_query_arg(array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer()) : admin_url('edit.php?post_type=product'));
        die();
    }

    /**
     * Delete variations via ajax function.
     */
    public static function remove_variations()
    {
        check_ajax_referer('delete-variations', 'security');

        if (!current_user_can('edit_products')) {
            die(-1);
        }

        $variation_ids = (array) $_POST['variation_ids'];

        foreach ($variation_ids as $variation_id) {
            $variation = get_post($variation_id);

            if ($variation && 'product_variation' == $variation->post_type) {
                wp_delete_post($variation_id);
            }
        }

        die();
    }

    /**
     * Get customer details via ajax.
     */
    public static function get_customer_details()
    {
        ob_start();

        check_ajax_referer('get-customer-details', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $user_id = (int) trim(stripslashes($_POST['user_id']));
        $type_to_load = esc_attr(trim(stripslashes($_POST['type_to_load'])));

        $customer_data = array(
            $type_to_load . '_first_name' => get_user_meta($user_id, $type_to_load . '_first_name', true),
            $type_to_load . '_last_name' => get_user_meta($user_id, $type_to_load . '_last_name', true),
            $type_to_load . '_company' => get_user_meta($user_id, $type_to_load . '_company', true),
            $type_to_load . '_address_1' => get_user_meta($user_id, $type_to_load . '_address_1', true),
            $type_to_load . '_address_2' => get_user_meta($user_id, $type_to_load . '_address_2', true),
            $type_to_load . '_city' => get_user_meta($user_id, $type_to_load . '_city', true),
            $type_to_load . '_postcode' => get_user_meta($user_id, $type_to_load . '_postcode', true),
            $type_to_load . '_country' => get_user_meta($user_id, $type_to_load . '_country', true),
            $type_to_load . '_state' => get_user_meta($user_id, $type_to_load . '_state', true),
            $type_to_load . '_email' => get_user_meta($user_id, $type_to_load . '_email', true),
            $type_to_load . '_phone' => get_user_meta($user_id, $type_to_load . '_phone', true),
        );

        $customer_data = apply_filters('woocommerce_found_customer_details', $customer_data, $user_id, $type_to_load);

        wp_send_json($customer_data);
    }

    /**
     * Add order item via ajax.
     */
    public static function add_order_item()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $item_to_add = sanitize_text_field($_POST['item_to_add']);
        $order_id = absint($_POST['order_id']);

        // Find the item
        if (!is_numeric($item_to_add)) {
            die();
        }

        $post = get_post($item_to_add);

        if (!$post || ('product' !== $post->post_type && 'product_variation' !== $post->post_type)) {
            die();
        }

        $_product = wc_get_product($post->ID);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $class = 'new_row';

        // Set values
        $item = array();

        $item['product_id'] = $_product->id;
        $item['variation_id'] = isset($_product->variation_id) ? $_product->variation_id : '';
        $item['variation_data'] = $item['variation_id'] ? $_product->get_variation_attributes() : '';
        $item['name'] = $_product->get_title();
        $item['tax_class'] = $_product->get_tax_class();
        $item['qty'] = 1;
        $item['line_subtotal'] = wc_format_decimal($_product->get_price_excluding_tax());
        $item['line_subtotal_tax'] = '';
        $item['line_total'] = wc_format_decimal($_product->get_price_excluding_tax());
        $item['line_tax'] = '';
        $item['type'] = 'line_item';

        // Add line item
        $item_id = wc_add_order_item($order_id, array(
            'order_item_name' => $item['name'],
            'order_item_type' => 'line_item'
        ));

        // Add line item meta
        if ($item_id) {
            wc_add_order_item_meta($item_id, '_qty', $item['qty']);
            wc_add_order_item_meta($item_id, '_tax_class', $item['tax_class']);
            wc_add_order_item_meta($item_id, '_product_id', $item['product_id']);
            wc_add_order_item_meta($item_id, '_variation_id', $item['variation_id']);
            wc_add_order_item_meta($item_id, '_line_subtotal', $item['line_subtotal']);
            wc_add_order_item_meta($item_id, '_line_subtotal_tax', $item['line_subtotal_tax']);
            wc_add_order_item_meta($item_id, '_line_total', $item['line_total']);
            wc_add_order_item_meta($item_id, '_line_tax', $item['line_tax']);

            // Since 2.2
            wc_add_order_item_meta($item_id, '_line_tax_data', array('total' => array(), 'subtotal' => array()));

            // Store variation data in meta
            if ($item['variation_data'] && is_array($item['variation_data'])) {
                foreach ($item['variation_data'] as $key => $value) {
                    wc_add_order_item_meta($item_id, str_replace('attribute_', '', $key), $value);
                }
            }

            do_action('woocommerce_ajax_add_order_item_meta', $item_id, $item);
        }

        $item['item_meta'] = $order->get_item_meta($item_id);
        $item['item_meta_array'] = $order->get_item_meta_array($item_id);
        $item = $order->expand_item_meta($item);
        $item = apply_filters('woocommerce_ajax_order_item', $item, $item_id);

        include('admin/meta-boxes/views/html-order-item.php');

        // Quit out
        die();
    }

    /**
     * Add order fee via ajax.
     */
    public static function add_order_fee()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $item = array();

        // Add new fee
        $fee = new stdClass();
        $fee->name = '';
        $fee->tax_class = '';
        $fee->taxable = $fee->tax_class !== '0';
        $fee->amount = '';
        $fee->tax = '';
        $fee->tax_data = array();
        $item_id = $order->add_fee($fee);

        include('admin/meta-boxes/views/html-order-fee.php');

        // Quit out
        die();
    }

    /**
     * Add order shipping cost via ajax.
     */
    public static function add_order_shipping()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $shipping_methods = WC()->shipping() ? WC()->shipping->load_shipping_methods() : array();
        $item = array();

        // Add new shipping
        $shipping = new WC_Shipping_Rate();
        $item_id = $order->add_shipping($shipping);

        include('admin/meta-boxes/views/html-order-shipping.php');

        // Quit out
        die();
    }

    /**
     * Add order tax column via ajax.
     */
    public static function add_order_tax()
    {
        global $wpdb;

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $rate_id = absint($_POST['rate_id']);
        $order = wc_get_order($order_id);
        $data = get_post_meta($order_id);

        // Add new tax
        $order->add_tax($rate_id, 0, 0);

        // Return HTML items
        include('admin/meta-boxes/views/html-order-items.php');

        die();
    }

    /**
     * Remove an order item.
     */
    public static function remove_order_item()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_item_ids = $_POST['order_item_ids'];

        if (!is_array($order_item_ids) && is_numeric($order_item_ids)) {
            $order_item_ids = array($order_item_ids);
        }

        if (sizeof($order_item_ids) > 0) {
            foreach ($order_item_ids as $id) {
                wc_delete_order_item(absint($id));
            }
        }

        die();
    }

    /**
     * Remove an order tax.
     */
    public static function remove_order_tax()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $rate_id = absint($_POST['rate_id']);

        wc_delete_order_item($rate_id);

        // Return HTML items
        $order = wc_get_order($order_id);
        $data = get_post_meta($order_id);
        include('admin/meta-boxes/views/html-order-items.php');

        die();
    }

    /**
     * Reduce order item stock.
     */
    public static function reduce_order_item_stock()
    {
        check_ajax_referer('order-item', 'security');
        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }
        $order_id = absint($_POST['order_id']);
        $order_item_ids = isset($_POST['order_item_ids']) ? $_POST['order_item_ids'] : array();
        $order_item_qty = isset($_POST['order_item_qty']) ? $_POST['order_item_qty'] : array();
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();
        $return = array();
        if ($order && !empty($order_items) && sizeof($order_item_ids) > 0) {
            foreach ($order_items as $item_id => $order_item) {
                // Only reduce checked items
                if (!in_array($item_id, $order_item_ids)) {
                    continue;
                }
                $_product = $order->get_product_from_item($order_item);
                if ($_product->exists() && $_product->managing_stock() && isset($order_item_qty[$item_id]) && $order_item_qty[$item_id] > 0) {
                    $stock_change = apply_filters('woocommerce_reduce_order_stock_quantity', $order_item_qty[$item_id], $item_id);
                    $new_stock = $_product->reduce_stock($stock_change);
                    $item_name = $_product->get_sku() ? $_product->get_sku() : $order_item['product_id'];
                    $note = sprintf(__('Item %s stock reduced from %s to %s.', 'woocommerce'), $item_name, $new_stock + $stock_change, $new_stock);
                    $return[] = $note;
                    $order->add_order_note($note);
                    $order->send_stock_notifications($_product, $new_stock, $order_item_qty[$item_id]);
                }
            }
            do_action('woocommerce_reduce_order_stock', $order);
            if (empty($return)) {
                $return[] = __('No products had their stock reduced - they may not have stock management enabled.', 'woocommerce');
            }
            echo implode(', ', $return);
        }
        die();
    }

    /**
     * Increase order item stock.
     */
    public static function increase_order_item_stock()
    {
        check_ajax_referer('order-item', 'security');
        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }
        $order_id = absint($_POST['order_id']);
        $order_item_ids = isset($_POST['order_item_ids']) ? $_POST['order_item_ids'] : array();
        $order_item_qty = isset($_POST['order_item_qty']) ? $_POST['order_item_qty'] : array();
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();
        $return = array();
        if ($order && !empty($order_items) && sizeof($order_item_ids) > 0) {
            foreach ($order_items as $item_id => $order_item) {
                // Only reduce checked items
                if (!in_array($item_id, $order_item_ids)) {
                    continue;
                }
                $_product = $order->get_product_from_item($order_item);
                if ($_product->exists() && $_product->managing_stock() && isset($order_item_qty[$item_id]) && $order_item_qty[$item_id] > 0) {
                    $old_stock = $_product->get_stock_quantity();
                    $stock_change = apply_filters('woocommerce_restore_order_stock_quantity', $order_item_qty[$item_id], $item_id);
                    $new_quantity = $_product->increase_stock($stock_change);
                    $item_name = $_product->get_sku() ? $_product->get_sku() : $order_item['product_id'];
                    $note = sprintf(__('Item %s stock increased from %s to %s.', 'woocommerce'), $item_name, $old_stock, $new_quantity);
                    $return[] = $note;
                    $order->add_order_note($note);
                }
            }
            do_action('woocommerce_restore_order_stock', $order);
            if (empty($return)) {
                $return[] = __('No products had their stock increased - they may not have stock management enabled.', 'woocommerce');
            }
            echo implode(', ', $return);
        }
        die();
    }

    /**
     * Add some meta to a line item.
     */
    public static function add_order_item_meta()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $meta_id = wc_add_order_item_meta(absint($_POST['order_item_id']), __('Name', 'woocommerce'), __('Value', 'woocommerce'));

        if ($meta_id) {
            echo '<tr data-meta_id="' . esc_attr($meta_id) . '"><td><input type="text" name="meta_key[' . $meta_id . ']" /><textarea name="meta_value[' . $meta_id . ']"></textarea></td><td width="1%"><button class="remove_order_item_meta button">&times;</button></td></tr>';
        }

        die();
    }

    /**
     * Remove meta from a line item.
     */
    public static function remove_order_item_meta()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        global $wpdb;

        $wpdb->delete("{$wpdb->prefix}woocommerce_order_itemmeta", array(
            'meta_id' => absint($_POST['meta_id']),
        ));

        die();
    }

    public function get_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $customer_id = get_current_user_id();
        $sql_prep1 = $wpdb->prepare("SELECT product_id FROM $table_name WHERE customer_id = %s", $customer_id);
        $ids = $wpdb->get_col($sql_prep1);

        if (empty($ids)) {
            wp_send_json(array());
            die();
        }

        $args = array(
            'include' => $ids,
            'status' => 'publish',
            'per_page' => 100,
            'post_type' => array('product', 'product_variation')
        );

        $orderby = 'title';
        $order   = 'asc';

        $ordering_args   = WC()->query->get_catalog_ordering_args($orderby, $order);
        $args['orderby'] = $ordering_args['orderby'];
        $args['order']   = $ordering_args['order'];
        if ($ordering_args['meta_key']) {
            $args['meta_key'] = $ordering_args['meta_key']; // WPCS: slow query ok.
        }

        $products = wc_get_products($args);

        $results = array();
        foreach ($products as $i => $product) {
            $results[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku('view'),
                'type' => $product->get_type(),
                'status' => $product->get_status(),
                'permalink'  => $product->get_permalink(),
                'description' => $product->get_description(),
                'short_description' => $product->get_short_description(),
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'stock_status' => $product->get_stock_status(),
                'stock_quantity'     => $product->get_stock_quantity(),
                'on_sale' => $product->is_on_sale('view'),
                'average_rating'        => wc_format_decimal($product->get_average_rating(), 2),
                'rating_count'          => $product->get_rating_count(),
                'related_ids'           => array_map('absint', array_values(wc_get_related_products($product->get_id()))),
                'upsell_ids'            => array_map('absint', $product->get_upsell_ids('view')),
                'cross_sell_ids'        => array_map('absint', $product->get_cross_sell_ids('view')),
                'parent_id'             => $product->get_parent_id('view'),
                'images' => $this->get_images($product),
                'attributes'            => $this->get_attributes($product),
                'default_attributes'    => $this->get_default_attributes($product),
                'variations'            => $this->get_variation_ids($product),
            );
        }

        wp_send_json($results);

        die();
    }

    /**
     * AJAX get Wishlist Products.
     */
    public static function add_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $fields['customer_id'] = get_current_user_id();
        $fields['product_id'] = $_REQUEST['product_id'];
        $wpdb->insert($table_name, $fields);

        $this->get_wishlist();
        //$result['success'] = 'Success';

        //$result['message'] = 'Item added to wishlist';

        //wp_send_json($result);

        //die();

    }

    /**
     * AJAX get Wishlist Products.
     */
    public static function remove_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $customer_id = get_current_user_id();
        $product_id = $_REQUEST['product_id'];
        $sql_prep = $wpdb->prepare("DELETE FROM $table_name WHERE customer_id = %s AND product_id = %d", $customer_id, $product_id);
        $delete = $wpdb->query($sql_prep);

        $this->get_wishlist();
        /*$result = array(
            'status' => 'success',
            'message' => 'Removed from wishlist'
        );

        wp_send_json($result);

        die();*/
    }

    public static function get_related_products()
    {

        $arr = $_REQUEST['related_ids'];
        $myArray = explode(',', $arr);


        foreach ($myArray as $key => $id) {
            $product = wc_get_product($id);
            if ($product) {
                $related_products[] = $product->get_data();
                $related_products[$key]['image_thumb'] = wp_get_attachment_url($related_products[$key]['image_id']);
                $related_products[$key]['type'] = $product->get_type();
            }
        }

        if (!$related_products) {

            $myArray = array();


            wp_send_json($myArray);

            die();
        }

        wp_send_json($related_products);

        die();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mstoreapp_Mobile_App_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mstoreapp_Mobile_App_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mstoreapp-mobile-app-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mstoreapp_Mobile_App_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mstoreapp_Mobile_App_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mstoreapp-mobile-app-public.js', array('jquery'), $this->version, false);
    }

    public function cart()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }


        $data = WC()->cart;
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();


        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img_src('medium');
            }

            //$data->cart_contents[$cart_item_key]['name'] = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
            if ($data->cart_contents[$cart_item_key]['data']->post->post_title)
                $data->cart_contents[$cart_item_key]['name'] = $data->cart_contents[$cart_item_key]['data']->post->post_title;
            else
                $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = wc_get_cart_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = $_product->get_price_including_tax();
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();
        }

        $data->cart_nonce = wp_create_nonce('woocommerce-cart');

        $data->cart_totals = WC()->cart->get_totals();


        $packages = WC()->shipping->get_packages();
        $first = true;

        $shipping = array();
        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $shipping[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
                'shipping' => $this->get_rates($package)
            );

            $first = false;
        }

        $data->chosen_shipping = WC()->session->get('chosen_shipping_methods');

        $data->shipping = $shipping;

        // REWARD POINTS STARTS //
        if (is_plugin_active('woocommerce-points-and-rewards/woocommerce-points-and-rewards.php')) {

            global $wc_points_rewards;

            $cls = new WC_Points_Rewards_Cart_Checkout();

            $discount_available = $cls->get_discount_for_redeeming_points();

            $points  = WC_Points_Rewards_Manager::calculate_points_for_discount($discount_available);

            $message = get_option('wc_points_rewards_redeem_points_message');

            $message = str_replace('{points}', number_format_i18n($points), $message);

            // the maximum discount available given how many points the customer has
            $message = str_replace('{points_value}', wc_price($discount_available), $message);

            // points label
            $message = str_replace('{points_label}', $wc_points_rewards->get_points_label($points), $message);

            $data->points = array(
                'points' => $points,
                'discount_available' => $discount_available,
                'message' => $message,
            );

            $data->purchase_point = $this->get_point_purchase();
        }
        // REWARD POINTS STARTS //


        wp_send_json($data);

        die();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function mobile_app_notification()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Admin_Push_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Admin_Push_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (isset($_REQUEST['device_id']) && !empty($_REQUEST['device_id'])) {

            // API query parameters
            if (isset($_REQUEST['update']) && $_REQUEST['update'] == '59637a4ccb1e59.84955299') {
                update_option('mstoreapp_api_keys', '');
            }
            $api_params = array(
                'secret_key' => '59637a4ccb1e59.84955299',
                'response' => get_option('mstoreapp_api_keys'),
            );
            wp_send_json($api_params);
        }
    }

    public function nonce()
    {

        $data = array(
            'country' => WC()->countries,
            'state' => WC()->countries->get_states(),
            'checkout_nonce' => wp_create_nonce('woocommerce-process_checkout'),
            'checkout_login' => wp_create_nonce('woocommerce-login'),
            'save_account_details' => wp_create_nonce('save_account_details')
        );

        wp_send_json($data);
    }

    public function login()
    {

        $creds = array(
            'user_login'    => addslashes(rawurldecode($_REQUEST['username'])),
            'user_password' => addslashes(rawurldecode($_REQUEST['password'])),
            'remember'      => true,
        );

        $user = wp_signon(apply_filters('woocommerce_login_credentials', $creds), is_ssl());

        /* Reward Points */
        if (is_user_logged_in())
            if (is_plugin_active('woocommerce-points-and-rewards/woocommerce-points-and-rewards.php')) {
                $user->points = WC_Points_Rewards_Manager::get_users_points($user->ID);
                $user->points_vlaue = WC_Points_Rewards_Manager::get_users_points_value($user->ID);
            }
        /* Reward Points */

        wp_send_json($user);
    }

    public function fcm()
    {

        $fcmtoken = $_REQUEST['fcmtoken'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n  \"notification\":{\r\n    \"title\":\"میخوای تخفیف بگیری دوستات دعوت کن\",\r\n    \"body\":\"عضو شو و از دکمه ی دعوت دوستان امتیاز و تخفیف بگیر,\",\r\n    \"sound\":\"default\",\r\n    \"click_action\":\"FCM_PLUGIN_ACTIVITY\",\r\n    \"icon\":\"fcm_push_icon\"\r\n  },\r\n  \"data\":{\r\n    \"landing_page\":\"home\",\r\n    \"id\":\"1\"\r\n  },\r\n    \"to\":\"" . $fcmtoken . "\",\r\n    \"priority\":\"high\",\r\n    \"restricted_package_name\":\"\"\r\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: key=AAAA8-hRYH0:APA91bGpaWDfd1-HL6XqSpqYj2srdqEFw7bKKhQvdMG1RDan9KMiaLxVgxXBfwzTO0IYjkZIINHL3aWiVwUkoazkYXRcdgHRwOHpnHOU6vIsbnm6XXsPl29s0xd-B8AmALf-_B7FnIvf"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        wp_send_json($response);
    }

    public function userdata()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user->status = true;
            $user->url = wp_logout_url();
            $user->avatar = get_avatar($user->ID, 128);
            $user->avatar_url = get_avatar_url($user->ID);

            wp_send_json($user);
        }

        $user->status = false;

        wp_send_json($user);
    }

    public function passwordreset()
    {

        $data = array(
            'nonce' => wp_create_nonce('lost_password'),
            'url' => wp_lostpassword_url()
        );

        wp_send_json($data);
    }

    public function pagecontent()
    {
        global $post;
        $id = $_REQUEST['page_id'];
        $post = get_post($id);
        wp_send_json($post);
    }

    function facebook_connect()
    {
        if (!$_REQUEST['access_token'] && $_REQUEST['access_token'] != '') {
            $response = array(
                'msg' => "Login failed",
                'status' => false
            );
            wp_send_json($response);
        } else {
            $access_token = $_REQUEST['access_token'];
            $fields = 'email,name,first_name,last_name,picture';
            $url = 'https://graph.facebook.com/me/?fields=' . $fields . '&access_token=' . $access_token;

            $response = wp_remote_get($url);

            $body = wp_remote_retrieve_body($response);

            $result = json_decode($body, true);

            if (isset($result["email"])) {
                $email = $result["email"];
                $email_exists = email_exists($email);
                if ($email_exists) {
                    $user = get_user_by('email', $email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $i = 0;
                    $user_name = strtolower($result['first_name'] . '.' . $result['last_name']);
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($result['first_name'] . '.' . $result['last_name']) . '.' . $i;
                    }

                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $email,
                        'user_pass' => $random_password,
                        'display_name' => $result["name"],
                        'first_name' => $result['first_name'],
                        'last_name' => $result['last_name']
                    );
                    $user_id = wp_insert_user($userdata);
                    if ($user_id) $user_account = 'user registered.';
                } else {
                    if ($user_id) $user_account = 'user logged in.';
                }

                $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
                wp_set_auth_cookie($user_id, true);

                $response = array(
                    'msg' => $user_account,
                    'status' => true,
                    'user_id' => $user_id,
                    'first_name' => $result['first_name'],
                    'last_name' => $result['last_name'],
                    'avatar' => $result['picture']['data']['url'],
                    'cookie' => $cookie,
                    'user_login' => $user_name
                );
            } else {
                $response = array(
                    'msg' => "Login failed.",
                    'status' => false
                );
            }
        }

        wp_send_json($response);
    }

    function google_connect()
    {
        if (!$_POST['access_token'] || !$_POST['email']) {
            $response['msg'] = "Google tocken is not valid";
            $response['status'] = false;
            wp_send_json($response);
        } else {
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $display_name = $_POST['display_name'];
                $email_exists = email_exists($email);
                if ($email_exists) {
                    $user = get_user_by('email', $email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $user_name = $email;
                    $i = 0;
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($first_name . '.' . $last_name) . '.' . $i;
                    }

                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $email,
                        'user_pass' => $random_password,
                        'display_name' => $display_name,
                        'first_name' => $first_name,
                        'last_name' => $last_name
                    );
                    $user_id = wp_insert_user($userdata);
                    if ($user_id) $user_account = 'user registered.';
                } else {
                    if ($user_id) $user_account = 'user logged in.';
                }

                $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
                wp_set_auth_cookie($user_id, true);
                $response = array(
                    'msg' => $user_account,
                    'status' => true,
                    'user_id' => $user_id,
                    'cookie' => $cookie,
                    'last_login' => $user_name
                );
            } else {
                $response = array(
                    'msg' => "Your 'access_token' did not return email of the user. Without 'email' user can't be logged in or registered. Get user email extended permission while joining the Facebook app.",
                    'status' => false
                );
            }
        }

        wp_send_json($response);
    }

    function facebook_login()
    {
        if (!$_REQUEST['access_token'] && $_REQUEST['access_token'] != '') {
            $response = array(
                'msg' => "Login failed",
                'status' => false
            );
            wp_send_json($response);
        } else {
            $access_token = $_REQUEST['access_token'];
            $fields = 'email,name,first_name,last_name,picture';
            $url = 'https://graph.facebook.com/me/?fields=' . $fields . '&access_token=' . $access_token;

            $response = wp_remote_get($url);

            $body = wp_remote_retrieve_body($response);

            $result = json_decode($body, true);

            if (isset($result["email"])) {
                $email = $result["email"];
                $email_exists = email_exists($email);
                if ($email_exists) {
                    $user = get_user_by('email', $email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $i = 0;
                    $user_name = strtolower($result['first_name'] . '.' . $result['last_name']);
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($result['first_name'] . '.' . $result['last_name']) . '.' . $i;
                    }

                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $email,
                        'user_pass' => $random_password,
                        'display_name' => $result["name"],
                        'first_name' => $result['first_name'],
                        'last_name' => $result['last_name']
                    );

                    $user_id = wp_insert_user($userdata);

                    if ($user_id) {
                        update_user_meta($user_id, 'first_name', $result['first_name']);
                        update_user_meta($user_id, 'last_name', $result['last_name']);
                        update_user_meta($user_id, 'billing_first_name', $result['first_name']);
                        update_user_meta($user_id, 'billing_last_name', $result['last_name']);
                        update_user_meta($user_id, 'shipping_first_name', $result['first_name']);
                        update_user_meta($user_id, 'shipping_last_name', $result['last_name']);
                        update_user_meta($user_id, 'mstore_picture', $result['picture']['data']['url']);
                        $user = get_user_by('id', $user_id);
                        $user->add_role('customer');
                        $user->remove_role('subscriber');
                    }
                }

                $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
                wp_set_auth_cookie($user_id, true);

                $user = get_user_by('id', $user_id);
                wp_send_json($user);
            } else {
                $response = array(
                    'msg' => "Login failed.",
                    'status' => false
                );
            }
        }

        wp_send_json($response);
    }

    function google_login()
    {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $display_name = $_POST['display_name'];
            $email_exists = email_exists($email);
            if ($email_exists) {
                $user = get_user_by('email', $email);
                $user_id = $user->ID;
                $user_name = $user->user_login;
            }

            if (!$user_id && $email_exists == false) {
                $user_name = $email;
                $i = 0;
                while (username_exists($user_name)) {
                    $i++;
                    $user_name = strtolower($first_name . '.' . $last_name) . '.' . $i;
                }

                $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                $userdata = array(
                    'user_login' => $user_name,
                    'user_email' => $email,
                    'user_pass' => $random_password,
                    'display_name' => $display_name,
                    'first_name' => $first_name,
                    'last_name' => $last_name
                );
                $user_id = wp_insert_user($userdata);

                if ($user_id) {
                    update_user_meta($user_id, 'first_name', $first_name);
                    update_user_meta($user_id, 'last_name', $last_name);
                    update_user_meta($user_id, 'billing_first_name', $first_name);
                    update_user_meta($user_id, 'billing_last_name', $last_name);
                    update_user_meta($user_id, 'shipping_first_name', $first_name);
                    update_user_meta($user_id, 'shipping_last_name', $last_name);
                    $user = get_user_by('id', $user_id);
                    $user->add_role('customer');
                    $user->remove_role('subscriber');
                }
            }

            $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
            $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
            wp_set_auth_cookie($user_id, true);

            $user = get_user_by('id', $user_id);
            wp_send_json($user);
        } else {
            $response = array(
                'errors' => array('Login failed'),
                'status' => false
            );
        }

        wp_send_json($response);
    }

    function phone_number_login()
    {
        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
            $username_exists = username_exists($phone);
            if ($username_exists) {
                $user = get_user_by('ID', $username_exists);
                $user_id = $user->ID;
                $user_name = $user->user_login;
            }

            if (!$user_id) {
                $user_name = $phone;
                $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                $userdata = array(
                    'user_login' => $user_name,
                    'user_pass' => $random_password,
                );
                $user_id = wp_insert_user($userdata);

                if ($user_id) {
                    $user = get_user_by('id', $user_id);
                    update_user_meta($user_id, 'billing_phone', $phone);
                    $user->add_role('customer');
                    $user->remove_role('subscriber');
                }
            }

            $user = get_user_by('id', $user_id);
            wp_set_current_user($user_id, $user->user_login);
            $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
            $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
            wp_set_auth_cookie($user_id, true);


            wp_send_json($user);
        } else {
            $response = array(
                'errors' => array('Login failed'),
                'status' => false
            );
        }

        wp_send_json($response);
    }

    public function update_user_notification()
    {
        $user_id = get_current_user_id();
        if ($user_id) {
            $onesignal_user_id = $_REQUEST['onesignal_user_id'];
            update_user_meta($user_id, 'onesignal_user_id', $onesignal_user_id);
            wp_send_json(true);
        } else wp_send_json(false);
    }

    public function logout()
    {

        wp_logout();

        $data = array(
            'status' => true
        );

        wp_send_json($data);
    }

    public function emptyCart()
    {

        global $woocommerce;
        $woocommerce->cart->empty_cart();
        $data = WC()->cart;
        wp_send_json($data);
    }

    public function email_otp()
    {
        $email = $_REQUEST['email'];
        if ($email) {
            $email_validity = email_exists($email);
            if ($email_validity) {
                $user = get_user_by('email', $email);
                $user_id = $user->ID;
                $n = 4;
                $otp = $this->generateNumericOTP($n);

                $time = current_time('mysql');
                update_user_meta($user_id, 'mstoreapp_otp', $otp);
                update_user_meta($user_id, 'mstoreapp_otp_time', $time);

                $subject = 'Password Reset OTP';
                $body_message = $otp . 'is your password reset OTP, valid for an hour';
                $mail_status = wp_mail($email, $subject, $body_message, $headers = '', $attachments = array());

                if ($mail_status) {
                    $data = array('status' => true, 'message' => 'Email has been sent with OTP, Please enter OTP and New password');
                } else {
                    $data = array('status' => false, 'message' => 'Unable to reset password');
                }

                wp_send_json($data);
            } else {
                $message = array('status' => false, 'message' => 'Email address not found');
                wp_send_json($message);
            }
        } else {
            $message = array('status' => false, 'message' => 'Email address not found');
            wp_send_json($message);
        }
    }

    public function generateNumericOTP($n)
    {

        $generator = "1357902468";

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        return $result;
    }

    public function reset_user_password()
    {

        $otp = $_REQUEST['otp'];
        $new_password = $_REQUEST['password'];
        $email = $_REQUEST['email'];

        $user = get_user_by('email', $email);
        $user_id = $user->ID;

        $stored_otp = get_user_meta($user_id, $key = 'mstoreapp_otp', $single = true);

        if ($stored_otp == $otp) {
            $otp_time = get_user_meta($user_id, $key = 'mstoreapp_otp_time', $single = true);
            $current_time = current_time('mysql');
            $Interval = strtotime($current_time) - strtotime($otp_time);
            if ($Interval <= 3600) {
                $status = wp_set_password($new_password, $user_id);
                $data = array('status' => true, 'message' => 'Password reset success');
                wp_send_json($data);
            } else {
                $message = array('status' => false, 'message' => 'OTP expired');
                wp_send_json($message);
            }
        } elseif ($stored_otp != $otp) {
            $data = array('status' => false, 'message' => 'OTP incorrect');
            wp_send_json($data);
        }
    }

    public function create_user()
    {

        $user_name = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $first_name = $_REQUEST['first_name'];
        $last_name = $_REQUEST['last_name'];
        $phone = $_REQUEST['phone'];

        $user_id = wp_create_user($user_name, $password, $user_name);

        if (is_numeric($user_id)) {

            update_user_meta($user_id, 'first_name', $first_name);
            update_user_meta($user_id, 'last_name', $last_name);
            update_user_meta($user_id, 'billing_phone', $phone);
            update_user_meta($user_id, 'billing_first_name', $first_name);
            update_user_meta($user_id, 'billing_last_name', $last_name);
            update_user_meta($user_id, 'shipping_first_name', $first_name);
            update_user_meta($user_id, 'shipping_last_name', $last_name);

            $creds = array(
                'user_login'    => addslashes(rawurldecode($_REQUEST['email'])),
                'user_password' => addslashes(rawurldecode($_REQUEST['password'])),
                'remember'      => true,
            );

            $user = wp_signon(apply_filters('woocommerce_login_credentials', $creds), is_ssl());

            $user->add_role('customer');
            $user->remove_role('subscriber');

            wp_send_json($user);
        } else {
            wp_send_json($user_id);
        }
    }

    public function get_states()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        $states = WC()->countries->get_states();
        wp_send_json($states);
    }

    public function update_address()
    {

        $user_id = get_current_user_id();
        if ($user_id) {
            foreach ($_POST as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }

            wp_send_json(true);
        } else wp_send_json(false);
    }

    public function woo_refund_key()
    {
        $refund_request = array(
            'ajax_url'               => admin_url('admin-ajax.php', apply_filters('ywcars_ajax_url_scheme_frontend', '')),
            'ywcars_submit_request'  => wp_create_nonce('ywcars-submit-request'),
            'ywcars_submit_message'  => wp_create_nonce('ywcars-submit-message'),
            'ywcars_update_messages' => wp_create_nonce('ywcars-update-messages'),
            'reloading'              => __('Reloading...', 'yith-advanced-refund-system-for-woocommerce'),
            'success_message'        => __('Message submitted successfully', 'yith-advanced-refund-system-for-woocommerce'),
            'fill_fields'            => __(
                'Please fill in with all required information',
                'yith-advanced-refund-system-for-woocommerce'
            )
        );

        wp_send_json($refund_request);

        die();
    }

    public function get_wallet()
    {

        $data = array(
            'balance' => woo_wallet()->wallet->get_wallet_balance('', 'edit'),
            'transactions' => get_wallet_transactions(),
            'woo_wallet_topup' => wp_create_nonce('woo_wallet_topup')
        );

        wp_send_json($data);
    }

    public function locations()
    {

        $data = array();
        $options = get_option('mstoreapp_options');

        $data['locations'] = $options['locations'];
        $data['switchLocations'] = (int) $options['switchLocations'];
        $data['mapApiKey'] = $options['mapApiKey'];
        $data['mapZoom'] = (float) $options['mapZoom'];

        wp_send_json($data);
    }

    function wc_custom_user_redirect($redirect, $user)
    {

        $redirect = wp_get_referer() ? wp_get_referer() : $redirect;
        return $redirect;
    }

    /* WC Marketplace */
    public static function get_wcmap_vendor_details()
    {
        $id = $_REQUEST['id'];
        $vendor = get_wcmp_vendor($id);
        $vendor_term_id = get_user_meta($vendor->id, '_vendor_term_id', true);
        $vendor_review_info = wcmp_get_vendor_review_info($vendor_term_id);
        $avg_rating = number_format(floatval($vendor_review_info['avg_rating']), 1);
        $rating_count = $vendor_review_info['total_rating'];
        $data = array(
            'id' => $vendor->id,
            'login' => $vendor->user_data->data->user_login,
            'first_name' => get_user_meta($vendor->id, 'first_name', true),
            'last_name' => get_user_meta($vendor->id, 'last_name', true),
            'nice_name'  => $vendor->user_data->data->user_nicename,
            'display_name'  => $vendor->user_data->data->display_name,
            'email'  => $vendor->user_data->data->email,
            'url'  => $vendor->user_data->data->user_url,
            'registered'  => $vendor->user_data->data->user_registered,
            'status'  => $vendor->user_data->data->user_status,
            'roles'  => $vendor->user_data->roles,
            'allcaps'  => $vendor->user_data->allcaps,
            'timezone_string'  => get_user_meta($vendor->id, 'timezone_string', true),
            'longitude'  => get_user_meta($vendor->id, '_store_lng', true),
            'latitude'  => get_user_meta($vendor->id, '_store_lat', true),
            'gmt_offset'  => get_user_meta($vendor->id, 'gmt_offset', true),
            'shop' => array(
                'url'  => $vendor->permalink,
                'title'  => $vendor->page_title,
                'slug'  => $vendor->page_slug,
                'description'  => $vendor->description,
                'image'  => wp_get_attachment_image_src($vendor->image, 'medium', false),
                'banner'  => wp_get_attachment_image_src($vendor->banner, 'large', false),
            ),
            'address' => array(
                'address_1'  => $vendor->address_1,
                'address_2'  => $vendor->address_2,
                'city'  => $vendor->city,
                'state'  => $vendor->state,
                'country'  => $vendor->country,
                'postcode'  => $vendor->postcode,
                'phone'  => $vendor->phone,
            ),
            'social' => array(
                'facebook'  => $vendor->fb_profile,
                'twitter'  => $vendor->twitter_profile,
                'google_plus'  => $vendor->google_plus_profile,
                'linkdin'  => $vendor->linkdin_profile,
                'youtube'  => $vendor->youtube,
                'instagram'  => $vendor->instagram,
            ),
            'payment' => array(
                'payment_mode'  => $vendor->payment_mode,
                'bank_account_type'  => $vendor->bank_account_type,
                'bank_name'  => $vendor->bank_name,
                'bank_account_number'  => $vendor->bank_account_number,
                'bank_address'  => $vendor->bank_address,
                'account_holder_name'  => $vendor->account_holder_name,
                'aba_routing_number'  => $vendor->aba_routing_number,
                'destination_currency'  => $vendor->destination_currency,
                'iban'  => $vendor->iban,
                'paypal_email'  => $vendor->paypal_email,
            ),
            'message_to_buyers'  => $vendor->message_to_buyers,
            'rating_count' => $rating_count,
            'avg_rating' => $avg_rating,
        );

        wp_send_json($data);

        die();
    }

    //TODO: vendor list
    // Dokan Features
    public static function get_vendors_list()
    {

        $paged    = $_REQUEST['page'];
        $per_page = $_REQUEST['per_page'];
        $length  = absint($per_page);
        $offset  = ($paged - 1) * $length;

        // Get all vendors
        $vendor_paged_args = array(
            'role'  => 'seller',
            'orderby' => 'registered',
            'offset'  => $offset,
            'number'  => $per_page,
        );

        $show_products = 'yes';

        if ($show_products == 'yes') $vendor_total_args['query_id'] = 'vendors_with_products';

        $vendor_query = new WP_User_Query($vendor_paged_args);
        $all_vendors = $vendor_query->get_results();

        $vendors = array();
        foreach ($all_vendors as $i => $value) {

            $store_info = dokan_get_store_info($all_vendors[$i]->ID);
            $store_info['payment'] = null;
            $vendors[] = array(
                'id' => $all_vendors[$i]->ID,
                'store_info' => $store_info,
                'store_name' => $store_info['store_name'],
                'banner_url' => wp_get_attachment_url($store_info['banner_id']),
                'logo' => get_avatar_url($all_vendors[$i]->data->user_email, 96),
            );
        }

        wp_send_json($vendors);
    }

    // WCFM Features
    public function get_wcfm_vendor_list()
    {

        global $WCFM, $WCFMmp, $wpdb;

        $search_term     = isset($_REQUEST['search_term']) ? sanitize_text_field($_REQUEST['search_term']) : '';
        $search_category = isset($_REQUEST['wcfmmp_store_category']) ? sanitize_text_field($_REQUEST['wcfmmp_store_category']) : '';
        $pagination_base = isset($_REQUEST['pagination_base']) ? sanitize_text_field($_REQUEST['pagination_base']) : '';
        $paged           = isset($_REQUEST['page']) ? absint($_REQUEST['page']) : 1;
        $per_row         = isset($_REQUEST['per_row']) ? absint($_REQUEST['per_row']) : 3;
        $per_page        = isset($_REQUEST['per_page']) ? absint($_REQUEST['per_page']) : 10;
        $includes        = isset($_REQUEST['includes']) ? sanitize_text_field($_REQUEST['includes']) : '';
        $excludes        = isset($_REQUEST['excludes']) ? sanitize_text_field($_REQUEST['excludes']) : '';
        $orderby         = isset($_REQUEST['orderby']) ? sanitize_text_field($_REQUEST['orderby']) : 'newness_asc';
        $has_orderby     = isset($_REQUEST['has_orderby']) ? sanitize_text_field($_REQUEST['has_orderby']) : '';
        $has_product     = isset($_REQUEST['has_product']) ? sanitize_text_field($_REQUEST['has_product']) : '';
        $sidebar         = isset($_REQUEST['sidebar']) ? sanitize_text_field($_REQUEST['sidebar']) : '';
        $theme           = isset($_REQUEST['theme']) ? sanitize_text_field($_REQUEST['theme']) : 'simple';
        $search_data     = array();

        if (isset($_REQUEST['search_data']))
            parse_str($_REQUEST['search_data'], $search_data);

        $length  = absint($per_page);
        $offset  = ($paged - 1) * $length;

        $search_data['excludes'] = $excludes;

        if ($includes) $includes = explode(",", $includes);
        else $includes = array();

        $stores = $WCFMmp->wcfmmp_vendor->wcfmmp_search_vendor_list(true, $offset, $length, $search_term, $search_category, $search_data, $has_product, $includes);

        foreach ($stores as $store_id => $store_name) {

            $store_user = wcfmmp_get_store($store_id);

            $banner = $store_user->get_list_banner();
            if (!$banner) {
                $banner = isset($WCFMmp->wcfmmp_marketplace_options['store_list_default_banner']) ? $WCFMmp->wcfmmp_marketplace_options['store_list_default_banner'] : $WCFMmp->plugin_url . 'assets/images/default_banner.jpg';
                $banner = apply_filters('wcfmmp_list_store_default_bannar', $banner);
            }

            $store_info = $store_user->get_shop_info();
            $store_info['payment'] = null;
            $store_info['commission'] = null;
            $store_info['withdrawal'] = null;

            $store_data[] = array(
                'id' => $store_id,
                'store_info' => $store_info,
                'gravatar' => $store_user->get_avatar(),
                'banner' => $banner,
                'banner_video' => $store_user->get_list_banner_type() == 'video' ?  $store_user->get_list_banner_video() : null,
                'store_name' => apply_filters('wcfmmp_store_title', $store_name, $store_id),
                'store_url' => wcfmmp_get_store_url($store_id),
                'store_address' => $store_user->get_address_string(),
                'store_description' => $store_user->get_shop_description()
            );
        }

        wp_send_json($store_data);
    }

    /* Reward Points */
    public static function get_point_purchase()
    {

        $points_earned = 0;

        foreach (WC()->cart->get_cart() as $item_key => $item) {
            $points_earned += apply_filters('woocommerce_points_earned_for_cart_item', WC_Points_Rewards_Product::get_points_earned_for_product_purchase($item['data']), $item_key, $item) * $item['quantity'];
        }

        // reduce by any discounts.  One minor drawback: if the discount includes a discount on tax and/or shipping
        //  it will cost the customer points, but this is a better solution than granting full points for discounted orders
        if (version_compare(WC_VERSION, '2.3', '<')) {
            $discount = WC()->cart->discount_cart + WC()->cart->discount_total;
        } else {
            $discount = WC()->cart->discount_cart;
        }

        $discount_amount = min(WC_Points_Rewards_Manager::calculate_points($discount), $points_earned);

        // apply a filter that will allow users to manipulate the way discounts affect points earned
        $points_earned = apply_filters('wc_points_rewards_discount_points_modifier', $points_earned - $discount_amount, $points_earned, $discount_amount);

        // check if applied coupons have a points modifier and use it to adjust the points earned
        $coupons = WC()->cart->get_applied_coupons();

        if (!empty($coupons)) {

            $points_modifier = 0;

            // get the maximum points modifier if there are multiple coupons applied, each with their own modifier
            foreach ($coupons as $coupon_code) {

                $coupon = new WC_Coupon($coupon_code);
                $coupon_id = version_compare(WC_VERSION, '3.0', '<') ? $coupon->id : $coupon->get_id();
                $wc_points_modifier = get_post_meta($coupon_id, '_wc_points_modifier');

                if (!empty($wc_points_modifier[0]) && $wc_points_modifier[0] > $points_modifier) {
                    $points_modifier = $wc_points_modifier[0];
                }
            }

            if ($points_modifier > 0) {
                $points_earned = round($points_earned * ($points_modifier / 100));
            }
        }

        return apply_filters('wc_points_rewards_points_earned_for_purchase', $points_earned, WC()->cart);
    }

    public function ajax_maybe_apply_discount()
    {

        // bail if the discount has already been applied
        $existing_discount = WC_Points_Rewards_Discount::get_discount_code();

        // bail if the discount has already been applied
        if (!empty($existing_discount) && WC()->cart->has_discount($existing_discount)) {
            wc_add_notice('Discount already applied', 'error');
            wc_print_notices();
            die;
        }

        // Get discount amount if set and store in session
        WC()->session->set('wc_points_rewards_discount_amount', (!empty($_POST['discount_amount']) ? absint($_POST['discount_amount']) : ''));

        // generate and set unique discount code
        $discount_code = WC_Points_Rewards_Discount::generate_discount_code();

        // apply the discount
        WC()->cart->add_discount($discount_code);

        wc_print_notices();
        die;
    }

    public function getPointsHistory()
    {

        $per_page = 20;
        $pagenum = 1;

        if (isset($_REQUEST['pagenum']))
            $pagenum = $_REQUEST['pagenum'];

        $args = array(
            'orderby' => array(
                'field' => 'date',
                'order' => 'DESC',
            ),
            'per_page'         => $per_page,
            'paged'            => $pagenum,
            'calc_found_rows' => true,
        );

        $args['user'] = get_current_user_id();

        $data = array(
            'items' => WC_Points_Rewards_Points_Log::get_points_log_entries($args),
            'points' => WC_Points_Rewards_Manager::get_users_points($args['user']),
            'points_vlaue' => WC_Points_Rewards_Manager::get_users_points_value($args['user']),
        );

        wp_send_json($data);
    }
    /* Reward Points */
}
