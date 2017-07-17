/*页面 全屏-添加*/
function o2o_edit(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url,
		end: function () {// 关闭layer后自动刷新父页面
	                location.reload();
	            },
	});
	layer.full(index);
}

/*添加或者编辑缩小的屏幕*/
function o2o_s_edit(title,url,w,h){
	layer_show(title,url,w,h);

}

/*删除*/
function o2o_del(url){
	
	layer.confirm('确认要删除吗？',function(index){
		window.location.href=url;
	});
}

/**
 * AJAX实现排序
 * @Author   A-Li
 * @DateTime 2017-07-08T14:19:55+0800
 * @param    {[type]}                 )          {			var      id [description]
 * @param    {[type]}                 'json');} [description]
 * @return   {[type]}                            [description]
 */
$('.listorder input').blur(function() {
	//编写抛送逻辑
	//获取主键id
	var id = $(this).attr('attr-id');
	//获取排序的值
	var listorder = $(this).val();
	//把上面获得的两个数据组装为数组
	var postData = {
		'id' : id,
		'listorder' : listorder,
	};
	var url = SCOPE.listorder_url;
	//抛送http
	$.post(url, postData, function(result) {
		//逻辑
		if(result.code == 1) {
			location.href = result.data;
		}else {
			alert(result.msg);
		}
	},'json');
});

/**
 * 动态获取二级城市
 */
$('.cityId').change(function(){
	city_id = $(this).val();
	//抛送请求
	url = SCOPE.city_url;
	postData = {'id':city_id};
	$.post(url, postData, function(result){
		//相关的业务处理
		if(result.status == 1) {
			//将信息填充到html
			data = result.data;
			city_html = '';
			$(data).each(function(i) {
				city_html += "<option value='"+this.id+"'>"+this.name+"</option>";
			});
			$('.se_city_id').html(city_html);
		}else if(result.status == 0) {
			$('.se_city_id').html('');
		};
	},'json');
});

/**
 * 动态获取二级分类
 */
$('.categoryId').change(function(){
	category_id = $(this).val();
	//抛送请求
	url = SCOPE.category_url;
	postData = {'id':category_id};
	$.post(url, postData, function(result){
		//相关的业务处理
		if(result.status == 1) {
			data = result.data;
			category_html = "";

			$(data).each(function() {
				category_html += '<label for="checkbox-moban-'+this.id+'">';
				category_html += '<input name="se_category_id[]" type="checkbox" id="checkbox-moban-'+this.id+'" value="'+this.id+'" >';
				category_html += this.name+'&nbsp;&nbsp;</label>';
			});
			$('.se_category_id').html(category_html);
		}else if(result.status == 0) {
			$('.se_category_id').html('');
		};
	},'json');
});

/**
 * 动态校验数据是否存在
 */
function check(data, id, name, url){
	//抛送请求
	if(name == 'name') {
		postData = {'name':data};
	}else if(name == 'username') {
		postData = {'username':data};
	}
	document.getElementById(id).innerHTML='';	// 先把提示值设为空，当再次循环是重复调用，覆盖提示信息
	$.post(url, postData, function(result){ 
		//相关的业务处理
		if(result.status == 0) {
			document.getElementById(id).innerHTML='&nbsp;&nbsp;'+result.message;	// 抛出提示信息
		};
	});
};

/**
 * 查看审核返回状态
 */
function checkstatus(status, id, name, url){
	//抛送请求
	if(name == 'name') {
		postData = {'name':data};
	}else if(name == 'username') {
		postData = {'username':data};
	}
	document.getElementById(id).innerHTML='';	// 先把提示值设为空，当再次循环是重复调用，覆盖提示信息
	$.post(url, postData, function(result){ 
		//相关的业务处理
		if(result.status == 0) {
			document.getElementById(id).innerHTML='&nbsp;&nbsp;'+result.message;	// 抛出提示信息
		};
	});
};

// 选择时间插件
function selecttime(flag){
	if(flag==1) {
		var endTime = $('#countTimeend').val();
		if(endTime != '') {
			WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', maxDate:endTime});
		}else {
			WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'});
		}
	}else {
		var startTime = $('#countTimestart').val();
		if(startTime != '') {
			WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', mixDate:startTime});
		}else {
			WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'});
		}
	}
}