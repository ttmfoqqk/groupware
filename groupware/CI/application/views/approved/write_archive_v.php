<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>보관함</h2>
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

							<form id="project-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" enctype="multipart/form-data" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="task_no" id="task_no" value="<?echo $data['project_no'];?>">
							<input type="hidden" name="oldFile" id="oldFile" value="<?echo $data['file'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">



							<?if( $action_type == 'create' ) {?>
							<div class="row pb20">
								<label for="approved_kind" class="col-lg-2 col-md-3 control-label">결재 종류</label>
								<div class="col-lg-7 col-md-6 col-xs-8">
									<select id="approved_kind" name="approved_kind" class="fancy-select form-control">
										<option value="0">업무</option>
										<option value="1">일반</option>
									</select>
								</div>
								<div class="col-lg-3 col-md-3 col-xs-4">
									<button type="button" class="btn btn-primary btn-alt" id="call_approved_kind">불러오기</button>
								</div>
							</div>
							<?}else{?>
							<div class="row pb20">
								<div class="col-lg-3 col-md-3 col-xs-4">
									<input type="hidden" id="approved_kind" name="approved_kind" value="<?echo $data['kind'];?>">
									<button type="button" class="btn btn-primary btn-alt" id="call_approved_kind"><?echo $data['kind']=='0'?'업무':'문서';?> 불러오기</button>
								</div>
							</div>
							<?}?>

							<!-- 업무보고서 -->
							<input type="hidden" name="p_department" id="p_department" value="<?echo $data['menu_no'];?>">
							<input type="hidden" name="p_title" id="p_title" value="<?echo $data['title'];?>">
							<input type="hidden" name="p_sData" id="p_sData" value="<?echo $data['sData'];?>">
							<input type="hidden" name="p_eData" id="p_eData" value="<?echo $data['eData'];?>">

							<div style="<?echo ($data['kind'] == '0' ? '' : 'display:none;'); ?>" id="project-layout">
							<div class="panel panel-default">
								<div class="panel-body">
									<h3 class="text-center mb25">업무보고서</h3>
									
									<div class="col-xs-10 col-xs-offset-1 mb20">
										<table class="table table-bordered table-border-black">
											<tbody>
												<tr>
													<th style="width:200px;">문서번호</th>
													<td id="p_paper_no" style="width:400px;"><?echo $data['no'];?></td>
													<th style="width:200px;">등록일자</th>
													<td><?echo $data['created'];?></td>
												</tr>
												<tr>
													<th>담당부서</th>
													<td id="project_department"><?echo $data['part_name'];?></td>
													<th>담당자</th>
													<td id="project_user"><?echo $data['user_name'];?></td>
												</tr>
												<tr>
													<th>분류</th>
													<td id="project_menu"><?echo $data['category_name']?></td>
													<th>제목</th>
													<td id="project_title"><?echo $data['title'];?></td>
												</tr>
												<tr>
													<th>내용</th>
													<td id="project_contents" colspan="3"><?echo nl2br($data['p_contents']);?></td>
												</tr>
												<tr>
													<th>진행기간</th>
													<td id="project_date" colspan="3"><?echo $data['sData'].' ~ '.$data['eData'];?></td>
												</tr>
												<tr>
													<th>결재점수</th>
													<td id="project_pPoint">+<?echo $data['pPoint'];?></td>
													<th>누락점수</th>
													<td id="project_mPoint">-<?echo $data['mPoint'];?></td>
												</tr>
												<tr>
													<th>첨부파일</th>
													<td id="project_file"><?echo $data['file_link'];?></td>
													<th >순서</th>
													<td class="p5"><input type="text" id="p_order" name="p_order" class="form-control input-sm input-mini" value="<?echo $data['order'];?>"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-xs-10 col-xs-offset-1">
										<textarea id="p_contents" name="p_contents" class="form-control" rows="10" placeholder="내용"><?echo $data['contents'];?></textarea>
									</div>
								</div>
							</div>
							</div>
							<!-- 업무보고서 -->

							<!-- 일반보고서 -->
							<div style="<?echo ($data['kind'] == '1' ? '' : 'display:none;'); ?>" id="document-layout">
							<div class="panel panel-default">
								<div class="panel-body">
									<h3 class="text-center mb25" id="document_name"><?echo $data['document_name'];?></h3>
									
									<div class="col-xs-10 col-xs-offset-1 mb20">
										<table class="table table-bordered table-border-black">
											<tbody>
												<tr>
													<th style="width:200px;">문서번호</th>
													<td style="width:400px;"><?echo $data['no'];?></td>
													<th style="width:200px;">등록일자</th>
													<td><?echo $data['created'];?></td>
												</tr>
												<tr>
													<th>담당부서</th>
													<td class="p5">
														<select id="d_department" name="d_department" data-method="department" data-value="<?echo $data['menu_no'];?>" class="fancy-select form-control input-sm">
															<option value="">담당부서</option>
														</select>
													</td>
													<th>담당자</th>
													<td><?echo $data['user_name'];?></td>
												</tr>
												<tr>
													<th>제목</th>
													<td colspan="3" class="p5">
														<input id="d_title" name="d_title" type="text" class="form-control input-sm" placeholder="제목" value="<?echo $data['title'];?>">
													</td>
												</tr>
												<tr>
													<th>진행기간</th>
													<td colspan="3" class="p5">
														
														<div class="col-xs-6 col-md-6 row">
															<div class="input-daterange input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control input-sm" name="d_sData" id="d_sData" value="<?echo $data['sData'];?>" />
																<span class="input-group-addon">to</span>
																<input type="text" class="form-control input-sm" name="d_eData" id="d_eData" value="<?echo $data['eData'];?>"/>
															</div>
														</div>
														<div class="col-xs-6 col-md-6">
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsToday">오늘</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsWeek">7일</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsMonth">30일</button>
															<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsReset">날짜초기화</button>
														</div>
														
													</td>
												</tr>
												<tr>
													<th>첨부파일</th>
													<td class="p5">
														<input type="file" id="d_file" name="d_file" class="filestyle" data-size="sm" data-buttonText="찾기" data-buttonName="btn-danger" data-iconName="fa fa-plus">
														
														<div>
														<?if($data['file']){?>
														<a href="<?php echo site_url('download?path=upload/approved/&oname='.$data['file'].'&uname='.$data['file'])?>"><?php echo $data['file'];?></a>
														<?}?>
														</div>
													</td>
													<th>순서</th>
													<td class="p5"><input type="text" id="d_order" name="d_order" class="form-control input-sm input-mini" value="<?echo $data['order'];?>"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-xs-10 col-xs-offset-1">
										<textarea id="d_contents" name="d_contents" class="form-control" rows="10" placeholder="내용"><?echo $data['contents'];?></textarea>
									</div>
								</div>
							</div>
							</div>
							<!-- 일반보고서 -->
							
							
							<div class="panel-body pull-left">
								<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
							</div>
							<div class="panel-body pull-right">
								<?if( $action_type == 'edit' ){?>
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