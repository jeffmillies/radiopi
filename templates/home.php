<?php
$channels = mpc::playlists();
?>
<div class="container-fluid">
    <div class="row">
        <?php foreach ($channels as $channel) { ?>
            <div class="col-md-4" style="margin-top: 25px;">
                <div class="card">
                    <div class="card-body">
                        <span class="ajax btn btn-outline-info" data-command="play"
                              data-id="<?php echo $channel['ID']; ?>">Play</span> <span
                                class="h3"><?php echo $channel['Name']; ?></span>
                        <div class="row text-muted">
                            <div class="col-md-6"><?php echo $channel['Genre']; ?> </div>
                            <div class="col-md-6 text-right"> <?php echo $channel['Bitrate']; ?>kb/s
                                <small><span class="ajax btn btn-outline-danger" data-command="delete"
                                             data-id="<?php echo $channel['ID']; ?>">Delete</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
