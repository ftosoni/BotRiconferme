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

namespace BotRiconferme\Task\Subtask;

use BotRiconferme\TaskHelper\Status;
use BotRiconferme\Wiki\Page\PageRiconferma;

/**
 * For each open page, protect it, add a closing text if it was a vote, and
 * update the text in the base page
 */
class ClosePages extends Subtask {
	/**
	 * @inheritDoc
	 */
	public function runInternal(): Status {
		$pages = $this->getDataProvider()->getPagesToClose();

		if ( !$pages ) {
			return Status::NOTHING;
		}

		$protectReason = $this->msg( 'close-protect-summary' )->text();
		foreach ( $pages as $page ) {
			if ( $page->isVote() ) {
				$this->addVoteCloseText( $page );
			}
			$this->getWiki()->protectPage( $page->getTitle(), $protectReason );
			$this->updateBasePage( $page );
		}

		return Status::GOOD;
	}

	protected function addVoteCloseText( PageRiconferma $page ): void {
		$content = $page->getContent();
		$beforeReg = '!è necessario ottenere una maggioranza .+ votanti\.!u';
		$newContent = preg_replace( $beforeReg, '$0' . "\n" . $page->getOutcomeText(), $content );

		$page->edit( [
			'text' => $newContent,
			'summary' => $this->msg( 'close-result-summary' )->text()
		] );
	}

	/**
	 * @see CreatePages::updateBasePage()
	 */
	protected function updateBasePage( PageRiconferma $page ): void {
		$this->getLogger()->info( "Updating base page for $page" );

		if ( $page->getNum() === 1 ) {
			$basePage = $this->getUser( $page->getUserName() )->getBasePage();
		} else {
			$basePage = $this->getUser( $page->getUserName() )->getExistingBasePage();
		}

		$current = $basePage->getContent();

		$outcomeText = $page->getOutcome()->isFailure() ?
			'non riconfermato' :
			'riconfermato';
		$text = $page->isVote() ? "votazione di riconferma: $outcomeText" : 'riconferma tacita';

		$newContent = preg_replace( '/^(#: *)(votazione di )?riconferma in corso/m', '$1' . $text, $current );

		$basePage->edit( [
			'text' => $newContent,
			'summary' => $this->msg( 'close-base-page-summary-update' )->text()
		] );
	}
}
