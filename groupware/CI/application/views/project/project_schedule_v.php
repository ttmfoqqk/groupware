<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>&nbsp;</h2>
				</div>
			</div>
			<div class="row">
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무일정</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">진행일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?echo $sData?>"/>
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?echo $eData?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">이달</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">3개월</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">6개월</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_part_no" name="menu_part_no" data-method="department" data-value="<?echo $this->input->get('menu_part_no');?>" class="fancy-select form-control">
											<option value="">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="userName" class="form-control" placeholder="담당자" value="<?echo $this->input->get('userName')?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_no" name="menu_no" data-method="project" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="title" class="form-control" placeholder="제목" value="<?echo $this->input->get('title')?>">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->


							<div class="row col-xs-12">
							<?php foreach($data as $lt){?>
								<span class="label m20 mr10" style="background-color:<?php echo $lt['color']?>;vertical-align:middle;">&nbsp;&nbsp;</span><?php echo $lt['name'];?>
							<?php }?>
							
							
							<?php 
							$item['width'] = 30;
							$diff = abs(strtotime($eData) - strtotime($sData));
							
							$years = floor($diff / (365*60*60*24));
							$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
							$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
							
							$months = ($years*12) + $months;
							$yoil = array("일","월","화","수","목","금","토");
							?>
							<table class="table table-bordered table-hover" style="table-layout:fixed;width:<?php echo ($months+1)*$item['width']*30?>px;">
								<thead>
									<tr>
										<th rowspan="3" style="width:200px;vertical-align:middle;">항목</th>
										<?
										for($i=0;$i<=$months;$i++){
											$tmp_month = date('Y-m-d', strtotime($sData.'+'.$i.' month'));
											$tmp_days  = days_in_month(date_format(date_create($tmp_month), 'm'),date_format(date_create($tmp_month), 'Y'));
											echo '<th class="text-center" colspan="'.$tmp_days.'">'.date_format(date_create($tmp_month), 'Y-m').'</th>';
										}
										?>
									</tr>
									<tr>
										<?
										for($i=0;$i<=$months;$i++){
											$tmp_month = date('Y-m-d', strtotime($sData.'+'.$i.' month'));
											$tmp_days  = days_in_month(date_format(date_create($tmp_month), 'm'),date_format(date_create($tmp_month), 'Y'));
											
											for($k=1;$k<=$tmp_days;$k++){
												echo '<td style="width:'.$item['width'].'px;" class="pr0 pl0 text-center">'.$k.'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?
										for($i=0;$i<=$months;$i++){
											$tmp_month = date('Y-m-d', strtotime($sData.'+'.$i.' month'));
											$tmp_days  = days_in_month(date_format(date_create($tmp_month), 'm'),date_format(date_create($tmp_month), 'Y'));
											
											
											for($k=1;$k<=$tmp_days;$k++){
												$tmp_day   = date('Y-m-d', strtotime($tmp_month.'+'.($k-1).' day'));
												echo '<td style="width:'.$item['width'].'px;" class="pr0 pl0 text-center">'.$yoil[date('w',strtotime($tmp_day))].'</td>';
											}
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php foreach($data as $lt){?>
									<tr>
										<th><?php echo $lt['name'];?></th>
										<?
										for($i=0;$i<=$months;$i++){
											$tmp_month = date('Y-m-d', strtotime($sData.'+'.$i.' month'));
											$tmp_days  = days_in_month(date_format(date_create($tmp_month), 'm'),date_format(date_create($tmp_month), 'Y'));
											
											for($k=1;$k<=$tmp_days;$k++){
												echo '<td> </td>';
											}
										}
										?>
									</tr>
									<?php foreach($lt['list'] as $list){?>
									<tr>
										<td><?php echo $list['title'];?></td>
										<?
										for($i=0;$i<=$months;$i++){
											$tmp_month = date('Y-m-d', strtotime($sData.'+'.$i.' month'));
											$tmp_days  = days_in_month(date_format(date_create($tmp_month), 'm'),date_format(date_create($tmp_month), 'Y'));
											
											for($k=1;$k<=$tmp_days;$k++){
												$tmp_day = date('Y-m-d', strtotime($tmp_month.'+'.($k-1).' day'));
												$tmp_bg  = '';
												if( $list['sData'] <= $tmp_day && $list['eData'] >= $tmp_day ){
													$tmp_bg = $lt['color'];
												}
												echo '<td class="pl0 pr0 pt15"><div style="width:100%;height:10px;background-color:'.$tmp_bg.'"></div></td>';
											}
										}
										?>
										
									</tr>
									<?php }?>
									<?php }?>
								</tbody>
							</table>
							</div>

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
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_schedule.js"></script>