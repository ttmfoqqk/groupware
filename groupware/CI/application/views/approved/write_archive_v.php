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
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 결재등록</h4>
						</div>
						<div class="panel-body">

							<form id="project-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
							
							<?if( $action_type != 'edit' ) {?>
							<div class="row pb20">
								<label for="menu_part_no" class="col-lg-2 col-md-3 control-label">결재 종류</label>
								<div class="col-lg-7 col-md-6 col-xs-8">
									<select id="approved-kind" name="approved-kind" class="fancy-select form-control">
										<option value="0">업무</option>
										<option value="1">일반</option>
									</select>
								</div>
								<div class="col-lg-3 col-md-3 col-xs-4">
									<button type="button" class="btn btn-primary btn-alt" id="call-approved-kind">불러오기</button>
								</div>
							</div>
							<?}?>

							<!-- 업무보고서 -->
							<div style="display:none;" id="project-layout">
							<div class="panel panel-default">
								<div class="panel-body">
									<h3 class="text-center mb25">업무보고서</h3>
									
									<div class="col-xs-10 col-xs-offset-1 mb20">
										<table class="table table-bordered table-border-black">
											<tbody>
												<tr>
													<th style="width:200px;">문서번호</th>
													<td style="width:400px;">Jacob Olsen</td>
													<th style="width:200px;">등록일자</th>
													<td><?echo Date('Y-m-d');?></td>
												</tr>
												<tr>
													<th>담당부서</th>
													<td>기획팀</td>
													<th>담당자</th>
													<td>홍길동</td>
												</tr>
												<tr>
													<th>분류</th>
													<td>내부 > 업무</td>
													<th>제목</th>
													<td>7月 바이럴 목표</td>
												</tr>
												<tr>
													<th>내용</th>
													<td colspan="3">7月 바이럴 목표</td>
												</tr>
												<tr>
													<th>진행기간</th>
													<td colspan="3">2015-06-19 ~ 2015-08-19</td>
												</tr>
												<tr>
													<th>결재점수</th>
													<td>1</td>
													<th>누락점수</th>
													<td>-1</td>
												</tr>
												<tr>
													<th>첨부파일</th>
													<td>123</td>
													<th >순서</th>
													<td class="p5"><input type="text" name="order" class="form-control input-sm input-mini" value="0"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-xs-10 col-xs-offset-1">
										<textarea id="contents" name="contents" class="form-control" rows="10" placeholder="내용"></textarea>
									</div>
								</div>
							</div>
							</div>
							<!-- 업무보고서 -->

							<!-- 일반보고서 -->
							<div style="display:none;" id="document-layout">
							<div class="panel panel-default">
								<div class="panel-body">
									<h3 class="text-center mb25">문서이름</h3>
									
									<div class="col-xs-10 col-xs-offset-1 mb20">
										<table class="table table-bordered table-border-black">
											<tbody>
												<tr>
													<th style="width:200px;">문서번호</th>
													<td style="width:400px;">Jacob Olsen</td>
													<th style="width:200px;">등록일자</th>
													<td>2015-06-19</td>
												</tr>
												<tr>
													<th>담당부서</th>
													<td class="p5">
														<select id="part_sender" name="part_sender" data-method="department" data-value="<?echo $this->input->get('part_sender');?>" class="fancy-select form-control input-sm">
															<option value="">담당부서</option>
														</select>
													</td>
													<th>담당자</th>
													<td>홍길동</td>
												</tr>
												<tr>
													<th>제목</th>
													<td colspan="3" class="p5">
														<input type="text" name="title" class="form-control input-sm" placeholder="제목" value="<?echo $this->input->get('title');?>">
													</td>
												</tr>
												<tr>
													<th>진행기간</th>
													<td colspan="3" class="p5">
														
														<div class="col-xs-6 col-md-6 row">
															<div class="input-daterange input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control input-sm" name="sData" id="sData" value="<?echo $this->input->get('sData')?>" />
																<span class="input-group-addon">to</span>
																<input type="text" class="form-control input-sm" name="eData" id="eData" value="<?echo $this->input->get('eData')?>"/>
															</div>
														</div>
														<div class="col-xs-6 col-md-6">
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
														</div>
														
													</td>
												</tr>
												<tr>
													<th>첨부파일</th>
													<td class="p5">
														<input type="file" id="userfile" name="userfile" class="filestyle" data-size="sm" data-buttonText="Find file" data-buttonName="btn-danger" data-iconName="fa fa-plus">
													</td>
													<th>순서</th>
													<td class="p5"><input type="text" name="order" class="form-control input-sm input-mini" value="0"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-xs-10 col-xs-offset-1">
										<textarea id="contents" name="contents" class="form-control" rows="10" placeholder="내용"></textarea>
									</div>
								</div>
							</div>
							</div>
							<!-- 일반보고서 -->
							
							
							<div class="panel-body pull-left">
								<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
							</div>
							<div class="panel-body pull-right">
								<?if( $action_type == 'edit' ) {?>
								<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								<?}?>
								<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
							</div>

							</form>
						</div>


					</div>
					<!-- End .panel -->
				</div>
				<!-- col-lg-12 end here -->
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_approved.js"></script>