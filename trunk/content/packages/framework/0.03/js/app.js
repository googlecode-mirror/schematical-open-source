/**
 * Core MFB Funcationality
 * 
 */
window.MFBAppInit = MFBAppInit = function(){	
	function DefaultInputTypeRead(strMatch){
		var objData = {};
		var jColl = $(this.EleSelector);
		for(var i = 0; i < jColl.length; i++){
			var jInput = $(jColl[i]);
			var blnIgnor = false;
			if(typeof strMatch != 'undefined'){
				if(!jInput.is(strMatch)){
					blnIgnor = true;
				}
			}
			if(!blnIgnor){
				objData[jInput.attr('name')] = {};
				objData[jInput.attr('name')]['type']  = this.IdType;
				objData[jInput.attr('name')]['value'] = jInput.val();
			}                 
		}
		return objData;
	}
	function TranferEleAttributes(objFrom, objTo){
		jTo = $(objTo);
		jFrom = $(objFrom);
		var arrAtts = jFrom[0].attributes;
		for(var i = 0; i < arrAtts.length; i++){
			if((arrAtts[i].nodeName != 'type') && (arrAtts[i].nodeValue != null)  && (arrAtts[i].nodeValue.length != 0)){
				//console.log(arrAtts[i].nodeName + ' = ' + arrAtts[i].nodeValue);
				jTo.attr(arrAtts[i].nodeName, arrAtts[i].nodeValue);
			}
		}
		return jTo;
	}
	
	var MFBAppPublic = {
			objSettings:{
				blnInitCalled:false,
				server_comm_method:'ajax',
				strUrl:'x',
				strMainPageSelector:'#divBody',
				strPermissions:'',				
				Events:{},
				form_vars:{}
			},
			ReloadPage:function(){
				top.location = MFBApp.objSettings.page_url;
			},
			TriggerEvent:function(strEventFull, strActionParameter, objData, strMethod, strLocation){
				if(typeof objData == 'undefined'){
					var objData = {};
				}
				if(typeof strMethod == 'undefined'){
					var strMethod = this.objSettings.server_comm_method;
				}
				if(typeof strLocation == 'undefined'){
					var strLocation = this.objSettings.strUrl;
				}
				
				objData['signed_request'] = this.objSettings.signed_request;
				objData['action_parameter'] = strActionParameter;
				objData['event'] = strEventFull;
				objData['form_vars'] = this.objSettings.form_vars;
				var jColl = $('.MFBInput');
				for(i = 0; i < jColl.length; i++){
					jEle = $(jColl[i]);
					if(jEle.attr('type') == 'checkbox'){
						if(jEle.is(':checked')){
							objData[jEle.attr('name')] = jEle.val();
						}
					}else{
						objData[jEle.attr('name')] = jEle.val();
					}
				}
				if(strMethod == 'ajax'){
					objData['ajax_call'] = 'true';
					
			        $.ajax({
			              url: strLocation,
			              success: this.TriggerEvent_callBack,
			              error: function(objData){
			            	  //console.log("Ajax Error:");
			            	  //console.log(objData);
			              },
			              data:objData,
			              dataType:'json',
			              type:'POST'
			        });
				}else{
					var jForm = $("<form method='post' action='" + strLocation + "'></form>");
					for(strKey in objData){
						
						if(typeof objData[strKey] == 'object'){
							objData[strKey] = JSON.stringify(objData[strKey], null, 2);
						}
						var jInput = $("<input type='hidden'></input>");						
						jInput.attr('name', strKey);
						jInput.attr('value', objData[strKey]);
						jForm.append(jInput);
						
					}					
					$('body').append(jForm);
					jForm.submit();
				}
			},
			TriggerEvent_callBack:function(objData){
				if(typeof objData['location'] != 'undefined'){
					var jForm = $('<form method="post" ></form>');
					jForm.attr('action', objData['location']);
					jForm.append('<input type="hidden" name="signed_request" value="' + MFBAppPublic.objSettings.signed_request + '"></input>');
					$('body').replaceWith(jForm);
					jForm.submit();
					return;
				}
				if(typeof objData['window_location'] != 'undefined'){
					window.location = objData['location'];
					return;
				}
				$(MFBAppPublic.objSettings.strMainPageSelector).html(objData.html);
				if(typeof objData['signed_request'] != 'undefined'){
					MFBAppPublic.objSettings.signed_request = objData['signed_request'];
				}
				MFBAppPublic.objSettings.form_vars = objData['form_vars'];
				FB.XFBML.parse();
				FB.Canvas.setAutoResize();
				
				if(typeof objData.callBack != 'undefined'){
					
					if(typeof MFBAppPublic.CallBacks[objData.callBack] != 'undefined'){				
						MFBAppPublic.CallBacks[objData.callBack](objData);
					}
				}
				MFBAppPublic.RenderMFBInputs();
			},			
			CallBacks:{	
				PromptForPermissions:function(objData){			
					FB.ui({
						   method: 'permissions.request',
						   'perms': MFBAppPublic.objSettings.strPermissions,
						   'display': 'iframe'
						  },
						  function(objResponse){
							  MFBAppPublic.TriggerEvent('.FBProxyControl_permissions', objResponse);
						  }
					);
				},
				PromptForFeedPost:function(objData){
					objData.method = 'feed';
					FB.ui(
							objData
						  ,
						  function(objResponse){
							  MFBAppPublic.TriggerEvent('.FBProxyControl_feedPost', objResponse);
						  }
					);
				},
				RenderMFBInputs:function(){
					MFBAppPublic.RenderMFBInputs();
				}
			},
			Init:function(objNewSettings){	
				if(!this.objSettings.blnInitCalled){				
					this.objSettings.blnInitCalled = true;

					if(typeof objNewSettings != 'undefined'){
			    		this.objSettings = jQuery.extend(this.objSettings, objNewSettings);    		
			    	}	

					FB.init({
			    	    appId  : this.objSettings.FB_APP_ID,
			    	    status : true, // check login status
			    	    cookie : true, // enable cookies to allow the server to access the session
			    	    xfbml  : true  // parse XFBML
			    	});
			    	FB.Canvas.setAutoResize();
					
					for(i in this.objSettings.Events){	
						var jElement = $(this.objSettings.Events[i].element);
						var strFullEvent = this.objSettings.Events[i].element + '_' + this.objSettings.Events[i].event;

						jElement.live(this.objSettings.Events[i].event, function(objEvent){
							
							objEvent.preventDefault();
							var jThis = $(this);
							var strFullEvent = objEvent.handleObj.selector + '_' + objEvent.type;
							MFBAppPublic.TriggerEvent(strFullEvent, jThis.attr('action_parameter'));
						});
					}
					this.RenderMFBInputs();
					
				}
			},
			InputType:{
				MFBTextInput:{
					EleSelector:'.MFBTextInput_Rendered',
					IdType:1,
					Render:function(objEle){
						var jEle = $(objEle);
						var jInput = $('<input type="text" />');						
						jInput = TranferEleAttributes(jEle, jInput);
						jInput.addClass('MFBTextInput_Rendered');
						jEle.replaceWith(jInput);
					},
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');
								if(strValidationType == 'zip'){
									var re = /^\d{5}([\-]\d{4})?$/;									   
									if(!re.test(jInput.val())){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' is not valid';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}else{
										strValidationType = 'required';
									}	
								}
								if(strValidationType == 'phone'){
									var re = /^\(?[2-9]\d{2}[\)\.-]?\s?\d{3}[\s\.-]?\d{4}$/;									   
									if(!re.test(jInput.val())){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' is not valid';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}else{
										strValidationType = 'required';
									}	
								}
								if(strValidationType == 'alphanumeric'){
									var re = /[^a-zA-Z0-9]/g									   
									if(!re.test(jInput.val())){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' is not valid';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}else{
										strValidationType = 'required';
									}	
								}
								if(strValidationType == 'email'){
									var re = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i									   
									if(!re.test(jInput.val())){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' is not valid';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}else{
										if(typeof jInput.attr('confirm_input') != 'undefined'){
											var jCInput = $('#' + jInput.attr('confirm_input'));
											if((jCInput.length > 0) && (jCInput.val() != jInput.val())){
												objData[i].Valid = false;
												if(typeof strDisplayName != 'undefined'){
													objData[i].Message = strDisplayName + ' does not match the confirmation email';
												}else{
													objData[i].Message = 'One or more of your fields has not been filled in properly';
												}
											}else{
												strValidationType = 'required';
											}
										}else{
											strValidationType = 'required';
										}
									}	
								}

								
								
								if(strValidationType == 'required'){
									if(jInput.val().length == 0){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' must be filled in';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}									
															
							}                 
						}
						return objData;
					}			
				},
				MFBLongTextInput:{
					EleSelector:'.MFBLongTextInput_Rendered',
					IdType:2,
					Render:function(objEle){
						var jEle = $(objEle);
						var jInput = $('<textarea></textarea>');						
						jInput = TranferEleAttributes(jEle, jInput);
						jInput.addClass('MFBLongTextInput_Rendered');
						jEle.replaceWith(jInput);
					},
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');
								if(strValidationType == 'required'){
									if(jInput.val().length == 0){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' must be filled in';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}									
															
							}                 
						}
						return objData;
					}			
				},
				MFBStateInput:{
					EleSelector:'.MFBStateInput_Rendered',
					IdType:5,//Same as MFBSelectInput
					Render:function(objEle){
						var jEle = $(objEle);
						var strName = jEle.attr('name');
						var strHtml = new Array(
								'<select>',
								'<option value="-1">State</option>',
								'<option value="Alabama">Alabama</option>',
								'<option value="Alaska">Alaska</option>',
								'<option value="Arizona">Arizona</option>',
								'<option value="Arkansas">Arkansas</option>',
								'<option value="California">California</option>',
								'<option value="Colorado">Colorado</option>',
								'<option value="Connecticut">Connecticut</option>',
								'<option value="Delaware">Delaware</option>',
								'<option value="District of Columbia">District of Columbia</option>',
								'<option value="Florida">Florida</option>',
								'<option value="Georgia">Georgia</option>',
								'<option value="Hawaii">Hawaii</option>',
								'<option value="Idaho">Idaho</option>',
								'<option value="Illinois">Illinois</option>',
								'<option value="Indiana">Indiana</option>',
								'<option value="Iowa">Iowa</option>',
								'<option value="Kansas">Kansas</option>',
								'<option value="Kentucky">Kentucky</option>',
								'<option value="Louisiana">Louisiana</option>',
								'<option value="Maine">Maine</option>',
								'<option value="Maryland">Maryland</option>',
								'<option value="Massachusetts">Massachusetts</option>',
								'<option value="Michigan">Michigan</option>',
								'<option value="Minnesota">Minnesota</option>',
								'<option value="Mississippi">Mississippi</option>',
								'<option value="Missouri">Missouri</option>',
								'<option value="Montana">Montana</option>',
								'<option value="Nebraska">Nebraska</option>',
								'<option value="Nevada">Nevada</option>',
								'<option value="New Hampshire">New Hampshire</option>',
								'<option value="New Jersey">New Jersey</option>',
								'<option value="New Mexico">New Mexico</option>',
								'<option value="New York">New York</option>',
								'<option value="North Carolina">North Carolina</option>',
								'<option value="North Dakota">North Dakota</option>',
								'<option value="Ohio">Ohio</option>',
								'<option value="Oklahoma">Oklahoma</option>',
								'<option value="Oregon">Oregon</option>',
								'<option value="Pennsylvania">Pennsylvania</option>',
								'<option value="Rhode Island">Rhode Island</option>',
								'<option value="South Carolina">South Carolina</option>',
								'<option value="South Dakota">South Dakota</option>',
								'<option value="Tennessee">Tennessee</option>',
								'<option value="Texas">Texas</option>',
								'<option value="Utah">Utah</option>',
								'<option value="Vermont">Vermont</option>',
								'<option value="Virginia">Virginia</option>',
								'<option value="Washington">Washington</option>',
								'<option value="West Virginia">West Virginia</option>',
								'<option value="Wisconsin">Wisconsin</option>',
								'<option value="Wyoming">Wyoming</option>',
								'</select>').join('');
						var jInput = $(strHtml);						
						jInput = TranferEleAttributes(jEle, jInput);
						jInput.addClass('MFBStateInput_Rendered');
						jEle.replaceWith(jInput);
					},
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');
								if(strValidationType == 'required'){
									if(jInput.val() == '-1'){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' must be selected';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}									
															
							}                 
						}
						return objData;
					}				
				},
				MFBSelectInput:{
					EleSelector:'.MFBSelectInput_Rendered',
					IdType:5,
					Render:function(objEle){
						var jEle = $(objEle);
						var strName = jEle.attr('name');						
						var jInput = $('<select></select>');
						jInput.html(jEle.html());
						jInput = TranferEleAttributes(jEle, jInput);
						jInput.addClass('MFBSelectInput_Rendered');
						jEle.replaceWith(jInput);
					},
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');
								if(strValidationType == 'required'){
									if(jInput.val() == '-1'){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' must be selected';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}									
															
							}                 
						}
						return objData;
					}
				},
				MFBUploadInput:{
					EleSelector:'.MFBUploadInput_Rendered',
					IdType:3,
					SuccessHtml:'<h3>Upload Successful</h3>',
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');
								if(strValidationType == 'required'){
									if(jInput.val() == '-1'){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = 'You must upload a file for the ' + strDisplayName + ' field';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}									
															
							}                 
						}
						return objData;
					},
					Render:function(objEle){
						var jEle = $(objEle);
						var strName = jEle.attr('name');
						var strHtml = new Array(
							'<form class="MFBUploadInput_Form" method="post" action="', MFBAppPublic.objSettings['UploadInputIFrameUrl'],'" target="ifmUpload_', strName, '">',
							'<input type="hidden" name="signed_request" value="', MFBAppPublic.objSettings['signed_request'], '" />', 
							'<input type="hidden" name="MFBIdApp" value="', MFBAppPublic.objSettings['MFBIdApp'], '" />', 
							'<input type="hidden" name="fieldName" value="', strName, '" />',							
							'</form>',
							'<iframe class="MFBUploadInput_IFrame" name="ifmUpload_', strName, '" src="" width="350" height="50"  frameborder="0" />'
						).join('');
						//alert(strHtml);
						var jInput = $(strHtml);			
						var jCInput = $('<input type="hidden" name="'+  strName+ '" value="-1" />');
						jCInput = TranferEleAttributes(jEle, jCInput); 
						jCInput.addClass('MFBUploadInput_Rendered');
						
						jEle.replaceWith(jInput);
						
						jForm = $(jInput[0]);
						jForm.append(jCInput);						
						$(function(){   
							$('.MFBUploadInput_Form').each(function(){
										$(this).submit();
							});
							$('.MFBUploadInput_IFrame').load(
								function(objEvent){
									try{
										
										var objResponse = $.parseJSON($(this).contents().text());
										var intIdAttachment = objResponse['idAttachment'];
										var strValue = objResponse['value'];
										var strHtml = new Array(
												//'<input class="MFBUploadInput_Rendered" type="hidden" name="', objResponse['fieldName'], '" value="', intIdAttachment, '" />',
												'<h3>Upload Successful</h3>'
										).join('');										
										var jCInput = $('.MFBUploadInput_Rendered');
										jCInput.val(strValue);
										$(this).replaceWith(strHtml);
										
									}catch(exe){
										//No Problem, its probablly just the HTML from the first step
									}
									
								}	
							);
						});
						
						
					}
				},
				MFBCheckboxInput:{
					EleSelector:'.MFBCheckboxInput_Rendered',
					IdType:1,
					Render:function(objEle){
						var jEle = $(objEle);
						var jInput = $('<input type="checkbox" />');						
						jInput = TranferEleAttributes(jEle, jInput);
						jInput.addClass('MFBCheckboxInput_Rendered');
						jEle.replaceWith(jInput);
					},
					Read:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							
							if((jInput.is(':checked')) && (!blnIgnor)){
								objData[jInput.attr('name')] = {};
								objData[jInput.attr('name')]['type']  = this.IdType;
								objData[jInput.attr('name')]['value'] = jInput.val();
							}                 
						}
						return objData;
					},
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};
									
								var strValidationType = jInput.attr('validation');
								var strDisplayName = jInput.attr('display_name');					
								
								if(strValidationType == 'required'){
									if(!jInput.is(':checked')){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = strDisplayName + ' must be filled in';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}
								if(strValidationType == 'agree'){
									if(!jInput.is(':checked')){
										objData[i].Valid = false;
										if(typeof strDisplayName != 'undefined'){
											objData[i].Message = 'You must agree to the ' + strDisplayName + '';
										}else{
											objData[i].Message = 'One or more of your fields has not been filled in properly';
										}
									}	
								}						
															
							}                 
						}
						return objData;
					}			
				},
				MFBLink:{
					EleSelector:'.MFBLink_Rendered',
					IdType:2,
					Render:function(objEle){
						var jEle = $(objEle);
						var jInput = $('<a href="#">' + jEle.html() + '</a>');						
						jInput = TranferEleAttributes(jEle, jInput);
						
						jInput.addClass('MFBLink_Rendered');
						jEle.replaceWith(jInput);
						jInput.click(function(objEvent){
							objEvent.preventDefault();
							var jThis = $(this);
							var objData = {};
							var strCtlFile = jThis.attr('ctl_file');
							var strTpl = jThis.attr('tpl');
						
							if(typeof strTpl != 'undefined'){
								objData['tpl'] = strTpl;
							}
							if(jThis.attr('target') == '_blank'){	
								if(typeof strCtlFile != 'undefined'){
									objData['ctl_file'] = strCtlFile;
									MFBAppPublic.TriggerEvent('.MFBLink_click', objData, {}, 'post', strCtlFile);
								}
								
							}else{
								if(typeof strCtlFile != 'undefined'){
									objData['ctl_file'] = strCtlFile;									
								}
								MFBAppPublic.TriggerEvent('.MFBLink_click', objData, {}, 'ajax');
							}
							
						});
						
					},
					Read:DefaultInputTypeRead,
					Validate:function(strMatch){
						var objData = {};
						var jColl = $(this.EleSelector);
						for(var i = 0; i < jColl.length; i++){
							var jInput = $(jColl[i]);
							var blnIgnor = false;
							if(typeof strMatch != 'undefined'){
								if(!jInput.is(strMatch)){
									blnIgnor = true;
								}
							}
							if(!blnIgnor){
								objData[i] = {
										Valid:true,
										Message:'',
										Element: jInput
								};								
															
							}                 
						}
						return objData;
					}			
				}
			},
			RenderMFBInputs:function(){
				for(strElementName in this.InputType){
					
					jEleColl = $(strElementName);
					for(var i = 0; i < jEleColl.length; i++){
						jEle = jEleColl[i];
						this.InputType[strElementName].Render(jEle);
					}
				}
			},
			ValidateMFBInputs:function(strMatch){
				var objData = {};
				var blnAllValid = true;
				var intCount = 0;
				for(strElementName in this.InputType){					
					var objInputType = this.InputType[strElementName];
					var objTypeData = objInputType.Validate(strMatch);
					for(strInputName in objTypeData){
						objData[intCount] = objTypeData[strInputName];
						if(!objData[intCount].Valid){
							blnAllValid = false;
						}
						intCount += 1;
					}
				}
				objData.AllValid = blnAllValid;
				return objData;
			},
			ReadMFBInputs:function(strMatch){
				var objData = {};
				for(strElementName in this.InputType){					
					var objInputType = this.InputType[strElementName];
					var objTypeData = objInputType.Read(strMatch);
					for(strInputName in objTypeData){
						objData[strInputName] = objTypeData[strInputName];
					}
				}
				return objData;
			}
	};
	
	window.MFBApp = MFBAppPublic;
	
	return MFBAppPublic;
}