<?php
//phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$open_newtab   = absint( $settings->get_field( 'bookmark_open_newtab', 'cbxwpbookmark_basics', 0 ) );
$target = ($open_newtab) ? ' target="_blank"' : '';
$object_id    = $item->object_id;//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$object_type  = $item->object_type;//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$object_title = wp_strip_all_tags( get_the_title( $object_id ) );//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( $object_title == '' ) {
	$object_title = esc_html__( 'Untitled', 'cbxwpbookmark' );//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
}
$object_link = get_permalink( $object_id );//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

echo '<li class="cbxwpbookmark-mylist-item ' . esc_attr( $sub_item_class ) . '">';
do_action( 'cbxwpbookmark_bookmarkpost_single_item_start', $object_id, $item );
echo '<a '.esc_html($target).' title="' . esc_attr( $object_title ) . '" href="' . esc_url( $object_link ) . '">' . $object_title . '</a>' . $action_html;  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
do_action( 'cbxwpbookmark_bookmarkpost_single_item_end', $object_id, $item );
echo '</li>';