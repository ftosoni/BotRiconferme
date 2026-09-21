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

namespace BotRiconferme\Message;

use RuntimeException;
use Stringable;

class Message {
	public const MONTHS = [
		'January' => 'gennaio',
		'February' => 'febbraio',
		'March' => 'marzo',
		'April' => 'aprile',
		'May' => 'maggio',
		'June' => 'giugno',
		'July' => 'luglio',
		'August' => 'agosto',
		'September' => 'settembre',
		'October' => 'ottobre',
		'November' => 'novembre',
		'December' => 'dicembre'
	];

	public function __construct(
		private string $value
	) {
	}

	/**
	 * @param array<string,int|string> $args
	 */
	public function params( array $args ): self {
		$this->value = strtr( $this->value, $args );
		return $this;
	}

	public function text(): string {
		$this->parsePlurals();
		return $this->value;
	}

	/**
	 * Replace {{$plur|<amount>|sing|plur}}
	 */
	protected function parsePlurals(): void {
		$reg = '!\{\{\$plur\|(?P<amount>\d+)\|(?P<sing>[^}|]+)\|(?P<plur>[^|}]+)}}!';

		if ( preg_match( $reg, $this->value ) === 0 ) {
			return;
		}
		$this->value = preg_replace_callback(
			$reg,
			/** @param string[] $matches */
			static function ( array $matches ): string {
				return (int)$matches['amount'] > 1 ? trim( $matches['plur'] ) : trim( $matches['sing'] );
			},
			$this->value
		);
	}

	/**
	 * Get a timestamp from a localized time string
	 *
	 * @param string $timeString Full format, e.g. "15 aprile 2019 18:27"
	 */
	public static function getTimestampFromLocalTime( string $timeString ): int {
		$englishTime = str_ireplace(
			array_values( self::MONTHS ),
			array_keys( self::MONTHS ),
			$timeString
		);
		assert( is_string( $englishTime ) );
		$parsedTime = strtotime( $englishTime );
		if ( $parsedTime === false ) {
			throw new RuntimeException( 'Could not parse time: ' . $timeString );
		}
		return $parsedTime;
	}

	/**
	 * Given an array of data, returns a list of its elements using commas, and " e " before
	 * the last one. $emptyText can be used to specify the text in case $data is empty.
	 *
	 * @param array<int|string|Stringable> $data
	 */
	public static function commaList( array $data, string $emptyText = 'nessuno' ): string {
		if ( count( $data ) > 1 ) {
			$last = array_pop( $data );
			$ret = implode( ', ', $data ) . " e $last";
		} elseif ( $data ) {
			$ret = (string)$data[0];
		} else {
			$ret = $emptyText;
		}

		return $ret;
	}

	public function __toString(): string {
		return $this->text();
	}
}
