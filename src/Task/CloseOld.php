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

use BotRiconferme\Task\Subtask\ArchivePages;
use BotRiconferme\Task\Subtask\ClosePages;
use BotRiconferme\Task\Subtask\FailedUpdates;
use BotRiconferme\Task\Subtask\SimpleUpdates;
use BotRiconferme\TaskHelper\Status;

/**
 * Task for closing old procedures
 */
class CloseOld extends Task {
	/**
	 * @inheritDoc
	 */
	public function runInternal(): Status {
		$orderedList = [
			'close-pages',
			'archive-pages',
			'simple-updates',
			'failed-updates'
		];

		return $this->runSubtaskList( $orderedList );
	}

	/**
	 * @inheritDoc
	 */
	protected function getSubtasksMap(): array {
		return [
			'archive-pages' => ArchivePages::class,
			'close-pages' => ClosePages::class,
			'failed-updates' => FailedUpdates::class,
			'simple-updates' => SimpleUpdates::class
		];
	}
}
