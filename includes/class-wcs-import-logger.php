<?php
/**
 * Shutdown handler and logger class for the Importer
 *
 * @since 1.0
 */
class WCS_Import_Logger {

	public static $logger = null;

	/**
	 * Catch any unexpected shutdowns experienced during the import process
	 *
	 * @since 1.0
	 */
	public static function shutdown_handler() {
		if ( ! empty( WCS_Import_Parser::$fields ) && ! empty( WCS_Import_Parser::$row ) && $error = error_get_last() ) {

			if ( E_ERROR == $error['type'] ) {
				self::log( '--------- Expected shutdown during the importer ---------', 'wcs-importer-shutdown' );
				self::log( 'Mapped Fields: ' . print_r( WCS_Import_Parser::$fields, true ), 'wcs-importer-shutdown' );
				self::log( 'CSV Row: ' . print_r( WCS_Import_Parser::$row, true ), 'wcs-importer-shutdown' );
				self::log( sprintf( 'PHP Fatal error %s in %s on line %s.', $error['message'], $error['file'], $error['line'] ), 'wcs-importer-shutdown' );
			}

			WCS_Import_Parser::$fields = WCS_Import_Parser::$row = null;
		}
	}

	/**
	 * Log all the things during an import
	 *
	 * @since 1.0
	 * @param string $message
	 * @param string $log Defaults to wcs-importer
	 */
	public static function log( $message, $log = 'wcs-importer' ) {

		if ( ! WCS_Import_Parser::$test_mode && ( ! defined( 'WCSI_LOG' ) || false !== WCSI_LOG ) ) {
			if ( ! self::$logger ) {
				self::$logger = new WC_Logger();
			}

			self::$logger->add( $log, $message );
		}
	}
}