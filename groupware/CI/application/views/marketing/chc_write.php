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
			
				
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">CHC 등록</h4>
						</div>
						
						<!-- Start .panel-body -->
						<div class="panel-body">
							<!-- top form(member-form-write-setting) start  -->
							<form id="chc-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data">
								<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
								<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
								<h3 class="text-center mb25">CHC 보고서</h3>
								
								<div class="row col-lg-12 col-md-12 col-xs-12">
									<input type="hidden" id="isActive" value="<?php echo $data['status'] ;?>">
									<div class="radio-custom radio-inline">
                                        <input type="radio" name="is_active" value="1" id="in_active">
                                        <label for="in_active">진행</label>
                                    </div>
                                    <div class="radio-custom radio-inline">
                                       	<input type="radio" name="is_active" value="0" id="out_active">
                                       	<label for="out_active">정지</label>
                                    </div>
                                </div>
								<!-- START .col-lg-12 col-md-12 col-xs-12 -->
								<div class="col-lg-12 col-md-12 col-xs-12">
									<br></br>
									<table class="table table-bordered">
										<tbody>
											<input type="hidden" id="menu_k" name="menu_k" value="<?php echo isset($data['menu_kind']) ? $data['menu_kind'] : '';?>"></input>
											<tr>
												<th class="th-size">문서번호</td>
												<td id="docNo"><?php echo $data['no'];?></td>
												<th class="th-size">등록일자</td>
												<td id="created"><?php echo $data['created'];?></td>
											</tr>
											<tr>
												<th class="th-size">담당부서</td>
												<td id="department">
													<?php echo isset($data['department_name']) ? $data['department_name'] : '';?>
												</td>
												<th class="th-size">담당자</td>
												<td id="user"><?php  echo isset($data['user_name']) ? $data['user_name'] : '' ;?></td>
											</tr>
											<tr>
												<th class="th-size">분류</td>
												<td id="menu_kind" name="menu_kind">
													<?php echo isset($data['menu_kind']) ? $data['menu_kind'] : '';?>
												</td>
												<th class="th-size"><font class="red">* </font>제목</td>
												<td>
													<input type="hidden" id="project_no" name="project_no"></input>
													<div class="col-lg-10 col-md-10" id="title" name="title">
														<?php echo $data['title'];?>
													</div>
													<div class="col-lg-2 col-md-2">
														<div class="btn btn-primary btn-xs" id="call_approved_kind">선택</div>
													</div>
												</td>
												
											</tr>
											<tr>
												<th class="th-size">진행기간</td>
												<td colspan="3" id="period">
													<?php echo isset($data['sData']) ? date("Y-m-d", strtotime($data['sData'])) . '  ~  ' : '';
													 echo isset($data['eData']) ? date("Y-m-d", strtotime($data['eData'])) : ''; ?>
												</td>
											</tr>
											<tr>
												<th class="th-size">결제점수</td>
												<td id="pPoint"> <?php echo isset($data['pPoint']) ? $data['pPoint'] : '';?></td>
												<th class="th-size">누락점수</td>
												<td id="mPoint"> <?php echo isset($data['mPoint']) ? $data['mPoint'] : '';?></td>
											</tr>
											<tr><td class="empty" colspan="4"></td>
											<tr>
												<th class="th-size"><font class="red">* </font>고객사</td>
												<td id="partner" class="nr-pd">
													<select class="fancy-select form-control" data-method="marketingList" data-value="<?php echo $data['customer_no']?>" id="ft_commpany" name="ft_commpany">
														<option value="">고객사</option>
													</select>
												</td>
												<th class="th-size"><font class="red">* </font>키워드</th>
												<td  class="nr-pd">
													<input id="keyword" name="keyword"  class="form-control" value="<?php echo $data['keyword'];?>"></input>
												</td>
											</tr>
											<tr>
												<th class="th-size"><font class="red">* </font>URL</th>
												<td class="nr-pd">
													<input id="url" name="url" class="form-control" value="<?php echo $data['url'];?>"></input>
												</td>
												<th class="th-size"><font class="red">* </font>IP</th>
												<td class="nr-pd">
													<input id="ip" name="ip" class="form-control" value="<?php echo $data['ip'];?>"></input>
												</td>
											</tr>
											<tr><td class="empty" colspan="4"></td>
											</tr>
											<tr>
												<th class="th-size">네이버 순위</th>
												<td id="rank"><?php echo $data['rank'];?></td>
												<th class="th-size">노출률</th>
												<td id="rate"><?php echo isset($data['exposeRate']) ? $data['exposeRate'] . '%' : '-';?></td>
											</tr>
											<tr>
												<th class="th-size">작업수</th>
												<td id="historyNum"><?php echo isset($data['history']) ? count($data['history']) : '-';?></td>
												<th class="th-size">순서</th>
												<td class="nr-pd">
													<input id="order" name="order" class="form-control" value="<?php echo $data['order'];?>"></input>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!-- END .col-lg-12 col-md-12 col-xs-12 -->
								
								<!-- START .col-lg-12 col-md-12 col-xs-12 -->
								<div class="col-lg-12 col-md-12 col-xs-12">
									<br></br>
									<h4 class="panel-title pull-left">URL 히스토리</h4>
									<table class="table table-bordered" id="tbHistory">
										<thead>
											<th>URL</th>
											<th>생성시간</th>
										</thead>
										<tbody>
											<?php if(isset($data['history']) && count($data['history']) > 0){ 
												foreach ($data['history'] as $rw){
													echo '<tr><td>' . $rw['url'] . '</td><td>' . $rw['created'] . '</td></tr>';
												}  
											
											}else echo '<tr><td colspan="2">데이터가 없습니다</td></tr>'?>
										<!--
											<tr><td>빈 데이터</td><td>2015-10-10</td></tr>
											<tr><td>빈 데이터</td><td>2015-10-10</td></tr>-->
										</tbody>
									</table>
								</div>
								<!-- END .col-lg-12 col-md-12 col-xs-12 -->
								
								<!-- START .col-lg-12 col-md-12 col-xs-12 -->
								<div class="col-lg-12 col-md-12 col-xs-12">
									<br></br>
									<h4 class="panel-title pull-left">사용 ID</h4>
									<table class="table table-bordered" id="tbId">
										<thead>
											<th>아이디</th>
											<th>비밀번호</th>
											<th>등급</th>
											<th>이름</th>
											<th>생년월일</th>
											<th>메일</th>
											<th>사용일자</th>
											<th>용도</th>
											<th></th>
										</thead>
										<tbody>
											<!-- 리스트 -->
											<tr class="tbRow">
												<td>
													<input type="hidden" name="selIdd[]" id="selId">
													<select class="fancy-select form-control selId" data-method="lists" data-value="" name="sel_id[]">
														<option value="">아이디</option>
													</select>
												</td>
												<td class="tdPass"></td>
												<td class="tdGrade"></td>
												<td class="tdName"></td>
												<td class="tdBirth"></td>
												<td class="tdEmail"></td>
												<td class="tdDate"></td>
												<td class="tdUsed">
													<input type="hidden" name="is_request[]" value="">
													<select class="fancy-select form-control" data-value="" name="sel_request[]">
														<option value=3>미사용</option>
														<option value=1>질문</option>
														<option value=2>답변</option>
													</select>
													<!--  
													<div class="radio-custom radio-inline">
			                                        	<input type="radio" name="is_request[]" value="1">
			                                        	<label for="in_active">질문</label>
			                                        </div>
			                                        <div class="radio-custom radio-inline">
			                                        	<input type="radio" name="is_request[]" value="2">
			                                        	<label for="out_active">답변</label>
			                                        </div>
			                                        -->
												</td>
												<td class="text-center">
													<div class="btn btn-primary mr5 btn-xs btAdd" onclick="row_controll($(this), 'add')">+</div> <!-- btn-round btn-alt mb10-->
													<div class="btn btn-danger mr5 btn-xs btRm" onclick="row_controll($(this), 'rm')">-</div>
												</td>
											</tr>
											<!-- 리스트 -->
										</tbody>
									</table>
								</div>
								<!-- END .col-lg-12 col-md-12 col-xs-12 -->
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="javascript:history.go(-1); javascript:reload();">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
								</div>
								
								
							</form>
							<!-- top form(member-form-write-setting) END  -->
							

						</div>
						<!-- End .panel-body -->
						

					</div>
					<!-- End .panel -->
				</div>
				<!-- col-lg-12 end here -->
			</div>
			
			
			
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_chc_write.js"></script>
<link href="<?echo $this->config->base_url()?>html/css/chc_write.css" rel="stylesheet">
