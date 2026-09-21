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

use BotRiconferme\ContextSource;
use BotRiconferme\Message\MessageProvider;
use BotRiconferme\TaskHelper\Status;
use BotRiconferme\TaskHelper\TaskDataProvider;
use BotRiconferme\TaskHelper\TaskResult;
use BotRiconferme\Wiki\Page\PageBotList;
use BotRiconferme\Wiki\WikiGroup;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * Base framework for all kind of tasks and subtasks
 */
abstract class TaskBase extends ContextSource {
	/** @var string[] */
	protected array $errors = [];

	/**
	 * Final to keep calls linear in the TaskManager
	 */
	final public function __construct(
		LoggerInterface $logger,
		WikiGroup $wikiGroup,
		MessageProvider $mp,
		PageBotList $pbl,
		protected TaskDataProvider $dataProvider,
	) {
		parent::__construct( $logger, $wikiGroup, $mp, $pbl );
	}

	/**
	 * Entry point
	 */
	final public function run(): TaskResult {
		$class = ( new ReflectionClass( $this ) )->getShortName();
		$opName = $this->getOperationName();
		$this->getLogger()->info( "Starting $opName $class" );

		$status = $this->runInternal();

		$msg = match ( $status ) {
			Status::GOOD => ucfirst( $opName ) . " $class completed successfully.",
			Status::NOTHING => ucfirst( $opName ) . " $class: nothing to do.",
			// We're fine with it, but don't run other tasks
			Status::ERROR => ucfirst( $opName ) . " $class completed with warnings."
		};

		$this->getLogger()->info( $msg );
		return new TaskResult( $status, $this->errors );
	}

	/**
	 * Actual main routine.
	 */
	abstract protected function runInternal(): Status;

	/**
	 * How this operation should be called in logs
	 */
	abstract public function getOperationName(): string;

	protected function getDataProvider(): TaskDataProvider {
		return $this->dataProvider;
	}
}
