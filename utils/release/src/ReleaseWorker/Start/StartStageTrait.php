<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Start;

trait StartStageTrait {
	public function getStage(): string {
		return 'start';
	}
}
