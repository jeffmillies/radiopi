<?php foreach ($data as $row) { ?>
    <div style="background: white; width: 100%; margin-bottom: 15px; border: 1px solid rgba(223,215,202,0.75); border-radius: 0.25rem; padding: 10px;">
        <div class="float-left d-inline-block">
            <button class="save-station btn btn-outline-primary"
                    data-station-id="<?php echo $row['ID']; ?>" <?php if ($row['saved']) {
                echo 'disabled';
            } ?>>
                <?php if ($row['saved']) {
                    echo 'Saved';
                } else {
                    echo 'Save';
                } ?></button>
            <button class="listen-station btn btn-outline-info" data-station-id="<?php echo $row['ID']; ?>">Listen
            </button>
            <div id="<?php echo $row['ID']; ?>" class="hidden"><?php echo json_encode($row); ?></div>
        </div>
        <div class="d-inline-block" style="margin-left: 15px;">
            <b><?php echo $row['Name']; ?>:</b><br>
            <?php echo $row['CurrentTrack']; ?><br>
            <?php echo $row['Listeners']; ?> listening at <?php echo $row['Bitrate']; ?>kb/s
        </div>
    </div>
<?php } ?>