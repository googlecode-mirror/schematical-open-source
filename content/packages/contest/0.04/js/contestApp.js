(function(window) {
	var MFBApp = window.MFBAppInit();
	
	function ValidateContestForm(){
		
		var blnValid = MFBContestApp.Validate();
		if(!blnValid){
			return false;
		}
		
		MFBContestApp.FormData = {};
		MFBContestApp.FormData.MFBContestData = MFBApp.ReadMFBInputs('.MFBContestInput');
		//FB.getLoginStatus(function(response) {
			  /*if (response.status == 'connected') {
			    // logged in and connected user, someone you know
				 SubmitContestForm(response);
			  } else {*/
			    // no user session available, someone you dont know
					 FB.ui({
						   method: 'permissions.request',
						   'perms': MFBApp.objSettings.strPermissions,
						   'display': 'iframe'
						  },
						  SubmitContestForm
					);
			//}
		//});

		
	}
	
	function SubmitContestForm(objResponse){
		
		//console.log(MFBContestApp.FormData);
		MFBApp.TriggerEvent('.MFBSubmitButton_submit', objResponse, MFBContestApp.FormData);
	}
	
	var objPublic = {
		Init:function(objNewSettings){
			objNewSettings['CONTEST_SELECTOR'] = '.MFBContestInput';
			objNewSettings['SUBMIT_BUTTON_SELECTOR'] = '.MFBSubmitButton';
			MFBApp.Init(objNewSettings);
			MFBApp.CallBacks.ValidateContestForm = ValidateContestForm;

			
			//Custome Events
			var jCollContestInputs = $(MFBApp.objSettings['CONTEST_SELECTOR']);
			for(var i = 0; i < jCollContestInputs.length; i++){
				var jInput = $(jCollContestInputs[i]);
				
			}
			$(MFBApp.objSettings['SUBMIT_BUTTON_SELECTOR']).live('click', ValidateContestForm);
			
		},
		Validate:function(){
			 var objData = MFBApp.ValidateMFBInputs('.MFBContestInput');
			 //console.log(objData);
			 var strMessage = '';
			 if(!objData.AllValid){
				 for(i in objData){
					 if((i != 'AllValid') && (!objData[i].Valid)){
						 strMessage += objData[i].Message + "\n";
					 }
				 }
				 alert(strMessage);
				 return false;
			 }else{
				 return true
			 }
		},
		FormData:{ }
	};
	window.MFBContestApp = objPublic;
	//Add custom JavaScript Functionality here
})(window);