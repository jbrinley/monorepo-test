<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

abstract class AbstractReleaseWorkerDecorator implements ReleaseWorkerInterface {
	/**
	 * @var ReleaseWorkerInterface
	 */
	private $worker;

	public function __construct( ReleaseWorkerInterface $worker ) {
		$this->worker = $worker;
	}

	public function getDescription( Version $version ): string {
		return $this->worker->getDescription( $version );
	}

	public function getPriority(): int {
		return $this->worker->getPriority();
	}

	public function work( Version $version ): void {
		$this->worker->work( $version );
	}


}
