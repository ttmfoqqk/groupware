<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo APPROVED_TITLE;?></h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 보낸결재</h4>
						</div>
						<div class="panel-body">

							<form id="approved-form-send" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
							
							<div class="panel panel-default">
								<div class="panel-body">
									<h3 class="text-center mb25">업무보고서</h3>
									
									<div class="col-xs-10 col-xs-offset-1 mb20">
										<table class="table table-bordered" style="min-width:700px;">
											<tbody>
												<tr>
													<th style="width:200px;">문서번호</th>
													<td style="width:400px;"><?echo $data['no'];?></td>
													<th style="width:200px;">등록일자</th>
													<td><?echo $data['created'];?></td>
												</tr>
												<tr>
													<th>담당부서</th>
													<td><?echo $data['sender_part'];?></td>
													<th>담당자</th>
													<td><?echo $data['sender_name'];?></td>
												</tr>
												<tr>
													<th>분류</th>
													<td><?echo $data['menu_name'];?></td>
													<th>제목</th>
													<td><?echo $data['title'];?></td>
												</tr>
												<tr>
													<th>내용</th>
													<td colspan="3"><?echo nl2br($data['p_contents']);?></td>
												</tr>
												<tr>
													<th>진행기간</th>
													<td colspan="3"><?echo $data['sData'].' ~ '.$data['eData'];?></td>
												</tr>
												<tr>
													<th>결재점수</th>
													<td>+<?echo $data['pPoint'];?></td>
													<th>누락점수</th>
													<td>-<?echo $data['pPoint'];?></td>
												</tr>
												<tr>
													<th>첨부파일</th>
													<td><?echo $data['file'];?></td>
													<th> </th>
													<td class="p5"> </td>
												</tr>
												<tr>
													<td colspan="4" style="border-left:1px solid #ffffff;border-right:1px solid #ffffff;">&nbsp;</td>
												</tr>
												<tr>
													<th style="vertical-align:middle;">결재</th>
													<td colspan="3" class="p0 m0">
														
														<div class="row col-xs-12 p0 m0">
															<?
															$i = 1;
															foreach($approved_list as $lt){?>
															<div class="col-xs-2 pt5 p0 m0 text-center" style="width:20%;border-right:<?echo ($i % 5==0?'0':'1')?>px solid #ddd;">
																<div style="border-bottom:1px solid #ddd;" class="p5 pb10 m0">직급</div>
																<div class="p5 m5" style="height:80px;">
																	<p><?echo $lt['user_name'];?></p>
																	<?if( $app_type == 'receive' && $data['status']=='b' && $lt['receiver']==$this->session->userdata('no') ){?>
																	<select id="status" name="status" class="fancy-select form-control">
																		<option value="">선택</option>
																		<option value="c">결재</option>
																		<option value="d">반려</option>
																	</select>
																	<?}else{
																		switch( $lt['status'] ) {
																			case "c" :
																				$status = '승인';
																				break;
																			case "d" :
																				$status = '반려';
																				break;
																			default :
																				$status = '미결';
																		}
																	?>
																	<div class="p5"><b><?echo $status;?></b></div>
																	<?}?>
																</div>
															</div>
															<?
																$i++;
																if($i % 6 == 0){
																	echo '</div><div class="row col-xs-12 p0 m0" style="border-top:1px solid #ddd;">';
																}
															}?>
														</div>
														
													</td>
												</tr>
												<?php 
												if(count($contents_list)>0) {?>
												<tr>
													<td colspan="4" style="border-left:1px solid #ffffff;border-right:1px solid #ffffff;">&nbsp;</td>
												</tr>
												<?}
												foreach($contents_list as $lt){?>
												<tr>
													<td><?echo $lt['user_name'];?></td>
													<td colspan="3"><?echo nl2br($lt['contents']);?></td>
												</tr>
												<?}?>
											</tbody>
										</table>
									</div>
									<?if($data['status']=='a'){?>
									<div class="col-xs-10 col-xs-offset-1">
										<textarea id="contents" name="contents" class="form-control" rows="10" placeholder="내용"><?echo $data['receiver_contents'];?></textarea>
									</div>
									<?}?>
								</div>
							</div>
							
							
							<div class="col-xs-10 col-xs-offset-1">
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<?if( $fg_btn_send == true ) {?>
									<button id="approved_write_send" type="button" class="btn btn-danger btn-alt mr5 mb10">결재 요청</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
									<?}?>

									<?if( $app_type == 'receive' && $data['status']=='b' ) {?>
									<button id="approved_write_receive" type="button" class="btn btn-danger btn-alt mr5 mb10">결재 하기</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
									<?}?>
								</div>
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