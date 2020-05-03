<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Release;

trait ReleaseStageTrait {
	public function getStage(): string {
		return 'release';
	}
}
