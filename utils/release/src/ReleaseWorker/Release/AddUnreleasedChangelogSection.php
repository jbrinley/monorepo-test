<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Release;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

class AddUnreleasedChangelogSection implements ReleaseWorkerInterface {

	public function getPriority(): int {
		return 90;
	}

	public function work( Version $version ): void {
		$changelogFilePath = getcwd() . '/CHANGELOG.md';
		if ( ! file_exists( $changelogFilePath ) ) {
			return;
		}

		$changelogFileContent = FileSystem::read( $changelogFilePath );
		$changelogFileContent = Strings::replace( $changelogFileContent, '/## /', "## Unreleased\n\n## ", 1 );

		FileSystem::write( $changelogFilePath, $changelogFileContent );
	}

	public function getDescription( Version $version ): string {
		return 'Add new "Unreleased" section in `CHANGELOG.md`';
	}
}
