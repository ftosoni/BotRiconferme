<?php
/**
 * This file is part of BotRiconferme.
 *
 * BotRiconferme is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BotRiconferme is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (C) 2019-2026 Daimona Eaytoy
 * @license AGPL-3.0-or-later
 */

declare( strict_types=1 );

namespace BotRiconferme;

use BotRiconferme\Exception\ConfigException;

/**
 * Singleton class holding user-defined config
 */
class Config {
	private static ?self $instance = null;
	/** @phan-var array<mixed> */
	private array $opts = [];

	/**
	 * Use self::init() and self::getInstance()
	 */
	private function __construct() {
	}

	/**
	 * Initialize a new self instance with CLI params set and retrieve on-wiki config.
	 *
	 * @param array<string,mixed> $confValues
	 */
	public static function init( array $confValues ): void {
		if ( self::$instance ) {
			throw new ConfigException( 'Config was already initialized' );
		}

		$inst = new self();

		foreach ( $confValues as $key => $val ) {
			$inst->set( $key, $val );
		}
		self::$instance = $inst;
	}

	/**
	 * Set a config value.
	 */
	protected function set( string $key, mixed $value ): void {
		$this->opts[ $key ] = $value;
	}

	public static function getInstance(): self {
		return self::$instance ?? throw new ConfigException( 'Config not yet initialized' );
	}

	/** @suppress PhanUnreferencedPublicMethod */
	public static function clearInstance(): void {
		self::$instance = null;
	}

	/**
	 * Get the requested option, or fail if it doesn't exist
	 */
	public function get( string $opt ): mixed {
		return $this->opts[ $opt ] ?? throw new ConfigException( "Config option '$opt' not set." );
	}
}
