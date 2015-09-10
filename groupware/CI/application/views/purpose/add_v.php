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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 추가평점목록</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">적용일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?echo $this->input->get('sData')?>" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?echo $this->input->get('eData')?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="department" name="department" data-method="department" data-value="<?echo $this->input->get('department');?>" class="fancy-select form-control">
											<option value="">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">사원명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="user_name" class="form-control" placeholder="사원명">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">사유</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="title" class="form-control" placeholder="사유">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">점수</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="point" class="form-control" placeholder="점수">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->

							<!-- 등록/리스트 -->
							<form id="purpose-add-form" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="create">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
							<input type="hidden" name="in_title" id="in_title" value="">
							<input type="hidden" name="in_operator" id="in_operator" value="">
							<input type="hidden" name="in_point" id="in_point" value="">

							<table class="table table-bordered" id="tabletools">
								<thead>
									<tr>
										<th class="per20">부서</th>
										<th class="per20">사원명</th>
										<th>사유</th>
										<th class="per10">점수</th>
										<th class="per10">누적</th>
										<th class="per10">적용일자</th>
										<th class="per10">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									
									<tr>
										<td>
											<select id="in_department" name="in_department" data-method="department" data-value="" class="fancy-select form-control">
												<option value="">담당부서</option>
											</select>
										</td>
										<td>
											<select id="in_user" name="in_user" class="fancy-select form-control">
												<option value="담당부서">사원</option>
											</select>
										</td>
										<td id="text_title"> </td>
										<td id="text_point"> </td>
										<th id="text_sum"> </th>
										<td>
											<div class="input-group input-daterange">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input id="in_date" name="in_date" type="text" class="form-control">
											</div>
										</td>
										
										<td class="text-center">
											<button type="button" class="btn btn-danger" onclick="call_rule_modal();">규정보기</button>
											<button type="submit" class="btn btn-success">등록</button>
										</td>
									</tr>
									
									<?php foreach($list as $lt){?>
									<tr>
										<td><?echo $lt['menu_name'];?></td>
										<td><?echo $lt['user_name'];?></td>
										<td><?echo $lt['title'];?></td>
										<td><?echo $lt['point'];?></td>
										<td><?echo $lt['sPoint'];?></td>
										<td><?echo substr($lt['date'],0,10);?></td>
										<td>&nbsp;</td>
									</tr>
									<?}?>
									<?if( count($list) <= 0 ){?>
										<tr>
											<td colspan="7">등록된 내용이 없습니다.</td>
										</tr>
									<?}?>
									<!-- 리스트 -->
								</tbody>
							</table>

							</form>
							<!-- 등록 -->

							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>

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
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_purpose_add.js"></script>