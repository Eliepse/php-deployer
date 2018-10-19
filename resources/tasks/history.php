find <?php $releases_path ?> -maxdepth 1 -name "20*" | sort | head -n <?= "-$project_release_history" ?> | xargs rm -Rf
