<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXWPBookmarkAddUniqueUserBookmark' ) ) {
	/**
	 * Class CBXWPBookmarkAddUniqueUserBookmark
	 * @since 2.0.0
	 */
	class CBXWPBookmarkAddUniqueUserBookmark {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;

			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			try {
				if ( Capsule::schema()->hasTable( $bookmark_table ) ) {
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
					$index_exists = $wpdb->get_var( "SHOW INDEX FROM {$bookmark_table} WHERE Key_name = 'unique_user_bookmark'" );

					if ( ! $index_exists ) {
						Capsule::schema()->table( $bookmark_table, function ( $table ) {
							$table->unique( [ 'user_id', 'object_id', 'object_type', 'cat_id' ], 'unique_user_bookmark' );
						} );
					}
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
			global $wpdb;

			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			try {
				if ( Capsule::schema()->hasTable( $bookmark_table ) ) {
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
					$index_exists = $wpdb->get_var( "SHOW INDEX FROM {$bookmark_table} WHERE Key_name = 'unique_user_bookmark'" );

					if ( $index_exists ) {
						Capsule::schema()->table( $bookmark_table, function ( $table ) {
							$table->dropUnique( 'unique_user_bookmark' );
						} );
					}
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXWPBookmarkAddUniqueUserBookmark
}

if ( isset( $action ) && $action == 'up' ) {
	CBXWPBookmarkAddUniqueUserBookmark::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXWPBookmarkAddUniqueUserBookmark::down();
}
