<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXWPBookmarkAddIndexes' ) ) {
	/**
	 * Class CBXWPBookmarkAddIndexes
	 * @since 2.0.8
	 */
	class CBXWPBookmarkAddIndexes {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;
			$table_name = 'cbxwpbookmark';
			$prefixed_table = $wpdb->prefix . $table_name;

			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
					$wpdb->query( "ALTER TABLE `{$prefixed_table}` MODIFY `modyfied_date` datetime NULL DEFAULT NULL" );
					
					Capsule::schema()->table( $table_name, function ( $table ) use ( $wpdb, $prefixed_table ) {
						// Add index for object_id if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_object = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmark_object_id_index' ) );

						if ( ! $index_object ) {
							$table->index( 'object_id', 'cbxwpbookmark_object_id_index' );
						}

						// Add index for object_type if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_object_type = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmark_object_type_index' ) );
						if ( ! $index_object_type ) {
							$table->index( 'object_type', 'cbxwpbookmark_object_type_index' );
						}

						// Add index for cat_id if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_cat = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmark_cat_id_index' ) );
						if ( ! $index_cat ) {
							$table->index( 'cat_id', 'cbxwpbookmark_cat_id_index' );
						}

						// Add index for user_id if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_user = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmark_user_id_index' ) );
						if ( ! $index_user ) {
							$table->index( 'user_id', 'cbxwpbookmark_user_id_index' );
						}

						// Add index for sort_order if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_user = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmark_sort_order_index' ) );
						if ( ! $index_user ) {
							$table->index( 'sort_order', 'cbxwpbookmark_sort_order_index' );
						}
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method up

		/**
		 * Drop migration
		 */
		public static function down() {
			$table_name = 'cbxwpbookmark';
			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) {
						$table->dropIndex( 'cbxwpbookmark_object_id_index' );
						$table->dropIndex( 'cbxwpbookmark_object_type_index' );
						$table->dropIndex( 'cbxwpbookmark_cat_id_index' );
						$table->dropIndex( 'cbxwpbookmark_user_id_index' );
						$table->dropIndex( 'cbxwpbookmark_sort_order_index' );
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXWPBookmarkAddIndexes
}

if ( isset( $action ) && $action == 'up' ) {
	CBXWPBookmarkAddIndexes::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXWPBookmarkAddIndexes::down();
}