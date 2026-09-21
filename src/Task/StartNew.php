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

namespace BotRiconferme\Task;

use BotRiconferme\Task\Subtask\CreatePages;
use BotRiconferme\Task\Subtask\OpenUpdates;
use BotRiconferme\Task\Subtask\UserNotice;
use BotRiconferme\TaskHelper\Status;

/**
 * Task for opening new procedures
 */
class StartNew extends Task {
	/**
	 * @inheritDoc
	 */
	public function runInternal(): Status {
		$orderedList = [
			'create-pages',
			'open-updates',
			'user-notice'
		];

		return $this->runSubtaskList( $orderedList );
	}

	/**
	 * @inheritDoc
	 */
	protected function getSubtasksMap(): array {
		return [
			'create-pages' => CreatePages::class,
			'open-updates' => OpenUpdates::class,
			'user-notice' => UserNotice::class,
		];
	}
}
