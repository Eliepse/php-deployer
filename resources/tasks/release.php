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

foreach ($project_shared_folders as $rule)
    echo "if [ ! -d $shared_path" . $rule["to"] . " ]; then mv -v $release_path" . $rule["from"] . " $shared_path" . $rule["to"] . "; fi\n";

foreach ($project_shared_files as $rule)
    echo "if [ ! -f $shared_path" . $rule["to"] . " ]; then mv -v $release_path" . $rule["from"] . " $shared_path" . $rule["to"] . "; fi\n";

foreach ($project_shared_folders as $rule)
    echo "if [ -d $release_path" . $rule["to"] . " ]; then rm -rf $release_path" . $rule["to"] . "; fi\n";

foreach ($project_shared_files as $rule)
    echo "if [ -f $release_path" . $rule["to"] . " ]; then rm -rf $release_path" . $rule["to"] . "; fi\n";
