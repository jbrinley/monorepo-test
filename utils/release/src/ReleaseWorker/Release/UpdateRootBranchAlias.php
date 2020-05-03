<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Release;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

class UpdateRootBranchAlias implements ReleaseWorkerInterface, StageAwareInterface {
	use ReleaseStageTrait;

	/**
	 * @var DevMasterAliasUpdater
	 */
	private $devMasterAliasUpdater;

	/**
	 * @var ComposerJsonProvider
	 */
	private $composerJsonProvider;

	/**
	 * @var VersionUtils
	 */
	private $versionUtils;

	public function __construct(
		DevMasterAliasUpdater $devMasterAliasUpdater,
		ComposerJsonProvider $composerJsonProvider,
		VersionUtils $versionUtils
	) {
		$this->devMasterAliasUpdater = $devMasterAliasUpdater;
		$this->composerJsonProvider  = $composerJsonProvider;
		$this->versionUtils          = $versionUtils;
	}

	public function getPriority(): int {
		return 99;
	}

	public function work( Version $version ): void {
		$nextAlias = $this->versionUtils->getNextAliasFormat( $version );

		$this->devMasterAliasUpdater->updateFileInfosWithAlias(
			[ $this->composerJsonProvider->getRootFileInfo() ],
			$nextAlias
		);
	}

	public function getDescription( Version $version ): string {
		$nextAlias = $this->versionUtils->getNextAliasFormat( $version );

		return sprintf( 'Set branch alias "%s" in root composer.json', $nextAlias );
	}
}
