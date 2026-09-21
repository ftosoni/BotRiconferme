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

use DateTime;

/**
 * Lightweight class that allows mocking date functions.
 */
class Clock {
	private static ?int $fakeTime = null;

	public static function getDate( string $format, ?int $timestamp = null ): string {
		$timestamp ??= self::$fakeTime ?? time();
		return date( $format, $timestamp );
	}

	public static function dateTimeNow(): DateTime {
		$curTime = self::$fakeTime ?? time();
		return ( new DateTime() )->setTimestamp( $curTime );
	}

	public static function now(): int {
		return self::$fakeTime ?? time();
	}

	/** @suppress PhanUnreferencedPublicMethod */
	public static function setFakeTime( int $time ): void {
		self::$fakeTime = $time;
	}

	/** @suppress PhanUnreferencedPublicMethod */
	public static function clearFakeTime(): void {
		self::$fakeTime = null;
	}
}
