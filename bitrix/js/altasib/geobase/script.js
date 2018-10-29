if(typeof altasib_geobase=="undefined")
	var altasib_geobase={};

if(typeof BX!='undefined'){
	BX.ready(function(){
		$(document).ready(function(){
			altasib_geobase.bNewVers=altasib_geobase.isNewVersion();
			altasib_geobase.parse_city();
			setTimeout('altasib_geobase.replace()',1000);
		});
	});
	BX.addCustomEvent("onAjaxSuccess",function(par1,par2,par3){
		if(typeof par1=='undefined'&&typeof par2=='undefined'&&typeof par3=='undefined')
			altasib_geobase.timeoutId=setTimeout('altasib_geobase.chrun()',300);
	});
}
else{
	$(document).ready(function(){
		altasib_geobase.bNewVers=altasib_geobase.isNewVersion();
		altasib_geobase.parse_city();
		setTimeout('altasib_geobase.replace()',1000);
	});
}
altasib_geobase.search_path='/bitrix/tools/altasib.geobase/search_loc.php';
altasib_geobase.attempts=0;
altasib_geobase.ctr_attempts=0;
altasib_geobase.replace_handler=null;
altasib_geobase.bNewVers=false;

altasib_geobase.replace=function(){
	var country='',city='',region='';

	if(typeof altasib_geobase.city!="undefined")
		city=altasib_geobase.city;
	if(typeof altasib_geobase.region!="undefined"&&altasib_geobase.region!="undefined"&&altasib_geobase.region!="undefined undefined")
		region=altasib_geobase.region;

	if(typeof altasib_geobase.country!="undefined")
		country=altasib_geobase.country;
	else if(typeof altasib_geobase.def_location!="undefined")
		country=altasib_geobase.def_location;
	else
		country='';

	var pt=$('form [name="PERSON_TYPE"][checked]').attr('value');
	if(typeof pt=='undefined'||pt=='null'||pt=='')pt=1;

	if(typeof altasib_geobase.pt_vals!='undefined')
		var fieldName=altasib_geobase.pt_vals[pt];

	if(typeof fieldName=='undefined'&&typeof altasib_geobase.pt!='undefined'){
		if(typeof altasib_geobase.pt[0]!='undefined')
			var fieldName=altasib_geobase.pt_vals[altasib_geobase.pt[0]];
	}

	if(typeof fieldName!='undefined'&&fieldName.length>0){
		var fLoc=$('form [name="'+fieldName+'"]');
		var fLocVal=$('#'+fieldName+'_val');

		if(typeof fLoc=='undefined'||fLoc.length==0)
			fLoc=$('#'+fieldName);

		if(typeof fLocVal=='undefined'||fLocVal.length==0)
			if(typeof fLoc!='undefined'&&fLoc.length>0)
				fLocVal=fLoc;

		if(typeof fLoc=='undefined'||fLoc.length==0)
			if(typeof fLocVal!='undefined'&&fLocVal.length>0)
				fLoc=fLocVal;

		if(!altasib_geobase.bNewVers&&fLocVal.length>0&&typeof region=="undefined"&&typeof city=="undefined"){
			if(altasib_geobase.check_value(fLocVal.val(),'1'))
				fLocVal.val(city+', '+region+', '+country);
			if(altasib_geobase.check_value(fLocVal.attr('value'),'2')){
				if(region!='')
					fLocVal.attr('value',city+', '+region+', '+country);
				else
					fLocVal.attr('value',city+', '+country);
			}
		}
	}

	if(typeof fLoc!='undefined'&&fLoc.length!=0&&altasib_geobase.check_value(fLoc.val(),'3')){
		$.ajax({
			url:window.location.protocol+'//'+window.location.host+
				altasib_geobase.search_path+'?search='+city.replace(/[\u0080-\uFFFF]/g,
					function(s){return "%u"+('000'+s.charCodeAt(0).toString(16)).substr(-4);}),
			data:{'params':'siteId:'+altasib_geobase.SITE_ID},
			async:false,
			success:function(out){
				if(out.length>2){
					out=$.parseJSON(out.replace(new RegExp("'",'g'),'"'));
					if(out!==null){
						if(out.length>0)
							out=out[0];
						if(typeof out=='object'){
							if(altasib_geobase.check_value(fLoc.val(),'6')){
								if(altasib_geobase.bNewVers){
									fLoc.val(out['CODE']);
									if(fLoc.val()!=out['CODE'])
										altasib_geobase.get_loc_fields(out['CODE']);
								}else{
									fLoc.val(out['ID']);
									if(fLoc.val()!=out['ID'])
										altasib_geobase.get_loc_fields(out['ID']);
								}
								altasib_geobase.send_form();
							}
						}
					}
				}
				if(out=='[]'||out===null||typeof out=='undefined'||out==''){
					if(altasib_geobase.bNewVers&&typeof altasib_geobase.bx_loc_code!='undefined'){
						if(altasib_geobase.check_value(fLoc.val(),'16')){
							fLoc.val(altasib_geobase.bx_loc_code);altasib_geobase.send_form();
						}
					}
					else if(typeof altasib_geobase.bx_loc!='undefined'){
						if(altasib_geobase.check_value(fLoc.val(),'7')){
							fLoc.val(altasib_geobase.bx_loc);altasib_geobase.send_form();
						}
					}
				}

				var valfLoc=fLocVal.val();
				if(!altasib_geobase.bNewVers&&typeof valfLoc!='undefined'&&valfLoc.length==0){
					if(altasib_geobase.check_value(fLocVal.val(),'4'))
						fLocVal.val(out['NAME']+', '+out['REGION_NAME']+', '+out['COUNTRY_NAME']);
					if(altasib_geobase.check_value(fLocVal.attr('value'),'5')){
						if(out['REGION_NAME']!='')
							fLocVal.attr('value',out['NAME']+', '+out['REGION_NAME']+', '+out['COUNTRY_NAME']);
						else
							fLocVal.attr('value',out['NAME']+', '+out['COUNTRY_NAME']);
					}
				}
			}
		});
	}

	if(typeof altasib_geobase.pt=='undefined')return;

	altasib_geobase.replace_handler=function(e){
		if(typeof e!='undefined'&&e!=null){
			if(typeof altasib_geobase.changeTimeStamp=='undefined')
				altasib_geobase.changeTimeStamp=e.timeStamp;
			else if(e.timeStamp==altasib_geobase.changeTimeStamp)
				return;
		}
		altasib_geobase.form_sended=false;
		if(altasib_geobase.attempts>=3)
			return;
		var itr=5;
		var interval=setInterval(function(){
			if(typeof altasib_geobase.pt_vals!='undefined'&&typeof e!='undefined'&&e!=null)
				var lfield=altasib_geobase.pt_vals[$(e.target).attr('value')];
			else if(typeof altasib_geobase.pt[0]!='undefined')
				var lfield=altasib_geobase.pt_vals[altasib_geobase.pt[0]];
			var lfID=lfield;

			if(typeof lfID!="undefined"&&lfID[0]!="#")
				lfID='#'+lfID;

			var fLocVal=$(lfID+'_val');
			if(typeof fLocVal=='undefined'||fLocVal.length==0)
				fLocVal=$('form [name="'+lfield+'"]');

			var oLoc=$(lfID);
			if(typeof oLoc=='undefined'||oLoc.length==0)
				oLoc=$('form [name="'+lfield+'"]');

			var locId=lfield.split('_');
			if(typeof locId=='object'&&locId.length>0){
				var ctrProp=$('[name=COUNTRYORDER_PROP_'+locId[locId.length-1]+']');
			}

			if((oLoc.length>0&&altasib_geobase.check_value(oLoc.val(),'8'))||(typeof ctrProp!='undefined'&&ctrProp.length>0&&altasib_geobase.check_ctrvalue(ctrProp.val()))){
				altasib_geobase.attempts++;
				$.ajax({
					url:window.location.protocol+'//'+window.location.host+
						altasib_geobase.search_path+'?search='+city.replace(/[\u0080-\uFFFF]/g,
							function(s){return "%u"+('000'+s.charCodeAt(0).toString(16)).substr(-4);}),
					data:{'params':'siteId:'+altasib_geobase.SITE_ID},
					async:false,
					success:function(out){
						var tmp=out.split('NAME'),item=tmp[0].split("'");
						var arr=$.parseJSON(out.replace(new RegExp("'",'g'),'"'));
						var townId=item[3],townCode=item[4];
						if(arr!==null&&arr.length>0)
							arr=arr[0];
						var cityNum=altasib_geobase.bNewVers?arr['CODE']:townId;
						if(typeof oLoc=='undefined'||oLoc.length==0){
							var inpLoc=$('form [name="'+lfield+'"]');
							if(altasib_geobase.check_value(inpLoc.val(),'9')){
								inpLoc.val(cityNum);
							}
						}else{
							if(altasib_geobase.check_value(oLoc.val(),'10')){
								oLoc.val(cityNum);
							}
						}

						clearInterval(interval);
						if(altasib_geobase.timeoutId!='undefined')
							clearTimeout(altasib_geobase.timeoutId);

						if(out!='[]')
							eval("out="+out+";");

						if(typeof fLocVal=='undefined'||fLocVal.length==0)
							fLocVal=$('form [name="'+lfield+'"]');

						if(out==null)
							out=arr;
						if(!altasib_geobase.bNewVers&&out!==null&&out.length>2&&altasib_geobase.check_value(fLocVal.attr('value'),'11')){
							if(out['REGION_NAME']!='')
								fLocVal.attr('value',out[0]['NAME']+', '+out[0]['REGION_NAME']+', '+out[0]['COUNTRY_NAME']);
							else
								fLocVal.attr('value',out[0]['NAME']+','+out[0]['COUNTRY_NAME']);
						}

						if(typeof ctrProp!='undefined'){
							if(typeof ctrProp!='undefined'&&ctrProp.length>0)
								altasib_geobase.get_loc_fields(altasib_geobase.bNewVers?arr['CODE']:townId);
						}

						if(out=='[]'||out===null||typeof out=='undefined'||out==''){
							if(typeof oLoc=='undefined'||oLoc.length==0){
								var inputLoc=$('form [name="'+lfield+'"]');
								if(altasib_geobase.check_value(inputLoc.val(),'12'))
									if(altasib_geobase.bNewVers&&typeof altasib_geobase.bx_loc_code!='undefined')
										inputLoc.val(altasib_geobase.bx_loc_code);
									else if(typeof altasib_geobase.bx_loc!='undefined')
										inputLoc.val(altasib_geobase.bx_loc);
							}else if(altasib_geobase.check_value(oLoc.val(),'13')){
								if(altasib_geobase.bNewVers&&typeof altasib_geobase.bx_loc_code!='undefined')
									inputLoc.val(altasib_geobase.bx_loc_code);
								else
									oLoc.val(altasib_geobase.bx_loc);
							}
						}
						altasib_geobase.send_form();
					}
				});
			}
			else if(--itr<=0){
				clearInterval(interval);
			}

			if(!altasib_geobase.bNewVers&&fLocVal.length>0){
				if(altasib_geobase.check_value(fLocVal.val(),'14',false)){
					fLocVal.val(city+', '+region+', '+country);
				}
				if(altasib_geobase.check_value(fLocVal.attr('value'),'15',false)){
					if(region!='')
						fLocVal.attr('value',city+', '+region+', '+country);
					else
						fLocVal.attr('value',city+', '+country);
				}
			}

		},1900);
	}

	$('body').on('change','form [name="PERSON_TYPE"]',function(e){
		altasib_geobase.attempts=0;
		altasib_geobase.ctr_attempts=0;
		altasib_geobase.replace_handler(e);
	});

	$('body').on('change','form [name="PROFILE_ID"]',function(e){
		altasib_geobase.attempts=0;
		altasib_geobase.ctr_attempts=0;
	});
}

