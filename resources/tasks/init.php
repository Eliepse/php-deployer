if [ ! -d <?= $project_path ?> ]; then
    mkdir <?= $project_path ?>;
    echo "Created folder <?= $project_path ?>";
fi

if [ ! -d <?= $releases_path ?> ]; then
    mkdir <?= $releases_path ?>;
    echo "Created folder <?= $releases_path ?>";
fi

if [ ! -d <?= $shared_path ?> ]; then
    mkdir <?= $shared_path ?>;
    echo "Created folder <?= $shared_path ?>";
fi

<?php include 'release.php' ?>

rm -rf <?= $release_path ?>
