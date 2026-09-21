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

namespace BotRiconferme\Message;

use BotRiconferme\Exception\ConfigException;
use BotRiconferme\Message\Exception\InvalidMessagePageException;
use BotRiconferme\Message\Exception\MessageNotFoundException;
use BotRiconferme\Message\Exception\MessagesPageDoesNotExistException;
use BotRiconferme\Request\Exception\MissingPageException;
use BotRiconferme\Wiki\Wiki;
use JsonException;

class MessageProvider {
	/** @var string[]|null */
	private static ?array $messages = null;

	public function __construct(
		private readonly Wiki $wiki,
		private readonly string $msgTitle
	) {
	}

	private function grabWikiMessages(): void {
		if ( self::$messages !== null ) {
			return;
		}
		try {
			$cont = $this->wiki->getPageContent( $this->msgTitle );
			$wikiMessages = json_decode( $cont, true, 512, JSON_THROW_ON_ERROR );
		} catch ( MissingPageException ) {
			throw new MessagesPageDoesNotExistException( 'Please create a messages page.' );
		} catch ( JsonException ) {
			throw new InvalidMessagePageException( 'Invalid messages page.' );
		}
		if ( !is_array( $wikiMessages ) ) {
			throw new ConfigException( "Invalid messages page" );
		}
		self::$messages = $wikiMessages;
	}

	public function getMessage( string $key ): Message {
		$this->grabWikiMessages();
		$messageText = self::$messages[$key] ?? null;
		if ( !$messageText ) {
			throw new MessageNotFoundException( "Message '$key' does not exist." );
		}
		return new Message( $messageText );
	}
}
