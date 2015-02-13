
var submit_page=0;
jQuery(function ($){
	var back_end_script_url=MyAjax.ajaxurl;
	$(function (){
		var substrate_select=$('#substrate');
		var adhesion_select=$('#adhesion');
		var category_select=$('#cat');
		var category_callback=function (data){
			submit_page=0;
			substrate_select.find('option').remove();
			adhesion_select.find('option').remove();
			for(var k in data){

				var option=$('<option />').val(data[k]).html(data[k]);
				adhesion_select.append(option);
			}
		};
		var adhesion_callback=function (data){
			submit_page=0;
			substrate_select.find('option').remove();
			var temp=0;
			for(var k in data){
				if(temp===0){
					submit_page=k;//line to fix the first one being selected but not actually selected..
					temp++;
				}
				var option=$('<option />').val(k).html(data[k]);
				substrate_select.append(option);
			}
		};
		var category_change=function (){
			var category=category_select.find(":selected").val();
			var params={
				action: 'ajax_callback',
				cat: category
			};
			$.post(back_end_script_url, params, category_callback, 'json');
		};
		var adhesion_change=function (select){
			if(typeof select==='undefined'){
				select=false;
			}
			var adhesion=adhesion_select.find(":selected").val();
			var category=category_select.find(":selected").val();
			var params={
				action: 'adhesion_ajax',
				cat: category,
				adhesion: adhesion
			};
			$.post(back_end_script_url, params, adhesion_callback, 'json');
		};
		var substrate_change=function (select){
			if(typeof select==='undefined'){
				select=false;
			}
			submit_page=substrate_select.find(":selected").val();
		};
		var submit_search=function (){
			if(submit_page>0){
				window.location="/?p="+submit_page;
			}else{
				alert("You must fill in all boxes to continue...");
			}
		};
		$('#cat').on('change', category_change);
		$('#adhesion').on('change', adhesion_change);
		$('#substrate').on('change', substrate_change);
		$('#product-selector-search').on('click', submit_search);
	});
});