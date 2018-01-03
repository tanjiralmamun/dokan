<?php
/**
 * Save admin fee as meta for existing sub-orders
 */
function dokan_update_order_meta_273(){
    $args = array(
        'post_type' => 'shop_order',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'meta_query' => array(
            array(
                'key'     => 'has_sub_order',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key'     => '_dokan_admin_fee',
                'compare' => 'NOT EXISTS',
            ),
	)
    );
    
    $query = new WP_Query( $args );
    
    foreach (  $query->posts as $sub_order ) {
        $order = wc_get_order( $sub_order->ID );
        $admin_fee = dokan_get_admin_commission_by( $order, dokan_get_seller_id_by_order( $sub_order->ID ) );
        update_post_meta( $sub_order->ID , '_dokan_admin_fee', $admin_fee );
    }
}

/**
 * Modify column structure to support upto 4 decimals
 */
function dokan_update_table_structure_273(){
    global $wpdb;
    
    $wpdb->query( 
        "ALTER TABLE `{$wpdb->prefix}dokan_orders` 
        MODIFY COLUMN order_total float(11,4)"
    );
    
    $wpdb->query( 
        "ALTER TABLE `{$wpdb->prefix}dokan_orders` 
        MODIFY COLUMN net_amount float(11,4)" 
    );
    
}


dokan_update_table_structure_273();
dokan_update_order_meta_273();