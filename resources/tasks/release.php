<?php

/**
 * @var string $project_repository
 * @var string $project_branch
 * @var string $shared_path
 * @var string $release_path
 * @var array $project_shared_folders
 * @var array $project_shared_files
 */

echo "git clone $project_repository --branch=$project_branch --depth=1 -q $release_path\n";

foreach ($project_shared_folders as $from => $to)
    echo "if [ ! -d $shared_path$to ]; then mv -v $release_path$from $shared_path$to; fi\n";

foreach ($project_shared_files as $from => $to)
    echo "if [ ! -f $shared_path$to ]; then mv -v $release_path$from $shared_path$to; fi\n";

foreach ($project_shared_folders as $from => $to)
    echo "if [ -d $release_path$from ]; then rm -rf $release_path$from; fi\n";

foreach ($project_shared_files as $from => $to)
    echo "if [ -f $release_path$from ]; then rm -rf $release_path$from; fi\n";
