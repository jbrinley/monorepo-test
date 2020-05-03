<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Release;

use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Tribe\Libs\Dev\ReleaseWorker\AbstractReleaseWorkerDecorator;

class SetNextMutualDependencies extends AbstractReleaseWorkerDecorator implements StageAwareInterface {
	use ReleaseStageTrait;

	public function __construct( SetNextMutualDependenciesReleaseWorker $worker ) {
		parent::__construct( $worker );
	}
}