altasib_geobase.parse_city=function(){
	if((altasib_geobase.manual_code=altasib_geobase.getCookie(altasib_geobase.COOKIE_PREFIX+'_'+'ALTASIB_GEOBASE_CODE'))!==null)
		altasib_geobase.manual_code=$.parseJSON(decodeURIComponent(altasib_geobase.manual_code.replace(/\+/g," ")));

	if((altasib_geobase.auto_code=altasib_geobase.getCookie(altasib_geobase.COOKIE_PREFIX+'_'+'ALTASIB_GEOBASE'))!==null)
		altasib_geobase.auto_code=$.parseJSON(decodeURIComponent(altasib_geobase.auto_code.replace(/\+/g," ")));

	if(altasib_geobase.manual_code!==null){
		if(typeof altasib_geobase.manual_code['CITY_RU']!='undefined')
			altasib_geobase.city=altasib_geobase.manual_code['CITY_RU'];
		else if(typeof altasib_geobase.manual_code['CITY']!='undefined'){
			if(typeof altasib_geobase.manual_code['CITY']['NAME']!='undefined')
				altasib_geobase.city=altasib_geobase.manual_code['CITY']['NAME'];
			else if(typeof altasib_geobase.manual_code['CITY']=='string')
				altasib_geobase.city=altasib_geobase.manual_code['CITY'];
		}
		else if(typeof altasib_geobase.manual_code['CITY_NAME']!='undefined')
			altasib_geobase.city=altasib_geobase.manual_code['CITY_NAME'];

		if(typeof altasib_geobase.manual_code['REGION']!='undefined'){
			if(typeof altasib_geobase.manual_code['REGION']['NAME']!='undefined')
				altasib_geobase.region=altasib_geobase.manual_code['REGION']['NAME']+' '
				+(typeof altasib_geobase.manual_code['REGION']['SOCR']!='undefined' ?
					altasib_geobase.manual_code['REGION']['SOCR']:'');
			else if(typeof altasib_geobase.manual_code['REGION']=='string')
				altasib_geobase.region=altasib_geobase.manual_code['REGION'];
		}
		else if(typeof altasib_geobase.manual_code['REGION_NAME']!='undefined')
			altasib_geobase.region=altasib_geobase.manual_code['REGION_NAME'];

	}else if(altasib_geobase.auto_code!==null){
		altasib_geobase.city=altasib_geobase.auto_code['CITY_NAME'];
		altasib_geobase.region=altasib_geobase.auto_code['REGION_NAME'];
	}
}

