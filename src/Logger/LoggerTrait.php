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

namespace BotRiconferme\Logger;

use BotRiconferme\Clock;
use LogicException;
use Psr\Log\LogLevel;
use Stringable;

trait LoggerTrait {
	/**
	 * Translate a LogLevel constant to an integer
	 */
	protected function levelToInt( string $level ): int {
		// Order matters
		$mapping = [
			LogLevel::DEBUG,
			LogLevel::INFO,
			LogLevel::NOTICE,
			LogLevel::WARNING,
			LogLevel::ERROR,
			LogLevel::CRITICAL,
			LogLevel::ALERT,
			LogLevel::EMERGENCY
		];
		$intLevel = array_search( $level, $mapping, true );
		if ( $intLevel === false ) {
			throw new LogicException( "Unexpected log level $level" );
		}
		return $intLevel;
	}

	protected function getFormattedMessage( string $level, string|Stringable $message ): string {
		return sprintf( '%s [%s] - %s', Clock::getDate( 'd M H:i:s' ), $level, $message );
	}
}
