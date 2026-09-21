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

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Stringable;

/**
 * Logger that just prints stuff to stdout
 */
class SimpleLogger extends AbstractLogger implements IFlushingAwareLogger {
	use LoggerTrait;

	private int $minLevel;

	public function __construct( string $minlevel = LogLevel::INFO ) {
		$this->minLevel = $this->levelToInt( $minlevel );
	}

	/**
	 * @inheritDoc
	 * @phan-param mixed[] $context
	 */
	public function log( $level, string|Stringable $message, array $context = [] ): void {
		if ( $this->levelToInt( $level ) >= $this->minLevel ) {
			echo $this->getFormattedMessage( $level, $message ) . "\n";
		}
	}

	/**
	 * @inheritDoc
	 */
	public function flush(): void {
		// Everything else is printed immediately
		echo "\n" . str_repeat( '-', 80 ) . "\n\n";
	}
}
