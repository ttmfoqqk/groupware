<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo $head_name;?></h2>
				</div>
			</div>
			<div class="row">
			
			
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">
								<i class="fa fa-circle"></i> 휴일목록
							</h4>
						</div>
						<div class="panel-body">

							<form id="company-form-list" method="post" class="form-horizontal group-border stripped" role="form">
								<div class="row">
									<div class="col-lg-3 col-md-2 col-sm-2 col-xs-4">
										<?php
											 $yearRange = 20; 	// 보여질 년도의 범위 
											 $ageLimit = 0;		// 선택되어질 년도 - 현재년 기준  x년전의 년도가 선택
											 $ageAfter = 50;	// 더 보여질 년도
											 $currentYear = date('Y');
											 $startYear = ($currentYear - $yearRange);
											 $selectYear = ($currentYear - $ageLimit); 
											 echo '<select name="year" class="fancy-select form-control" id="tfYear">';
											 foreach (range($currentYear + $ageAfter, $startYear) as $year) { 
											    $selected = ""; 
											    if($year == $selectYear) { $selected = " selected"; }
											    echo '<option' . $selected . '>' . $year . '</option>';
											 }
											 echo '</select>';
										?>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
										<div class="btn btn-primary btn-alt mr5 mb10" id="btYear">이번년도</div>
									</div>
								</div>
							
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th class="per45">휴일 내용</th>
											<th class="per30">휴일일자</th>
											<th class="per10"></th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
										<tr class="tbRow">
											<td><input type="text" class="form-control tdInfo" data-value="" value="" style="min-width: 300px; width:100%;" name="tdInfo"></input></td>
											<td><a class="text-normal">
												<div class="input-daterange input-group">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control tdDate" name="ft_start"
														id="ft_start" data-value="" value="" name="tdDate"/>
												</div>
												</a>
											</td>
											<td class="text-center">
												<div class="btn btn-success btAdd" onclick="row_controll($(this), 'add')">+</div>
												<div class="btn btn-danger " onclick="row_controll($(this), 'rm')">-</div>
											</td>
										</tr>
									<!-- 리스트 -->
								</tbody>
								</table>
								
								<!--
								<div class="panel-body" style="text-align: center;">
									<?php echo $table_num?><br><?echo $pagination;?></div>
								-->
								<div class="panel-body pull-right">
									<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="save()">저장</button>
								</div>
								 
							</form>
						</div>
					</div>
					<!-- End .panel -->
				</div>			
			
			
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->



<!-- 폼 날짜 -->
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/locales/bootstrap-datepicker.kr.js"></script>

<!-- Bootbox fast bootstrap modals -->
<script
	src="<?echo $this->config->base_url()?>html/plugins/ui/bootbox/bootbox.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_holiday.js"></script>


