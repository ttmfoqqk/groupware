<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo MENU_NAME;?></h2>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> <?echo MENU_NAME;?></h4>
						</div>

						<div class="panel-body">
							<div class="dd" id="nestable3" data-key="<?echo $key;?>" style="padding-bottom:100px;">
								<?echo $tree;?>
							</div>

						</div>
						<div class="panel-body">
							<button id="category_add" type="button" class="btn btn-primary btn-alt mr5 mb10">등록</button>
						</div>


					</div>
				</div>
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/plugins/ui/nestable/jquery.nestable.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_organization.js"></script>