<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Release;

use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Tribe\Libs\Dev\ReleaseWorker\AbstractReleaseWorkerDecorator;

class UpdateBranchAlias extends AbstractReleaseWorkerDecorator implements StageAwareInterface {
	use ReleaseStageTrait;

	public function __construct( UpdateBranchAliasReleaseWorker $worker ) {
		parent::__construct( $worker );
	}
}
