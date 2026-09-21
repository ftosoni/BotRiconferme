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

namespace BotRiconferme\TaskHelper;

/**
 * Object wrapping the result of the execution of a task.
 */
class TaskResult {
	/**
	 * @param Status $status
	 * @param string[] $errors
	 */
	public function __construct(
		private Status $status,
		private array $errors = []
	) {
	}

	public function getStatus(): Status {
		return $this->status;
	}

	/**
	 * @return string[]
	 * @suppress PhanUnreferencedPublicMethod
	 */
	public function getErrors(): array {
		return $this->errors;
	}

	public function merge( TaskResult $that ): void {
		$this->status = $that->status->combinedWith( $that->status );
		$this->errors = array_merge( $this->errors, $that->errors );
	}

	public function __toString(): string {
		if ( $this->isOK() ) {
			$stat = 'OK';
			$errs = "\tNo errors.";
		} else {
			$stat = 'ERROR';
			$formattedErrs = [];
			foreach ( $this->errors as $err ) {
				$formattedErrs[] = "\t - $err";
			}
			$errs = implode( "\n", $formattedErrs );
		}
		return "=== RESULT ===\n - Status: $stat\n - Errors:\n$errs\n";
	}

	/**
	 * Shorthand
	 */
	public function isOK(): bool {
		return $this->status->isOK();
	}
}
