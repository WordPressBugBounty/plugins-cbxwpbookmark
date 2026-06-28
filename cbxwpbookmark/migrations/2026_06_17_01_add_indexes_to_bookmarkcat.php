<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXWPBookmarkCatAddIndexes' ) ) {
	/**
	 * Class CBXWPBookmarkCatAddIndexes
	 * @since 2.0.8
	 */
	class CBXWPBookmarkCatAddIndexes {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;
			$table_name = 'cbxwpbookmarkcat';
			$prefixed_table = $wpdb->prefix . $table_name;

			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
                    $wpdb->query( "ALTER TABLE `{$prefixed_table}` MODIFY `modyfied_date` datetime NULL DEFAULT NULL" );

					Capsule::schema()->table( $table_name, function ( $table ) use ( $wpdb, $prefixed_table ) {
						// Add index for user_id if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_user = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmarkcat_user_id_index' ) );
						if ( ! $index_user ) {
							$table->index( 'user_id', 'cbxwpbookmarkcat_user_id_index' );
						}

						// Add index for privacy if not exists
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$index_privacy = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxwpbookmarkcat_privacy_index' ) );
						if ( ! $index_privacy ) {
							$table->index( 'privacy', 'cbxwpbookmarkcat_privacy_index' );
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
			$table_name = 'cbxwpbookmarkcat';
			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) {
						$table->dropIndex( 'cbxwpbookmarkcat_user_id_index' );
						$table->dropIndex( 'cbxwpbookmarkcat_privacy_index' );
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXWPBookmarkCatAddIndexes
}

if ( isset( $action ) && $action == 'up' ) {
	CBXWPBookmarkCatAddIndexes::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXWPBookmarkCatAddIndexes::down();
}