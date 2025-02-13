<div id="block-<?php echo $dataKey;?>" class="spot-item">
    <div class="item-area type-itembox">
        <div class="item-head">
            <a href="<?php echo $socContent['soc_url']; ?>" class="type-link">
                <?php $socInfo = new SocInfo;?>
                <img class="soc-icon" src="/themes/mobispot/socialmediaicons/<?php echo $socInfo->getSmallIcon($socContent['soc_url']);?>" height="36"><span class="link"><?php echo $socContent['soc_url']; ?></span>
            </a>
        </div>
        <div class="type-mess item-body">
            <div class="item-user-avatar">
            <?php if (!empty($socContent['photo'])):?>
                <img width="50" height="50" src="<?php echo $socContent['photo'] ?>">
            <?php endif ?>
            </div>
            <div class="mess-body">
                <div class="author-row">
                    <?php if (!empty($socContent['soc_username'])): ?>
                    <a class="authot-name" href="<?php echo $socContent['soc_url']; ?>"><?php echo $socContent['soc_username'] ?></a>
                    <?php endif ?>
                    <b class="time">
                    <?php if (!empty($socContent['sub-time'])):?>
                        <?php echo $socContent['sub-time']; ?>
                    <?php endif ?>
                    </b>
                    <div class="sub-line">
                        <?php if (!empty($socContent['venue_name'])): ?>
                        <span class="icon">&#xe01b;</span>
                        <?php echo Yii::t('eauth', 'at ') . $socContent['venue_name'] ?>
                        <?php endif ?>
                    </div>
                </div>
                <div class="ins-block">
                    <p>
                    <?php if (!empty($socContent['checkin_shout'])):?>
                        <?php echo $socContent['checkin_shout']; ?>
                    <?php endif ?>
                    </p>
                    <?php if (!empty($socContent['checkin_photo'])):?>
                        <img src="<?php echo $socContent['checkin_photo']; ?>">
                    <?php endif ?>
                </div>
            </div>
        </div>
            <div class="item-control">
                    <span class="move move-top"></span>
                        <div class="spot-activity">
                            <a class="button round" ng-click="unBindSocial(spot, <?php echo $dataKey; ?>, $event)">&#xe003;</a>
                            <a class="button round" ng-click="removeContent(spot, <?php echo $dataKey; ?>, $event)">&#xe00b;</a>
                        </div>
                    <span class="move move-bottom"></span>
            </div>
    </div>
</div>
