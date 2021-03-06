<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access
defined('_JEXEC') or die('Restricted index access');

// Load template framework
if (!defined('JSN_PATH_TPLFRAMEWORK')) {
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/jsntplframework.defines.php';
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/libraries/joomlashine/loader.php';
}

// Preparing template parameters
JSNTplTemplateHelper::prepare();

// Get template utilities
$jsnutils = JSNTplUtils::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- <?php echo $this->template . ' ' . JSNTplHelper::getTemplateVersion($this->template); ?> -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<!-- html5.js and respond.min.js for IE less than 9 -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="<?php echo JURI::root(true) ?>/plugins/system/jsntplframework/assets/3rd-party/respond/respond.min.js"></script>
	<![endif]-->
	<?php
	/*====== Show analytics code configured in template parameter ======*/
	if ($this->codePosition == 0) echo $this->codeAnalytic;
	?>
    <style>.contenedor-popup{
  display:none;
  position:fixed;
  background:#fff;
  width:1000px;
  height:500px;
  top:100px;
  left:50%;
  margin-left:-500px;
  text-align:center;
  z-index:1000;
}
.show{
  display:block
}

.contenedor-popup span{
  cursor:pointer;
position:absolute;
font-size:45px;
color: #498a46;
right: -25px;
top: -25px;
line-height: 20px;
font-weight: bold;
background: #fff;
border-radius: 50%;
height: 30px;
width: 30px;
padding: 10px;
box-shadow: -3px 1px 3px rgba(3, 3, 3, 0.66);
  }
#iframe-mapa{
	width: 100%;
	height: 103%;
	border: 5px solid #122c43
}
#pop{
-webkit-transform: rotate(-90deg);
-moz-transform: rotate(-90deg);
-o-transform: rotate(-90deg);
-ms-transform: rotate(-90deg) ;
transform: rotate(-90deg) ;
position: fixed;
right: -2px;
top:50%;
/* width: 150px; */
border:1px solid #4ea04b;
margin: -25px -108px;
background:  #498a46;
color:#fff;
font-weight: 900;
text-align: center;
padding: 10px 44px 10px 40px;
display: block;
z-index: 1000;
font-size: 20px
}
#pop:hover{
background:  rgba(255,255,255,.2);
border:1px solid rgba(255,255,255,.2);
text-decoration: none
}
@media screen and (max-width:800px){  
#pop{
display:none
}

</style>
          <meta property="og:url" content="http://redplanetacolombia.com"/>
    <meta property="og:title" content="Red Planeta "/>
    <meta property="og:description"
          content="Red planeta es una red social de colombianos en el extranjero, te invitamos a unirte y ha compartir tus experiencias, ¡UNETE!."/>
    <meta property="og:image" content="http://redplanetacolombia.com/templates/jsn_metro_pro/images/ogfacebook.jpg"/>
