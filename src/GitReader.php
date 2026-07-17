<?php

namespace MTechOrg\Gitstamp;

use Symfony\Component\Process\Exception\ExceptionInterface;
use Symfony\Component\Process\Process;

class GitReader
{
    /**
     * Resolve the short SHA of HEAD for the git repository at $cwd.
     *
     * Returns null (never throws) whenever a SHA can't be resolved: no
     * `.git` directory, git not on $PATH, or the process otherwise fails.
     */
    public function shortSha(string $cwd): ?string
    {
        if (! is_dir($cwd.'/.git')) {
            return null;
        }

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
