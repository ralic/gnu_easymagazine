<?php include("sidebar.php");?>

<div id="content">

<div class="post">

    <h1>People</h1>

    <? foreach($this->people  as $user) { ?>

    <div class="date"><h4><a href="<?= URIMaker::articlesperson($user) ?>"><?= $user->getName() ?></a></h4></div>

    <div class="entry">
         <? if ($user->imageExists()) { ?>
            <img src="<?= URIMaker::fromBasePath($user->imagePath()) ?>" width="60" align="left">
         <? } ?>

         <?= $user->getBody() ?>
    </div>

    <? } ?>

</div>


<?php

echo "<img src=\"contents/templates/default/example.png\">";

?>