altasib_geobase.getCookie=function(n){
	var nameEQ=n+'=';
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' ')
			c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)
			return c.substring(nameEQ.length,c.length);
	}
	return null;
}
altasib_geobase.isNewVersion=function(){
	if(typeof BX!='undefined'&&typeof BX.Sale!='undefined'&&typeof BX.Sale.OrderAjaxComponent!='undefined'
		&&typeof submitFormProxy!='undefined'&&typeof submitFormProxy=='function'){
		return true;
	}
	return false;
}
altasib_geobase.send_form=function(){
	if(typeof altasib_geobase.default_checked!='undefined'&&altasib_geobase.default_checked)
		altasib_geobase.form_sended=true;

	var func=null;
	if(altasib_geobase.bNewVers){
		func=submitFormProxy;
	}
	else if(typeof submitForm!='undefined'&&typeof submitForm=='function'){
		func=submitForm;
	}

	if(typeof func=='function'){
		if(altasib_geobase.is_mobile){
			if(!$('div#altasib_geobase_mb_popup').is(':visible')&&!altasib_geobase.sc_is_open&&!$('div#altasib_geobase_mb_window').is(':visible'))
				func();
		}else{
			if(!$('div#altasib_geobase_popup').is(':visible')&&!altasib_geobase.sc_is_open&&!$('div#altasib_geobase_window').is(':visible'))
				func();
		}
	}
}

