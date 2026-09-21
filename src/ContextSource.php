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

use BotRiconferme\Message\Message;
use BotRiconferme\Message\MessageProvider;
use BotRiconferme\Wiki\Page\Page;
use BotRiconferme\Wiki\Page\PageBotList;
use BotRiconferme\Wiki\User;
use BotRiconferme\Wiki\Wiki;
use BotRiconferme\Wiki\WikiGroup;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Base class with a few utility methods available to get a logger, the config and a wiki
 */
abstract class ContextSource implements LoggerAwareInterface {
	private LoggerInterface $logger;
	private Config $config;
	private WikiGroup $wikiGroup;
	private MessageProvider $messageProvider;
	private PageBotList $pageBotList;

	public function __construct(
		LoggerInterface $logger,
		WikiGroup $wikiGroup,
		MessageProvider $mp,
		PageBotList $pbl
	) {
		$this->setLogger( $logger );
		$this->setConfig( Config::getInstance() );
		$this->setWikiGroup( $wikiGroup );
		$this->setMessageProvider( $mp );
		$this->pageBotList = $pbl;
	}

	protected function getLogger(): LoggerInterface {
		return $this->logger;
	}

	/**
	 * @inheritDoc
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Shorthand to $this->getConfig()->get
	 */
	protected function getOpt( string $optname ): mixed {
		return $this->getConfig()->get( $optname );
	}

	protected function getConfig(): Config {
		return $this->config;
	}

	protected function setConfig( Config $cfg ): void {
		$this->config = $cfg;
	}

	protected function getWiki(): Wiki {
		return $this->getWikiGroup()->getMainWiki();
	}

	protected function getWikiGroup(): WikiGroup {
		return $this->wikiGroup;
	}

	protected function setWikiGroup( WikiGroup $wikiGroup ): void {
		$this->wikiGroup = $wikiGroup;
	}

	protected function getMessageProvider(): MessageProvider {
		return $this->messageProvider;
	}

	protected function setMessageProvider( MessageProvider $mp ): void {
		$this->messageProvider = $mp;
	}

	protected function msg( string $key ): Message {
		return $this->messageProvider->getMessage( $key );
	}

	public function getBotList(): PageBotList {
		return $this->pageBotList;
	}

	/**
	 * Shorthand to get a page using the local wiki
	 */
	protected function getPage( string $title ): Page {
		return new Page( $title, $this->getWiki() );
	}

	/**
	 * Shorthand to get a user using the local wiki
	 */
	protected function getUser( string $name ): User {
		$ui = $this->getBotList()->getUserInfo( $name );
		return new User( $ui, $this->getWiki() );
	}
}
