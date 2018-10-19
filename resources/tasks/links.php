<?php foreach ($project_links as $from => $to) :?>
    ln -s -T <?= $shared_path . $from ?> <?= $release_path . $to ?>
<?php endforeach;?>