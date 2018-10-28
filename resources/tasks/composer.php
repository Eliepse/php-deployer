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

echo "cd $release_path\n";
echo "composer install --no-interaction --optimize-autoloader --no-progress --no-dev\n";
echo "composer dump-autoload --optimize --no-dev --classmap-authoritative\n";