altasib_geobase.get_loc_fields=function(inp){
	if(typeof inp=='undefined'||inp==null||inp=='')
		return;
	var arLocs=[];
	if(typeof(altasib_geobase.pt)!='undefined'&&typeof(altasib_geobase.pt_vals)!='undefined'){
		for(var i=0,len=altasib_geobase.pt.length;i<len;++i)
			arLocs.push(altasib_geobase.pt_vals[altasib_geobase.pt[i]]);
	}
	else if(typeof(altasib_geobase.field_loc_ind)!='undefined'&&typeof(altasib_geobase.field_loc_leg)!='undefined')
		arLocs=[altasib_geobase.field_loc_ind,altasib_geobase.field_loc_leg];

	for(var key in arLocs){
		var loc=arLocs[key];
		var locId=loc.split('_');
		if(typeof locId=='object'&&locId.length>0){
			var prNum=locId[locId.length-1];
			var ctrProp=$('[name=COUNTRYORDER_PROP_'+prNum+']');

			if(typeof ctrProp=='undefined'||ctrProp.length<=0)
				continue;

			ctrProp.prop("disabled",true).parent().append('<input type="hidden" name="'+loc+'" value="'+inp+'">')
				.children('select').prop("disabled",true);
		}
	}
	return;
}

altasib_geobase.chrun=function(){
	if(altasib_geobase.timeoutId!='undefined')
		clearTimeout(altasib_geobase.timeoutId);

	if(typeof altasib_geobase.replace_handler=='function'){
		altasib_geobase.replace_handler();
	}
}
altasib_geobase.check_value=function(val,source,def_en){
	if(def_en===undefined)def_en=true;
	var res=false;

	if(typeof val=='undefined'||val==null||val==''||val==0||val.length==0)
		res=true;

	if(!res&&typeof val=='string'&&$.trim(val)=='')
		res=true;

	if(typeof altasib_geobase.form_sended!='undefined'&&altasib_geobase.form_sended)
		def_en=false;

	if(!res&&def_en){
		if(altasib_geobase.bNewVers){
			for(var i in altasib_geobase.pv_def_code){
				if(val==altasib_geobase.pv_def_code[i]){
					altasib_geobase.default_checked=true;
					res=true;break;
				}
			}
		}else{
			for(var k in altasib_geobase.pv_default){
				if(val==altasib_geobase.pv_default[k]){
					altasib_geobase.default_checked=true;
					res=true;break;
				}
			}
		}
	}
	return res;
}
altasib_geobase.check_ctrvalue=function(v){
	var res=true;

	if(altasib_geobase.ctr_attempts>0)
		res=false;

	if(typeof v!='undefined'&&v!=null&&v!=''&&v.length!=0)
		altasib_geobase.ctr_attempts++;
	return res;
}