<?php $folderUploads = substr(Yii::getPathOfAlias('webroot.uploads.spot.'), (strpos(Yii::getPathOfAlias('webroot.uploads.spot.'), Yii::getPathOfAlias('webroot'))+strlen(Yii::getPathOfAlias('webroot'))) ) . '/'; ?>
<?php foreach ($content['keys'] as $key=>$type): ?>
	<?php if($type == 'text'): ?>
		<div class="spot-item">
			<p class="item-area item-type__text"><?php echo $this->hrefActivate($content['data'][$key]); ?></p>
		</div>
	<?php elseif($type == 'image'): ?>
		<div class="item-area text-center">
			<a href="<?php echo $folderUploads.$content['data'][$key]; ?>">
				<img src="<?php echo $folderUploads.'tmb_'.$content['data'][$key]; ?>">
			</a>
		</div>
	<?php elseif($type == 'obj'): ?>
		<a href="<?php echo $folderUploads.$content['data'][$key]; ?>" class="item-area text-center">
			<div class="file-block">
				<span><?php echo substr(strchr($content['data'][$key], '_'), 1); ?></span>
				<img src="/themes/mobile/images/icons/i-files.2x.png" width="80">
			</div>
		</a>
	<?php elseif($type == 'socnet'): ?>
		<div class="spot-item">
			<div class="item-area type-mess">
				<!-- Avatar -->
				<?php if(isset($content['data'][$key]['photo'])): ?>
					<div class="default-avatar"><img src="<?php echo $content['data'][$key]['photo']; ?>">
					</div>
				<?php endif; ?>
				<!-- Text post -->
				<?php if(isset($content['data'][$key]['last_status'])): ?>
					<p><?php echo $this->hrefActivate($content['data'][$key]['last_status']); ?></p>
				<?php endif; ?>
				<!-- Text link -->
				<?php if(isset($content['data'][$key]['link_href']) && isset($content['data'][$key]['link_text'])): ?>
				  <a href="<?php echo $this->hrefActivate($content['data'][$key]['link_href']); ?>"><?php echo $content['data'][$key]['link_text']; ?></a>
				  <?php if(isset($content['data'][$key]['link_descr'])): ?>
				    <p><?php echo $content['data'][$key]['link_descr']; ?></p>
				  <?php endif; ?>
				<?php endif; ?>
				<!-- Vimeo video -->
				<?php if(isset($content['data'][$key]['vimeo_last_video'])): ?>
				  <div class="item-area text-center" id="div_<?php echo $key; ?>">
					<iframe 
					  id="vimeo_<?php echo $key; ?>" src="http://player.vimeo.com/video/<?php echo $content['data'][$key]['vimeo_last_video']; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen>
					</iframe>
					<?php if(isset($content['data'][$key]['vimeo_last_video_counter'])):?>
					<p><?php echo Yii::t('eauth','View count: ').$content['data'][$key]['vimeo_last_video_counter']; ?></p>
					<?php endif; ?>
					<?php if(isset($content['data'][$key]['vimeo_video_width']) && isset($content['data'][$key]['vimeo_video_height'])):?>
					<script type="text/javascript">
					  $(document).ready(function(){
						$('#vimeo_<?php echo $key; ?>').width($('body').width()-<?php echo isset($content['data'][$key]['photo']) ? '146' : '80';?>);
						$('#vimeo_<?php echo $key; ?>').height(($('body').width()-<?php echo isset($content['data'][$key]['photo']) ? '146' : '80';?>)/<?php echo $content['data'][$key]['vimeo_video_width']/$content['data'][$key]['vimeo_video_height']; ?>);
					  });
					  $(window).resize(function(){
					    $('#vimeo_<?php echo $key; ?>').width($('body').width()-<?php echo isset($content['data'][$key]['photo']) ? '146' : '80';?>);
						$('#vimeo_<?php echo $key; ?>').height(($('body').width()-<?php echo isset($content['data'][$key]['photo']) ? '146' : '80';?>)/<?php echo $content['data'][$key]['vimeo_video_width']/$content['data'][$key]['vimeo_video_height']; ?>);
					  });
					</script>
				  </div>
					<?php endif; ?>
				<?php endif; ?>
				<!-- Checkin -->
				<?php if(isset($content['data'][$key]['venue_name'])): ?>
				  <div class="item-area text-center">
				  <p><?php echo Yii::t('eauth','в ').$content['data'][$key]['venue_name']?><?php if(isset($content['data'][$key]['venue_address'])): ?>, <?php echo $content['data'][$key]['venue_address']?><?php endif; ?>
				  </p>
				  <?php if(isset($content['data'][$key]['checkin_shout'])): ?>
				    <p><?php echo $content['data'][$key]['checkin_shout']?></p>
				  <?php endif; ?>
				  <?php if(isset($content['data'][$key]['checkin_date'])): ?>
				    <p><?php echo $content['data'][$key]['checkin_date']?></p>
				  <?php endif; ?>
				  <?php if(isset($content['data'][$key]['checkin_photo'])): ?>
				    <img src="<?php echo $content['data'][$key]['checkin_photo']; ?>">
				  <?php endif; ?>
				  </div>
				<?php endif; ?>
				<!-- Image -->
				<?php if(isset($content['data'][$key]['last_img'])): ?>
					<div class="item-area text-center">
						<?php if(isset($content['data'][$key]['last_img_href'])): ?>
						<a href="<?php echo $content['data'][$key]['last_img_href']; ?>">
						<?php endif; ?>
						<?php if(isset($content['data'][$key]['last_img_msg'])): ?>
							<p><?php echo $this->hrefActivate($content['data'][$key]['last_img_msg']); ?></p>
						<?php endif; ?>
						<img src="<?php echo $content['data'][$key]['last_img']; ?>">
						<?php if(isset($content['data'][$key]['last_img_story'])): ?>
							<p><?php echo $this->hrefActivate($content['data'][$key]['last_img_story']); ?></p>
						<?php endif; ?>
						<?php if(isset($content['data'][$key]['last_img_href'])): ?>
						</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<!-- Map -->
				<?php if(isset($content['data'][$key]['place_lat']) && isset($content['data'][$key]['place_lng'])): ?>
					<div class="item-area text-center">
						<?php if(isset($content['data'][$key]['place_msg'])): ?>
							<p><?php echo $content['data'][$key]['place_msg']; ?></p>
						<?php endif; ?>
						<div id="map_canvas_<?php echo $key; ?>" style="width:400px; height:200px; margin:0 auto"></div>
						<script type="text/javascript">
							var initLat = <?php echo $content['data'][$key]['place_lat']; ?>;
							var initLng = <?php echo $content['data'][$key]['place_lng']; ?>;
							var latlng = new google.maps.LatLng(initLat, initLng);

							var mapOptions = {
							  zoom: 9,
							  center: latlng,
							  mapTypeId: google.maps.MapTypeId.ROADMAP
							}
							map = new google.maps.Map(document.getElementById('map_canvas_<?php echo $key; ?>'), mapOptions);

							marker = new google.maps.Marker({
								map: map,
								position: latlng,
								draggable: false
							});
						</script>
						<p><?php echo $content['data'][$key]['place_name']; ?></p>
					</div>
				<?php endif; ?>
				<!-- YouTube video -->
				<?php if(isset($content['data'][$key]['ytube_video_link'])) {?>
					<?php if(isset($content['data'][$key]['ytube_video_flash'])) {?>
						<div class="item-area text-center">
						<object>
						  <param name="movie" value="<?php echo $content['data'][$key]['ytube_video_flash']; ?>"></param>
						  <param name="allowFullScreen" value="true"></param>
						  <embed class="yt_player" id="player_<?php echo $key; ?>" src="<?php echo $content['data'][$key]['ytube_video_flash']; ?>"
							type="application/x-shockwave-flash"
							<?php if(isset($content['data'][$key]['ytube_video_rel'])): ?>
							width="100%" height="480"
							<?php else: ?>
							width="120" height="90"
							<?php endif; ?>
							allowfullscreen="true"></embed>
						</object>
						<?php if(isset($content['data'][$key]['ytube_video_rel'])): ?>
						<script type="text/javascript">
							$(document).ready(function(){
								$('#player_<?php echo $key; ?>').height($('#player_<?php echo $key; ?>').width()/<?php echo $content['data'][$key]['ytube_video_rel']; ?>);
							});
							$(window).resize(function(){
								$('#player_<?php echo $key; ?>').height($('#player_<?php echo $key; ?>').width()/<?php echo $content['data'][$key]['ytube_video_rel']; ?>);
							});
						</script>
						<?php endif; ?>
						<?php if(isset($content['data'][$key]['ytube_video_view_count'])):?>
							<p><?php echo Yii::t('eauth','View count: ').$content['data'][$key]['ytube_video_view_count']; ?></p>
						<?php endif; ?>
						</div>
					<?php }?>
				<?php }?>
				<!-- Follow button -->
				<?php if(isset($content['data'][$key]['soc_url'])): ?>
					<a href="<?php echo $content['data'][$key]['soc_url']; ?>" class="spot-button soc-link" >
					<span><?php echo $content['data'][$key]['invite']; ?></span> 
					<?php if(isset($content['data'][$key]['inviteClass']) && (strlen($content['data'][$key]['inviteClass']) > 0) ): ?>
						<i class="i-soc <?php echo $content['data'][$key]['inviteClass']; ?> round"><?php if(isset($content['data'][$key]['inviteValue']) && (strlen($content['data'][$key]['inviteValue']) > 0))echo $content['data'][$key]['inviteValue']; ?></i>
					<?php endif; ?>		
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>