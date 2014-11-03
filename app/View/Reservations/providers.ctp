<div class="provider-list">
    <?php

    if (!empty($providers)) {
        foreach ($providers as $prov) {
            ?>
            <div class="provider-single provider-single-<?php echo $prov['User']['id'] ?>">
                <?php echo $prov['User']['username'] ?>
            </div>

        <?php
        }

    } else {
        echo 'No provider found!!!';

    }
    ?>
</div>