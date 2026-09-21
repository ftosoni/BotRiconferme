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

declare( strict_types = 1 );

namespace BotRiconferme\Tests;

use BotRiconferme\Clock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( Clock::class )]
class ClockTest extends TestCase {
	public function testGetDate(): void {
		$format = 'Ymd H:i:s';
		$ts = time();
		$this->assertSame( date( $format, $ts ), Clock::getDate( $format, $ts ) );
	}

	public function testFakeTime(): void {
		$fakeTime = 1733440000;
		Clock::setFakeTime( $fakeTime );
		$this->assertSame( (string)$fakeTime, Clock::getDate( 'U' ) );
		Clock::clearFakeTime();
		$this->assertNotSame( (string)$fakeTime, Clock::getDate( 'U' ) );
	}
}
