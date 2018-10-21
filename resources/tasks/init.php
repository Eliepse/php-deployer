<?php

/**
 * @var string $project_path
 * @var string $project_repository
 * @var string $project_branch
 * @var string $shared_path
 * @var string $releases_path
 * @var string $release_path
 * @var array $project_shared_folders
 * @var array $project_shared_files
 */

echo "if [ ! -d $project_path ]; then mkdir $project_path; echo \"Created folder $project_path\"; fi\n";
echo "if [ ! -d $releases_path ]; then mkdir $releases_path; echo \"Created folder $releases_path\"; fi\n";
echo "if [ ! -d $shared_path ]; then mkdir $shared_path; echo \"Created folder $shared_path\"; fi\n";

include 'release.php';

include 'clean.php';
