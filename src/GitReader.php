<?php

namespace MTechOrg\Gitstamp;

use Symfony\Component\Process\Exception\ExceptionInterface;
use Symfony\Component\Process\Process;

class GitReader
{
    /**
     * Resolve the short SHA of HEAD for the git repository containing $cwd.
     *
     * Git walks up from $cwd to find the repository root itself, so this
     * works when the app is a subdirectory of a checkout (monorepo layouts)
     * and when `.git` is a file rather than a directory (worktrees,
     * submodules).
     *
     * Returns null (never throws) whenever a SHA can't be resolved: not
     * inside a git repository, git not on $PATH, or the process otherwise
     * fails.
     */
    public function shortSha(string $cwd): ?string
    {
        try {
            $process = new Process(['git', 'rev-parse', '--short', 'HEAD'], $cwd);
            $process->run();

            if (! $process->isSuccessful()) {
                return null;
            }

            $sha = trim($process->getOutput());

            return $sha === '' ? null : $sha;
        } catch (ExceptionInterface) {
            return null;
        }
    }
}
