<?php
$genres = shout::getGenreList();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-sm-12" style="margin-top: 10px;">
        <span class="input-group">
            <select id="parent" class="station form-control">
                <option></option>
                <?php foreach ($genres['Parent'] as $parent) { ?>
                    <option><?php echo $parent['text']; ?></option>
                <?php } ?>
            </select>

            <select class="station child form-control">
                <option></option>
            </select>
            <?php foreach ($genres as $genre => $channels) { ?>
                <?php if ($genre == 'Parent') {
                    continue;
                } ?>
                <select id="<?php echo $genre; ?>" class="station child form-control" style="display: none;">
                <option></option>
                    <?php foreach ($channels as $channel) { ?>
                        <option><?php echo $channel['text']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            </span>
        </div>
        <div class="col-md-6 col-sm-12" style="margin-top: 10px;">
            <input id="search-station" type="text" class="form-control" placeholder="Search Stations">
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div id="stations" class="col-sm-12">
        </div>
    </div>
</div>