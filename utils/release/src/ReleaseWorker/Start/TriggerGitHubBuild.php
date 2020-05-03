<?php
declare( strict_types=1 );

namespace Tribe\Libs\Dev\ReleaseWorker\Start;

use GuzzleHttp\Psr7\Request;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Tribe\Libs\Dev\Exception\InvalidOriginException;
use Tribe\Libs\Dev\Exception\MissingGithubAuthTokenException;
use Tribe\Libs\Dev\Exception\NoOriginException;

class TriggerGitHubBuild implements ReleaseWorkerInterface, StageAwareInterface {
	use StartStageTrait;

	/**
	 * @var ProcessRunner
	 */
	private $processRunner;
	/**
	 * @var string
	 */
	private $githubAuthToken;
	/**
	 * @var string
	 */
	private $repo;

	public function __construct( ProcessRunner $processRunner, string $githubAuthToken = '' ) {
		$this->processRunner   = $processRunner;
		$this->githubAuthToken = $githubAuthToken;
		$this->repo            = $this->getRepo();
	}

	public function getDescription( Version $version ): string {
		return 'Sends a request to GitHub to trigger a release build.';
	}

	public function getPriority(): int {
		return 1000;
	}

	public function work( Version $version ): void {
		$this->validateGithubAuthToken( $this->githubAuthToken );
		$url     = $this->getDispatchUrl();
		$headers = $this->getDispatchHeaders();
		$body    = $this->getDispatchBody( $version );
		$client  = new \GuzzleHttp\Client( [ 'timeout' => 2 ] );
		$request = new Request( 'POST', $url, $headers, $body );
		$client->send( $request );
	}

	private function getDispatchUrl(): string {
		return sprintf( 'https://api.github.com/repos/%s/dispatches', $this->repo );
	}

	private function getRepo(): string {
		$origin = $this->processRunner->run( 'git config --get remote.origin.url' );
		if ( empty( $origin ) ) {
			throw new NoOriginException( 'Origin remote not configured for git repository' );
		}
		if ( preg_match( '#git@github\.com:(.*?)\.git#', $origin, $matches ) ) {
			return $matches[1];
		}
		if ( preg_match( '#https://github.com/(.*?)(\.git)?#', $origin, $matches ) ) {
			return $matches[1];
		}
		throw new InvalidOriginException( sprintf( 'Remote %s is not recognizable as a GitHub-hosted repository', $origin ) );
	}

	private function getDispatchHeaders(): array {
		return [
			'Accept'        => 'application/vnd.github.everest-preview+json',
			'Authorization' => sprintf( 'token %s', $this->githubAuthToken ),
		];
	}

	private function validateGithubAuthToken( $token ): string {
		if ( empty( $token ) ) {
			$message = 'A GitHub auth token is required to trigger a release. Follow the instructions in .env.sample';
			throw new MissingGithubAuthTokenException( $message );
		}

		return $token;
	}

	private function getDispatchBody( Version $version ): string {
		$data = [
			'event_type'     => 'trigger-release',
			'client_payload' => [
				'version' => $version->getVersionString(),
			],
		];

		return \GuzzleHttp\json_encode( $data );
	}

}