</head>
<body id="jsn-master" class="<?php echo $this->bodyClass ?>">
<a href="#" title="PopUp" id="pop">SELECCIONA TU PAÍS</a>
<div class="contenedor-popup"><span id="close">x</span><iframe id="iframe-mapa" src="http://192.185.147.123/~planet/mapaRedPlaneta/"></iframe></div>
	<a name="top" id="top"></a>
	<div id="jsn-page">
	<?php
		/*====== Show modules in position "stick-lefttop" ======*/
		if ($jsnutils->countModules('stick-lefttop') > 0) {
	?>
		<div id="jsn-pos-stick-lefttop">
			<jdoc:include type="modules" name="stick-lefttop" style="jsnmodule" />
		</div>
	<?php
		}

		/*====== Show modules in position "stick-leftmiddle" ======*/
		if ($jsnutils->countModules('stick-leftmiddle') > 0) {
	?>
		<div id="jsn-pos-stick-leftmiddle">
			<jdoc:include type="modules" name="stick-leftmiddle" style="jsnmodule" />
		</div>
	<?php
		}

		/*====== Show modules in position "stick-leftbottom" ======*/
		if ($jsnutils->countModules('stick-leftbottom') > 0) {
	?>
		<div id="jsn-pos-stick-leftbottom">
			<jdoc:include type="modules" name="stick-leftbottom" style="jsnmodule" />
		</div>
	<?php
		}

		/*====== Show modules in position "stick-righttop" ======*/
		if ($jsnutils->countModules('stick-righttop') > 0) {
	?>
		<div id="jsn-pos-stick-righttop">
			<jdoc:include type="modules" name="stick-righttop" style="jsnmodule" />
		</div>
	<?php
		}

		/*====== Show modules in position "stick-rightmiddle" ======*/
		if ($jsnutils->countModules('stick-rightmiddle') > 0) {
	?>
		<div id="jsn-pos-stick-rightmiddle">
			<jdoc:include type="modules" name="stick-rightmiddle" style="jsnmodule" />
		</div>
	<?php
		}

		/*====== Show modules in position "stick-rightbottom" ======*/
		if ($jsnutils->countModules('stick-rightbottom') > 0) {
	?>
		<div id="jsn-pos-stick-rightbottom">
			<jdoc:include type="modules" name="stick-rightbottom" style="jsnmodule" />
		</div>
	<?php
		}
	?>
		<div id="jsn-header">
    		<div id="jsn-header-top">
				<div id="jsn-headerright">
				<?php
					/*====== Show modules in position "top" ======*/
					if ($jsnutils->countModules('top') > 0) {
				?>
					<div id="jsn-pos-top">
						<jdoc:include type="modules" name="top" style="jsnmodule" />
					</div>
				
				<?php
					}
				?>
				</div>
				<div class="clearbreak"></div>
			</div>
			<div id="jsn-header-bottom">
				<?php
					if ($this->helper->countPositions('mainmenu', 'toolbar') || $this->textSizeSelector || $this->colorSelector || $this->widthSelector) {
				?>
				<div id="jsn-header-bottom-inner">
					<div id="jsn-logo">
				<?php
					/*====== Show top H1 tag with site name and slogan ======*/
					if ($this->enableH1)
						echo '<h1 class="jsn-topheading">' . $this->app->getCfg('sitename') . ' - ' . $this->logoSlogan . '</h1>';
	
					/*====== Show modules in position "logo" ======*/
					if ($jsnutils->countModules('logo') > 0) {
				?>
					<div id="jsn-pos-logo">
						<jdoc:include type="modules" name="logo" style="jsnmodule" />
					</div>
				<?php
					/*====== If there are NO modules in position "logo", then show logo image file "logo.png" ======*/
					} else {
						/*====== Attach link to logo image ======*/
						if (!empty($this->logoLink)) {
							echo '<a href="' . $this->logoLink . '" title="' . $this->logoSlogan . '">';
						}
	
						/*====== Show mobile logo ======*/
						if (!empty($this->mobileLogo)) {
							echo '<img src="' . $this->mobileLogo . '" alt="' . $this->logoSlogan . '" id="jsn-logo-mobile" />';
						}
	
						/*====== Show desktop logo ======*/
						if (!empty($this->logoFile)) {
							echo '<img src="' . $this->logoFile . '" alt="' . $this->logoSlogan . '" id="jsn-logo-desktop" />';
						}
	
						if ($this->logoLink != "") {
							echo '</a>';
						}
					}
				?>
				</div>
					<div id="jsn-menu">
						<?php		
							/*====== Show button to jump to mobile view if user is using mobile device ======*/
							if ($this->desktopSwitcher) {
						?>
							<span id="jsn-desktopswitch">
								<a href="#" onclick="javascript: JSNUtils.setTemplateAttribute('<?php echo $this->templatePrefix ?>','mobile','no'); return false;"></a>
							</span>
							<span id="jsn-mobileswitch">
								<a href="#" onclick="javascript: JSNUtils.setTemplateAttribute('<?php echo $this->templatePrefix ?>','mobile','yes'); return false;"></a>
							</span>
						<?php
							}
						?>
						<?php
							/*====== Show modules in position "mainmenu" ======*/
							if ($jsnutils->countModules('mainmenu') > 0) {
						?>
							<div id="jsn-pos-mainmenu">
								<jdoc:include type="modules" name="mainmenu" style="jsnmodule" />
							</div>
						<?php
							}
						?>
						<div class="clearbreak"></div>
						<?php
							/*====== Show elements in Sitetools ======*/
							if ($this->textSizeSelector || $this->widthSelector || $this->colorSelector) {
						?>
							<div id="jsn-sitetoolspanel" class="<?php echo ($this->textSizeSelector || $this->widthSelector)?' jsn-include-size':''; ?><?php echo ($this->colorSelector)?' jsn-include-color':''; ?>">
								<ul id="jsn-sitetools-<?php echo $this->sitetoolStyle; ?>">
									<li class="clearafter jsn-sitetool-control">
										<ul>
										<?php
											if ($this->textSizeSelector || $this->widthSelector) {
										?>
														<li class="clearafter jsn-selector-size">
											<?php
												if ($this->textSizeSelector) {
													foreach ($this->attributes['textsize']['type'] as $tsize) {
											?>
															<a id="jsn-selector-<?php echo $tsize; ?>" title="<?php echo JText::_('JSN_TPLFW_SITETOOLS_SELECT_TEXTSIZE').': '.JText::_('JSN_TPLFW_STYLE_FONTSIZE_' . $tsize); ?>" href="#" onclick="javascript: JSNUtils.setTemplateAttribute('<?php echo $this->templatePrefix ?>','textsize','<?php echo $tsize; ?>'); return false;"<?php echo ($tsize == $this->textSize)?' class="current"':''; ?>></a>
											<?php
													}
												}
												if ($this->widthSelector) {
													foreach ($this->attributes['width']['type'] as $tw) {
											?>
															<a id="jsn-selector-<?php echo $tw; ?>" title="<?php echo JText::_('JSN_TPLFW_SITETOOLS_SELECT_WIDTH').': '.JText::_('JSN_TPLFW_LAYOUT_' . $tw); ?>" href="#" onclick="javascript: JSNUtils.setTemplateAttribute('<?php echo $this->templatePrefix ?>','width','<?php echo $tw; ?>'); return false;"<?php echo ($tw == $this->layoutWidth)?' class="current"':''; ?>></a>
											<?php
													}
												}
											?>
														</li>
										<?php
											}
											if ($this->colorSelector) {
										?>
														<li class="clearafter jsn-selector-color">
											<?php
												foreach ($this->templateColors as $tcolor) {
											?>
															<a id="jsn-selector-<?php echo $tcolor; ?>" title="<?php echo JText::_('JSN_TPLFW_SITETOOLS_SELECT_COLOR').': '.JText::_('JSN_TPLFW_COLOR_' . $tcolor); ?>" href="#" onclick="javascript: JSNUtils.setTemplateAttribute('<?php echo $this->templatePrefix ?>','color','<?php echo $tcolor; ?>'); return false;" <?php echo ($tcolor == $this->templateColor)?' class="current"':''; ?>></a>
											<?php
												}
											?>
														</li>
										<?php
											}
										?>
										</ul>
										<a href="javascript:void(0)"></a>
									</li>
								</ul>
							</div>
						<?php
							}

							/*====== Show modules in position "toolbar" ======*/
							if ($jsnutils->countModules('toolbar') > 0) {
						?>
							<div id="jsn-pos-toolbar">
								<jdoc:include type="modules" name="toolbar" style="jsnmodule" />
							</div>
						<?php
							}
						?>
					</div>
				</div>
				<?php
					}
				?>
			</div>
		</div>
		<div class="clearbreak"></div>
		<div id="jsn-body">
			<div id="jsn-body-inner">
			<?php
				/*====== Show modules in content top area ======*/
				if ($this->helper->countPositions('promo-left', 'promo', 'promo-right', 'content-top')) {
			?>
				<?php
					/*====== Show modules in promo area ======*/
					if ($this->helper->countPositions('promo-left', 'promo', 'promo-right')) {
				?>
					<div id="jsn-promo" class="<?php echo (($this->hasPromoLeft)?'jsn-haspromoleft ':'') ?><?php echo (($this->hasPromoRight)?'jsn-haspromoright ':'') ?>">
						<div id="jsn-promo-inner">
					<?php
						/*====== Show modules in position "promo" ======*/
						if ($jsnutils->countModules('promo') > 0) {
					?>
		                    <div id="jsn-pos-promo">
		                        <jdoc:include type="modules" name="promo" style="jsnmodule" />
		                    </div>
					<?php
						}
		
						/*====== Show modules in position "promo-left" ======*/
						if ($jsnutils->countModules('promo-left') > 0) {
					?>
		                    <div id="jsn-pos-promo-left">
								<jdoc:include type="modules" name="promo-left" style="jsnmodule" />
		                    </div>
					<?php
						}
		
						/*====== Show modules in position "promo-right" ======*/
						if ($jsnutils->countModules('promo-right') > 0) {
					?>
		                    <div id="jsn-pos-promo-right">
								<jdoc:include type="modules" name="promo-right" style="jsnmodule" />
		                    </div>
					<?php
						}
					?>
						<div class="clearbreak"></div>
						</div>
					</div>
				<?php
					}
				?>
				<?php
					/*====== Show modules in position "content-top" ======*/
					if ($jsnutils->countModules('content-top') > 0) {
				?>
					<div id="jsn-content-top" class="jsn-modulescontainer jsn-horizontallayout jsn-modulescontainer<?php echo $jsnutils->countModules('content-top'); ?>">
						<div id="jsn-pos-content-top">
							<jdoc:include type="modules" name="content-top" style="jsnmodule" />
							<div class="clearbreak"></div>
						</div>
					</div>
					<div class="clearbreak"></div>
				<?php
					}
				?>
				
			<?php
				}
			?>
					<div id="jsn-content" class="<?php echo (($this->hasLeft)?'jsn-hasleft ':'') ?><?php echo (($this->hasRight)?'jsn-hasright ':'') ?><?php echo (($this->hasInnerLeft)?'jsn-hasinnerleft ':'') ?><?php echo (($this->hasInnerRight)?'jsn-hasinnerright ':'') ?>">
					<div id="jsn-content_inner">
						<div id="jsn-maincontent">
							<div id="jsn-centercol">
								<div id="jsn-centercol_inner">
									<?php
										/*====== Show modules in position "breadcrumbs" ======*/
										if ($jsnutils->countModules('breadcrumbs') > 0) {
									?>
									<div id="jsn-breadcrumbs">
										<jdoc:include type="modules" name="breadcrumbs" />
									</div>
									<?php
										}
									?>									
									<?php
										/*====== Show modules in position "user-top" ======*/
										if ($jsnutils->countModules('user-top') > 0) {
									?>
									<div id="jsn-pos-user-top" class="jsn-modulescontainer jsn-horizontallayout jsn-modulescontainer<?php echo $jsnutils->countModules('user-top'); ?>">
										<jdoc:include type="modules" name="user-top" style="jsnmodule" />
										<div class="clearbreak"></div>
									</div>
									<?php
										}
	
									/*====== Show modules in position "user1" and "user2" ======*/
									$positionCount = $this->helper->countPositions('user1', 'user2');
									if ($positionCount)
									{
										$grid_suffix = $positionCount;
									?>
									<div id="jsn-usermodules1" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
										<div id="jsn-usermodules1_inner_grid<?php echo $grid_suffix; ?>">
									<?php
										/*====== Show modules in position "user1" ======*/
										if ($jsnutils->countModules('user1') > 0) {
									?>
											<div id="jsn-pos-user1">
												<jdoc:include type="modules" name="user1" style="jsnmodule" />
											</div>
									<?php
										}
	
									/*====== Show modules in position "user2" ======*/
									if ($jsnutils->countModules('user2') > 0) {
									?>
											<div id="jsn-pos-user2">
												<jdoc:include type="modules" name="user2" style="jsnmodule" />
											</div>
									<?php
										}
									?>
											<div class="clearbreak"></div>
										</div>
									</div>
									<?php
										}
									?>
									<div id="jsn-mainbody-content" class="<?php echo (($this->hasInnerLeft)?'jsn-hasinnerleft ':'') ?><?php echo (($this->hasInnerRight)?'jsn-hasinnerright ':'') ?><?php echo ($jsnutils->countModules('mainbody-top') > 0)?' jsn-hasmainbodytop':'' ?><?php echo ($jsnutils->countModules('mainbody-bottom') > 0)?' jsn-hasmainbodybottom':'' ?><?php echo ($this->showFrontpage)?' jsn-hasmainbody':'' ?>">
										<div id="mainbody-content-inner1"><div id="mainbody-content-inner2"><div id="mainbody-content-inner3"><div id="mainbody-content-inner4">
											<div id="mainbody-content-inner">
												<?php
													/*====== Show modules in position "mainbody-top" ======*/
													if ($jsnutils->countModules('mainbody-top') > 0) {
												?>
												<div id="jsn-pos-mainbody-top" class="jsn-modulescontainer jsn-horizontallayout jsn-modulescontainer<?php echo $jsnutils->countModules('mainbody-top'); ?>">
													<jdoc:include type="modules" name="mainbody-top" style="jsnmodule" />
													<div class="clearbreak"></div>
												</div>
												<?php
													}
										
													/*====== Show mainbody ======*/
													if ($this->showFrontpage) {
												?>
												<div id="jsn-mainbody">
													<jdoc:include type="message" />
													<jdoc:include type="component" />
												</div>
												<?php
													}
													/*====== Show modules in position "mainbody-bottom" ======*/
													if ($jsnutils->countModules('mainbody-bottom') > 0) {
												?>
												<div id="jsn-pos-mainbody-bottom" class="jsn-modulescontainer jsn-modulescontainer<?php echo $jsnutils->countModules('mainbody-bottom'); ?>">
													<jdoc:include type="modules" name="mainbody-bottom" style="jsnmodule" />
													<div class="clearbreak"></div>
												</div>
												<?php
													}
												?>
											</div>
											<?php
												/*====== Show modules in position "innerleft" ======*/
												if ($jsnutils->countModules('innerleft') > 0) {
											?>
											<div id="jsn-pos-innerleft">
												<div id="jsn-pos-innerleft_inner">
													<jdoc:include type="modules" name="innerleft" style="jsnmodule" />
												</div>
											</div>
															
											<?php
												}
									
												/*====== Show modules in position "innerright" ======*/
												if ($jsnutils->countModules('innerright') > 0) {
											?>
											<div id="jsn-pos-innerright">
												<div id="jsn-pos-innerright_inner">
													<jdoc:include type="modules" name="innerright" style="jsnmodule" />
												</div>
											</div>
											<?php
												}
											?>
											<div class="clearbreak"></div>
										</div></div></div></div>
										<div class="clearbreak"></div>
									</div>
			<?php
				/*====== Show modules in position "user3" and "user4" ======*/
				$positionCount = $this->helper->countPositions('user3', 'user4');
				if ($positionCount) {
					$grid_suffix = $positionCount;
			?>
									<div id="jsn-usermodules2" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
										<div id="jsn-usermodules2_inner_grid<?php echo $grid_suffix; ?>">
				<?php
					/*====== Show modules in position "user3" ======*/
					if ($jsnutils->countModules('user3') > 0) {
				?>
											<div id="jsn-pos-user3">
												<jdoc:include type="modules" name="user3" style="jsnmodule" />
											</div>
				<?php
					}
	
					/*====== Show modules in position "user4" ======*/
					if ($jsnutils->countModules('user4') > 0) { ?>
											<div id="jsn-pos-user4">
												<jdoc:include type="modules" name="user4" style="jsnmodule" />
											</div>
				<?php
					}
				?>
											<div class="clearbreak"></div>
										</div>
									</div>
			<?php
				}
	
				/*====== Show modules in position "user-bottom" ======*/
				if ($jsnutils->countModules('user-bottom') > 0) { ?>
									<div id="jsn-pos-user-bottom" class="jsn-modulescontainer jsn-horizontallayout jsn-modulescontainer<?php echo $jsnutils->countModules('user-bottom'); ?>">
										<jdoc:include type="modules" name="user-bottom" style="jsnmodule" />
										<div class="clearbreak"></div>
									</div>
			<?php
				}
	
				/*====== Show modules in position "banner" ======*/
				if ($jsnutils->countModules('banner') > 0) {
			?>
									<div id="jsn-pos-banner">
										<jdoc:include type="modules" name="banner" style="jsnmodule" />
									</div>
			<?php
				}
			?>
								</div>
							</div>
							<div class="clearbreak"></div>
						</div>
					</div>
			<?php
				/*====== Show modules in position "left" ======*/
				if ($jsnutils->countModules('left') > 0) {
			?>
					<div id="jsn-leftsidecontent">
							<div id="jsn-leftsidecontent_inner">
								<div id="jsn-pos-left">
									<jdoc:include type="modules" name="left" style="jsnmodule" />
								</div>
							</div>
						</div>
			<?php
				}
	
				/*====== Show modules in position "right" ======*/
				if ($jsnutils->countModules('right') > 0) {
			?>
					<div id="jsn-rightsidecontent">
							<div id="jsn-rightsidecontent_inner">
								<div id="jsn-pos-right">
									<jdoc:include type="modules" name="right" style="jsnmodule" />
								</div>
							</div>
						</div>
			<?php
				}
			?>
					<div class="clearbreak"></div></div>
			</div>
		</div>
			
		<?php
		/*====== Show elements in content bottom area ======*/
		if ($this->helper->countPositions('content-bottom-over', 'content-bottom', 'user5', 'user6', 'user7')) {
		?>
		<div id="jsn-content-bottom" class="clearafter">
				<div id="jsn-content-bottom-inner">
				<?php
					/*====== Show modules in position "content-bottom" ======*/
					if ($jsnutils->countModules('content-bottom') > 0) {
				?>
					<div id="jsn-pos-content-bottom" class="jsn-modulescontainer jsn-horizontallayout jsn-modulescontainer<?php echo $jsnutils->countModules('content-bottom'); ?> clearafter">
						<jdoc:include type="modules" name="content-bottom" style="jsnmodule" />
					</div>
				<?php
					}
	
					/*====== Show modules in position "user5", "user6", "user7" ======*/
					$positionCount = $this->helper->countPositions('user5', 'user6', 'user7');
					if ($positionCount) {
						$grid_suffix = $positionCount;
				?>
					<div id="jsn-usermodules3" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
					<?php
						/*====== Show modules in position "user5" ======*/
						if ($jsnutils->countModules('user5') > 0) {
					?>
						<div id="jsn-pos-user5">
							<jdoc:include type="modules" name="user5" style="jsnmodule" />
						</div>
					<?php
						}
	
						/*====== Show modules in position "user6" ======*/
						if ($jsnutils->countModules('user6') > 0) {
					?>
						<div id="jsn-pos-user6">
							<jdoc:include type="modules" name="user6" style="jsnmodule" />
						</div>
					<?php
						}
	
						/*====== Show modules in position "user7" ======*/
						if ($jsnutils->countModules('user7') > 0) {
					?>
						<div id="jsn-pos-user7">
							<jdoc:include type="modules" name="user7" style="jsnmodule" />
						</div>
					<?php
						}
					?>
						<div class="clearbreak"></div>
					</div>
				<?php
					}
				?>
				</div>
			</div>
		<?php
			}
		?>
		<?php
			/*====== Show modules in position "footer" and "bottom" ======*/
			$positionCount = $this->helper->countPositions('footer', 'bottom');
			if ($positionCount) {
				$grid_suffix = $positionCount;
		?>
			<div id="jsn-footer">
			<div id="jsn-footer-inner">
				<div id="jsn-footermodules" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
					<?php
						/*====== Show modules in position "footer" ======*/
						if ($jsnutils->countModules('footer') > 0) {
					?>
						<jdoc:include type="modules" name="footer" style="jsnmodule" />

					<?php
						}
	
						/*====== Show modules in position "bottom" ======*/
						if ($jsnutils->countModules('bottom') > 0) {
					?>
						<jdoc:include type="modules" name="bottom" style="jsnmodule" />

					<?php
						}
					?>
					<div class="clearbreak"></div>
				</div>
			</div>
		</div>
		<?php
			}
		?>
	</div>
	<?php
		/*====== Show "Go to top" link ======*/
		if ($this->gotoTop) {
			// Get rid of hitcount=0 that may prevent the button's action
			$hitcount = JRequest::getVar('hitcount', null, 'GET');
			if ($hitcount === null) {
				$this->uri->delVar('hitcount');
			}

			$return = $this->uri->toString();
	?>
		<a id="jsn-gotoplink" href="<?php echo isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''; ?>#top">
			<span><?php echo JText::_('JSN_TPLFW_GOTO_TOP'); ?></span>
		</a>
	<?php
		}

		/*====== Show modules in position "background" ======*/
		if ($jsnutils->countModules('background') > 0) {
	?>
			<div id="jsn-pos-background">
				<jdoc:include type="modules" name="background" style="jsnmodule" />
			</div>
	<?php
		}
?>
<jdoc:include type="modules" name="debug" />
<?php
	/*====== Show analytics code configured in template parameter ======*/
	if ($this->codePosition == 1) {
		echo $this->codeAnalytic;
	}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>$('#pop').on("click",function(){$(".contenedor-popup").addClass("show")})
$('#close').on("click",function(){$(".contenedor-popup").removeClass("show")})</script>
</body>
</html>
