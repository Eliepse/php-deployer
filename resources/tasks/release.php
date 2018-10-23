<?php

/**
 * @var string $project_path
 * @var string $project_repository
 * @var string $project_branch
 * @var string $shared_path
 * @var string $releases_path
 * @var string $release_path
 * @var int $project_release_history
 * @var array $project_shared_folders
 * @var array $project_shared_files
 * @var array $project_links
 * @var string $test
 */

echo "git clone $project_repository --branch=$project_branch --depth=1 -q $release_path\n";

foreach ($project_shared_folders as $from => $to)
    echo "if [ ! -d $shared_path$to ]; then mv -v $release_path$from $shared_path$to; fi\n";

foreach ($project_shared_files as $from => $to)
    echo "if [ ! -f $shared_path$to ]; then mv -v $release_path$from $shared_path$to; fi\n";

foreach ($project_shared_folders as $from => $to)
    echo "if [ -d $release_path$to ]; then rm -rf $release_path$to; fi\n";

foreach ($project_shared_files as $from => $to)
    echo "if [ -f $release_path$to ]; then rm -rf $release_path$to; fi\n";
