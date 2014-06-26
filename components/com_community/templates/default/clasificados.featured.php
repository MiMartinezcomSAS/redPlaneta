<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();

$mainframe	= JFactory::getApplication();
$jinput 	= $mainframe->input;

if ($clasificados && $showFeatured) {

?>
	<!-- Slider Kit compatibility -->
		<!--[if IE 6]><?php CAssets::attach('assets/featuredslider/sliderkit-ie6.css', 'css'); ?><![endif]-->
		<!--[if IE 7]><?php CAssets::attach('assets/featuredslider/sliderkit-ie7.css', 'css'); ?><![endif]-->
		<!--[if IE 8]><?php CAssets::attach('assets/featuredslider/sliderkit-ie8.css', 'css'); ?><![endif]-->

		<!-- Slider Kit scripts -->
		<?php
			CAssets::attach('assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js', 'js');
			CAssets::attach('assets/joms.jomSelect.js', 'js');
		?>

		<!-- Slider Kit launch -->
		<script type="text/javascript">
			joms.jQuery(window).load(function(){

				<?php if($jinput->get('limitstart')!="" || $jinput->get('sort')!="" || $jinput->get('categoryid') != ""){?>
					if(joms.jQuery("#lists").length){
						var target_offset = joms.jQuery("#lists").offset();
						var target_top = target_offset.top;
						joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
					}
				<?php } ?>

				jax.call('community' , 'clasificados,ajaxShowClasificadoFeatured' , <?php echo $clasificados[0]->id; ?>, '<?php echo $allday; ?>' );

				joms.jQuery(".featured-clasificado").sliderkit({
					shownavitems:3,
					scroll:<?php echo $config->get('featuredclasificadoscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
					var clasificado_id = joms.jQuery(this).parent().attr('id');
					clasificado_id = clasificado_id.split("cPhoto");
					clasificado_id = clasificado_id[1];
					jax.call('community' , 'clasificados,ajaxShowClasificadoFeatured' , clasificado_id, '<?php echo $allday; ?>' );
				});



			});

			function updateClasificado(clasificadoId, title, categoryName, likes, avatar, clasificadoDate, location, summary, clasificadoLink,rsvp, clasificadoUnfeature){
			joms.jQuery('#like-container').html(likes);
			joms.jQuery('#clasificado-title').html(title);
			joms.jQuery('#clasificado-date').html(clasificadoDate);
			joms.jQuery('#clasificado-data-location').html(location);
			joms.jQuery('#clasificado-summary').html(summary);
			joms.jQuery('.cSlider-selected').removeClass('cSlider-selected');
			joms.jQuery('#cPhoto'+clasificadoId).addClass('cSlider-selected');
			if(rsvp==""){
				joms.jQuery('#rsvp-container').html(rsvp);
			} else {
			   joms.jQuery('#rsvp').html(rsvp);
			}
			joms.jQuery('.album-actions').html(clasificadoUnfeature);
			joms.jQuery('#community-clasificado-data-category').html(categoryName);
			joms.jQuery('#clasificado-avatar').attr('src',avatar);
			clasificadoLink = clasificadoLink.replace(/\&amp;/g,'&');
			joms.jQuery('.clasificado-link').attr('href',clasificadoLink);
			}

		</script>






<div id="cFeatured" class="cFeatured-Clasificados">

	<div class="cFeaturedTop">

		<div class="clearfull">
			<div id="rsvp-container" class="cFeatured-Rsvp">
				<div id="community-clasificado-rsvp" class="cClasificado-Rsvp">
					<p><?php echo JText::_('COM_COMMUNITY_EVENTS_ATTENDING_QUESTION'); ?>&nbsp;&nbsp;</p>
					<div class="btn pull-right">
						<select onchange="joms.clasificados.submitRSVP(<?php echo $clasificados[0]->id;?>,this)">
							<?php if($clasificados[0]->getMemberStatus($my->id)==0) { ?><option class="noResponse" selected="selected"><?php echo JText::_('COM_COMMUNITY_GROUPS_INVITATION_RESPONSE')?></option> <?php }?>
							<option class="attend" <?php if($clasificados[0]->getMemberStatus($my->id) == COMMUNITY_EVENT_STATUS_ATTEND){echo "selected='selected'"; }?> value="<?php echo COMMUNITY_EVENT_STATUS_ATTEND; ?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_RSVP_ATTEND')?></option>
							<option class="notAttend" <?php if($clasificados[0]->getMemberStatus($my->id) >= COMMUNITY_EVENT_STATUS_WONTATTEND ){echo "selected='selected'"; }?> value="<?php echo COMMUNITY_EVENT_STATUS_WONTATTEND; ?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_RSVP_NOT_ATTEND')?></option>
						</select>
					</div>
				</div>
			</div><!--.rvsp-->

			<div id="community-clasificado-avatar" class="cFeatured-PageCover cFeaturedThumb cFloat-L">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificados[0]->id );?>" class="clasificado-link">
					<img id="clasificado-avatar" src="<?php echo $clasificados[0]->getAvatar( 'avatar' ); ?>" alt="<?php echo $this->escape($clasificados[0]->title);?>" />
				</a>
				<?php if( $isCommunityAdmin ){?>
				<b>
					<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $clasificados[0]->id;?>','clasificados');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
				</b>
				<?php } ?>

				<div id="like-container" class="cFeaturedLike"></div>
			</div><!--.clasificado-vatar -->

			<!-- clasificado Information -->
			<div class="cFeaturedInfo Page">
				<!-- Title -->
				<div class="cFeaturedTitle">
					<a href="<?php echo CRoute::_('index.php?option=com_community&view=clasificados&task=viewclasificado&clasificadoid=' . $clasificados[0]->id );?>" class="clasificado-link"><span id="clasificado-title"><?php echo $clasificados[0]->title; ?></span></a>
				</div>

				<ul class="cFeaturedMeta cFloatedList cResetList clearfull">
					<!-- clasificado Time -->
					<li class="clasificado-created">
						<span><?php echo JText::_('COM_COMMUNITY_EVENTS_TIME')?>:</span>
						<b id="clasificado-date"></b>
					</li>
				</ul>

				<!--clasificado Summary-->
				<div class="clasificado-summary">
					<!-- <span><?php echo JText::_('COM_COMMUNITY_EVENTS_VIEW_SUMMARY');?></span> -->
					<div id="clasificado-summary"></div>
				</div>

				<div class="cFeaturedExtra">
					<ul class="cFeaturedMeta cFloatedList cResetList clearfull">
						<li class="clasificado-category">
							<span><?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY'); ?>:</span>
							<b id="community-clasificado-data-category">
								<?php echo JText::_( $clasificados[0]->getCategoryName() ); ?>
							</b>
						</li><!--.clasificado-category-->

						<!-- Location info -->
						<li class="clasificado-location">
							<span><?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION');?>:</span>
							<b id="clasificado-data-location">
								<a href="http://maps.google.com/?q=<?php echo urlencode($clasificados[0]->location); ?>" target="_blank">
									<?php echo $clasificados[0]->location; ?>
								</a>
							</b>
						</li>
					</ul>
				</div>
			</div><!--.clasificado-info -->
		</div>
	</div><!--.clasificado-main-->

	<!-- navigation container -->
	<div class="cFeaturedBottom">
		<!--#####SLIDER#####-->
		<div class="cSlider featured-clasificado">
			<div class="cSlider-Wrap cSlider-nav">
				<div class="cSlider-Clip cSlider-nav-clip">
					<ul class="cSlider-List Clasificados cFloatedList cResetList clearfix">

						<?php foreach($clasificados as $clasificado) { ?>
						<li id="cPhoto<?php echo $clasificado->id; ?>">
							 <div id="<?php echo $clasificado->id; ?>" class="cBoxPad">
								<a href="javascript:void(0);">
								<b class="cThumb-Calendar">
									<b><?php echo CClasificadoHelper::formatStartDate($clasificado, JText::_('M') ); ?></b>
									<b><?php echo CClasificadoHelper::formatStartDate($clasificado, JText::_('d') ); ?></b>
								</b>
								<div class="cFeaturedTitle"><b><?php echo $clasificado->title;?></b></div>
								<div class="cFeaturedMeta"><?php echo $clasificado->location; ?></div>
								</a>
							</div>
						</li>
						<?php
							} // end foreach
						?>
					</ul>
				</div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
			</div>
		</div><!--.cSlider-->
	</div>

	<script type="text/javascript">
	  joms.jQuery(function(){
		joms.jQuery("select").jomSelect();
	  });
	</script>

</div><!--#cFeatured-->
<?php } ?>
