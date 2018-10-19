git clone <?= $project_repository ?> --branch=<?= $project_branch ?> --depth=1 -q <?= $release_path ?>;

<?php foreach ($project_shared_folders as $from => $to): ?>
if [ ! -d <?= $shared_path . $to ?> ]; then
    mv -v <?= $release_path . $from ?> <?= $shared_path . $to ?>;
fi

<?php endforeach; ?>

<?php foreach ($project_shared_folders as $from => $to): ?>
if [ -d <?= $release_path . $from ?> ]; then
    rm -rf <?= $release_path . $from ?>;
fi

<?php endforeach; ?>