/*****************************************************************************
 * elmの値によって表示を切り替える。
 *
 * @param elm 値を取得する要素/基本的にthisを渡す
 *****************************************************************************/
function changeSalaryDisp( elm ) 
{
	switch(elm.value)
	{
	case '日給':
	case '時給':
	default:
		$("#salary_unit_disp").html("円");
		$("#salary_unit_disp2").html("円");
		break;
	case '月給':
	case '年俸':
		$("#salary_unit_disp").html("万円");
		$("#salary_unit_disp2").html("万円");
		break;
	}
}

/*****************************************************************************
 * elmの値によって表示を切り替える。
 *
 * @param elm 値を取得する要素/基本的にthisを渡す
 *****************************************************************************/
function changeSalaryOptionDisp( elm ) 
{
	$("#salary_hour_disp").css('display','none');
	$("#salary_day_disp").css('display','none');
	$("#salary_month_disp").css('display','none');
	$("#salary_year_disp").css('display','none');

	$("#salary_hour").attr("disabled", "disabled");
	$("#salary_day").attr("disabled", "disabled");
	$("#salary_month").attr("disabled", "disabled");
	$("#salary_year").attr("disabled", "disabled");

	switch(elm.value)
	{
	case '時給':
		$("#salary_hour_disp").css('display','inline');
		$("#salary_hour").removeAttr("disabled");
		break;
	case '日給':
		$("#salary_day_disp").css('display','inline');
		$("#salary_day").removeAttr("disabled");
		break;
	case '月給':
		$("#salary_month_disp").css('display','inline');
		$("#salary_month").removeAttr("disabled");
		break;
	case '年俸':
		$("#salary_year_disp").css('display','inline');
		$("#salary_year").removeAttr("disabled");
		break;
	}
}

/*****************************************************************************
 * 求人の公開設定を変更する
 *
 * @id 求人ID
 * @mode on:掲載 off:非掲載
 *****************************************************************************/
function changePublish( id, mode ) 
{
	var message = new Array();
	message["on"] = "この求人を公開に変更しますか？";
	message["off"] = "この求人を一時取りさげに変更しますか？";

	var flag = confirm ( message[mode] );
	if(flag) {
		jQuery.ajax({
			url: 'api.php',
			type: 'POST',
			dataType: "text",
			data: "c=JobApi&m=changePublish&id=" + id + "&mode=" + mode
		})
		.done(function(res){ window.location.reload(true); })
		.fail(function(xml, status, e){ });
	}
	return false;
}