(function(window) {
	
	MFBContestApp.Validate = function(){
		$('body').remove('.MFBAlert');
		 var objData = MFBApp.ValidateMFBInputs('.MFBContestInput');
		 //console.log(objData);
		 var strMessage = '';
		 if(!objData.AllValid){
			 for(i in objData){
				 if((i != 'AllValid') && (!objData[i].Valid)){
					 ClassicTemplateApplicaiton.AlertInput(objData[i].Element, objData[i].Message);					 
				 }
			 }			 
			 return false;
		 }else{
			 return true
		 }
	}
	
	var objPublic = {
	
		
		AlertInput:function(jElement, strMessage){
			
			var strHtml = '<div class="MFBAlert alert"><p>' + strMessage + '</p></div>';
			jElement.after(strHtml);
		}
	};

	window.ClassicTemplateApplicaiton = objPublic;//Make sure to change the refrence to this namespace in /_core/inc/php/YourTemplateApplicationBase.class.php
	
})(window);