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

namespace BotRiconferme\Request;

use Psr\Log\LoggerInterface;

readonly class RequestFactory {
	public function __construct(
		private LoggerInterface $logger,
		private string $domain
	) {
	}

	/**
	 * @param array<int|string|bool> $params
	 * @param string[] $cookies
	 */
	public function createRequest( array $params, array $cookies, callable $cookiesCallback ): RequestBase {
		$ret = extension_loaded( 'curl' )
			? new CurlRequest( $this->logger, $params, $this->domain, $cookiesCallback )
			: new NativeRequest( $this->logger, $params, $this->domain, $cookiesCallback );
		$ret->setCookies( $cookies );
		return $ret;
	}
}
