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

foreach ($project_links as $rule)
    echo "ln -sv $shared_path" . $rule["from"] . " $release_path" . $rule["to"] . "\n";