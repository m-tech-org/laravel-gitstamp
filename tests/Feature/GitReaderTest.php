<?php

use MTechOrg\Gitstamp\GitReader;
use Symfony\Component\Process\Process;

beforeEach(function () {
    $this->dir = sys_get_temp_dir().'/gitstamp-gitreader-'.bin2hex(random_bytes(6));
    mkdir($this->dir, 0777, true);
});

afterEach(function () {
    (new Process(['rm', '-rf', $this->dir]))->run();
});

function initRepoWithCommit(string $dir): void
{
    foreach ([
        ['git', 'init'],
        ['git', '-c', 'user.email=test@example.com', '-c', 'user.name=test', 'commit', '--allow-empty', '-m', 'init'],
    ] as $command) {
        (new Process($command, $dir))->mustRun();
    }
}

it('resolves the short sha of HEAD from the repository root', function () {
    initRepoWithCommit($this->dir);

    expect((new GitReader)->shortSha($this->dir))->toMatch('/^[0-9a-f]{7,}$/');
});

it('resolves the short sha from a subdirectory of the repository (monorepo layout)', function () {
    initRepoWithCommit($this->dir);
    mkdir($this->dir.'/apps/backend', 0777, true);

    $reader = new GitReader;

    expect($reader->shortSha($this->dir.'/apps/backend'))
        ->not->toBeNull()
        ->toBe($reader->shortSha($this->dir));
});

it('returns null when the directory is not inside a git repository', function () {
    expect((new GitReader)->shortSha($this->dir))->toBeNull();
});
