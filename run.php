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

use BotRiconferme\Bot;
use BotRiconferme\CLI;

/**
 * Entry point for the bot, called by CLI
 */

set_error_handler(
	/**
	 * @throws ErrorException
	 */
	static function ( int $errno, string $errstr, string $errfile, int $errline ): never {
		throw new ErrorException( $errstr, 0, $errno, $errfile, $errline );
	}
);

require __DIR__ . '/vendor/autoload.php';

if ( !CLI::isCLI() ) {
	exit( 'CLI only!' );
}

const BOT_VERSION = '3.0';
// TODO make this configurable?
const BOT_EDITS = false;

date_default_timezone_set( 'Europe/Rome' );

$cli = new CLI();
$bot = new Bot( $cli );
$bot->run();
