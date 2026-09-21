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

/** @noinspection PhpComposerExtensionStubsInspection */
declare( strict_types=1 );

namespace BotRiconferme\Request;

use BotRiconferme\Request\Exception\APIRequestException;
use BotRiconferme\Request\Exception\TimeoutException;
use CurlHandle;
use RuntimeException;

/**
 * Request done using cURL, if available
 */
class CurlRequest extends RequestBase {
	/**
	 * @inheritDoc
	 */
	protected function reallyMakeRequest( string $params ): string {
		$curl = curl_init();
		if ( $curl === false ) {
			throw new RuntimeException( 'Cannot open cURL handler.' );
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_HEADERFUNCTION, [ $this, 'headersHandler' ] );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $this->getHeaders() );

		if ( $this->method === self::METHOD_POST ) {
			curl_setopt( $curl, CURLOPT_URL, $this->url );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $params );
		} else {
			curl_setopt( $curl, CURLOPT_URL, "{$this->url}?$params" );
		}

		$result = curl_exec( $curl );

		if ( $result === false ) {
			$debugUrl = $this->getDebugURL( $params );
			if ( curl_errno( $curl ) === CURLE_OPERATION_TIMEDOUT ) {
				throw new TimeoutException( "Curl timeout for $debugUrl" );
			}
			throw new APIRequestException( "Curl error for $debugUrl: " . curl_error( $curl ) );
		}

		// Extract response body
		$headerSize = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );
		assert( is_string( $result ), 'Result must be string when RETURNTRANSFER is set' );
		$body = substr( $result, $headerSize );

		return $body;
	}

	/**
	 * cURL's headers handler
	 *
	 * @internal Only used as CB for cURL (CURLOPT_HEADERFUNCTION)
	 * @suppress PhanUnreferencedPublicMethod
	 */
	public function headersHandler( CurlHandle $ch, string $header ): int {
		$this->handleResponseHeader( $header );
		return strlen( $header );
	}
}
