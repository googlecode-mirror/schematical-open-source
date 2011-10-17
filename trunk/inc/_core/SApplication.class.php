<?php
abstract class SApplication{
	const FILE_PERM = 0777;
	protected static $strAppName = null;
	protected static $objCurrentApp = null;
	protected static $arrRunFunctions = array();
	protected static $objHostServer = null;
	public static $arrIncludes = array();
	protected static $arrLoadedPackages = array();
	protected static $blnDBConnected = false;
	public static function Encrypt($string, $key) { 
		 $result = '';
		  for($i=0; $i<strlen($string); $i++) {
		    $char = substr($string, $i, 1);
		    $keychar = substr($key, ($i % strlen($key))-1, 1);
		    $char = chr(ord($char)+ord($keychar));
		    $result.=$char;
		  }
		  return base64_encode($result);
	}

	public static function Decrypt($string, $key) { 
		$result = '';
		  $string = base64_decode($string);
		  for($i=0; $i<strlen($string); $i++) {
		    $char = substr($string, $i, 1);
		    $keychar = substr($key, ($i % strlen($key))-1, 1);
		    $char = chr(ord($char)-ord($keychar));
		    $result.=$char;
		  }
		  return $result;
	}

	public static function AppName(){
		return self::$strAppName;
	}
	public static function AddRunFunction($strFunction){
		self::$arrRunFunctions[] = $strFunction;
	}
	
	public static function FB($strCall, $arrParams = array(), $strMethod = 'GET'){
		//if(!is_null(MFBAuthDriver::User())){
			$arrParams['access_token'] = MFBAuthDriver::User()->OAuthToken;
		//}
		return MLCFBDriver::FB($strCall, $arrParams, $strMethod);
	}

	public static function GetUserFBPages(){		
		$strCall = '/' . MFBAuthDriver::Fbuid() . '/accounts';
		return self::FB($strCall);
		
	}
	public static function GetPageData($intPageId){		
		$strCall = '/' . $intPageId;
		return self::FB($strCall);
		
	}
	
	public static function CopyDir($path, $dest ){		
		$strDirPath = dirname($dest);
        if(!is_dir($strDirPath)){        	
        	@mkdir( $strDirPath );
        	chmod($strDirPath, self::FILE_PERM);
        }
        if( is_dir($path) ){
            @mkdir( $dest );
            $objects = scandir($path);
            if( sizeof($objects) > 0 ){
                foreach( $objects as $file ){
                    if( $file == "." || $file == ".." ){
                        continue;
                    }
                    // go on
                    if( is_dir( $path.DS.$file ) ){
                        self::CopyDir( $path.DS.$file, $dest.DS.$file );
                    }else{
                        self::CopyDir( $path.DS.$file, $dest.DS.$file );
                    }
                }
            }
            return true;
        }elseif( is_file($path) ){
        	$blnSuccess = copy($path, $dest);
        	chmod($dest, self::FILE_PERM);
            return $blnSuccess;
        }else{
            return false;
        }

	}
	/**
	 * BootPage
	 * This page initilizes a page 
	 * @throws Exception
	 */
	public static function Run($strAppName){
		
		self::$strAppName = $strAppName;
		define('APP_FRONTEND_DIR', APP_DIR . '/' .$strAppName);
		define('__APP_URL__', __BASE_URL__ . '/apps/' . $strAppName);
		define('__CUSTOME_CONFIG_FILE__', APP_FRONTEND_DIR . '/_config.inc.php');
		if(file_exists(__CUSTOME_CONFIG_FILE__)){
			require_once(__CUSTOME_CONFIG_FILE__);
		}
		
		if(defined('APP_TEMPLATE_LOC')){
			$arrParts = explode('-',APP_TEMPLATE_LOC);
			if(count($arrParts) == 2){
				$intIdAppTemplate = $arrParts[0];
				$intIdAppTemplateVersion = $arrParts[1];
			}else{
				$arrParts = explode('/', APP_TEMPLATE_LOC);
				
				if(count($arrParts) != 2){
					throw new Exception("Invalid 'APP_TEMPLATE_LOC' defined");
				}
				
				$intIdAppTemplate = $arrParts[0];
				$intIdAppTemplateVersion = $arrParts[1];
			}
		}else{
			throw new Exception("APP_TEMPLATE_DIR not defined");
		}
		
		define('APP_BACKEND_DIR', sprintf('%s/%s/%s',  __APP_TPL_DIR__, $intIdAppTemplate, $intIdAppTemplateVersion));		
		define('APP_BACKEND_CORE_DIR', APP_BACKEND_DIR . '/_core');		 
		
		define('APP_CONTROL_LOC', APP_FRONTEND_DIR . '/index.php');
			
		
		//TODO add definitions for new AppTemplate assets
		if(!defined('__CUST_FB_APP_ID__')){
			define('__FB_APP_ID__', __GLOBAL_FB_APP_ID__);
		}else{
			define('__FB_APP_ID__', __CUST_FB_APP_ID__);
		}
		/*if(!defined('__CUST_FB_APP_KEY__')){
			define('__FB_APP_KEY__', __GLOBAL_FB_APP_KEY__);
		}else{
			define('__FB_APP_KEY__', __CUST_FB_APP_KEY__);
		}*/
		if(!defined('__CUST_FB_APP_SECRET__')){
			define('__FB_APP_SECRET__', __GLOBAL_FB_APP_SECRET__);
		}else{
			define('__FB_APP_SECRET__', __CUST_FB_APP_SECRET__);
		}	
		
		//Exicute the functions defined in Framework.php
		foreach(self::$arrRunFunctions as $intIndex=>$strFunction){
			$strFunction();//Should we pass it any info?
		}
		
		require_once APP_CONTROL_LOC;
		
	}
	public static function LoadPackage($strPackageName, $strPackageVersion){
		$strPath = __PACKAGE_DIR__ . DS . $strPackageName . DS . $strPackageVersion . DS . 'prepend.inc.php';
		if(file_exists($strPath)){
			require_once($strPath);
		}else{
			throw new Exception(sprintf("Package '%s' Version '%s' does not exist", $strPackageName, $strPackageVersion));
		}
		self::$arrLoadedPackages[$strPackageName] = $strPackageVersion;
		
	}
	public static function CheckPackageVersion($strPackageName){
		if(!array_key_exists($strPackageName, self::$arrLoadedPackages)){
			return null;		
		}
		return self::$arrLoadedPackages[$strPackageName];
	}
	public static function AddClass($strClassName, $strPath, $blnLoadNow = false){
		SApplication::$arrIncludes[$strClassName] = $strPath;
		if($blnLoadNow){
			require_once($strPath);
		}
	}
	public static function GetPackages(){
		return self::$arrLoadedPackages;
	}
	public static function InitDB($strDataBaseConnection){
		if(!self::$blnDBConnected){
			self::$blnDBConnected = true;
			$arrDBInfo = unserialize($strDataBaseConnection);
	
			$objDataConnection = new MySqlDataConnection($arrDBInfo['host'], $arrDBInfo['db_name'], $arrDBInfo['user'], $arrDBInfo['password']);
			$objDataConnection->Connect();
			LoadDriver::AddDataConnection($objDataConnection);
		}
	}
	public static function InstallApp($strAppName){
		require(APP_DIR . '/' . $strAppName . '/_config.inc.php');
		foreach(self::$arrLoadedPackages as $strPackageName => $strVersion){
			require(__PACKAGE_DIR__ . '/' . $strPackageName . '/' . $strVersion . '/install.inc.php');
		}
	}
	public static function ExicuteOnDB($strExicute, $strDBName = 'DATABASE_1'){
		
		self::InitDB(constant($strDBName));
		return LoadDriver::Query($strExicute);
	}
	public static function GetPhpAssetUrl($strFramework, $strVersion, $strCtlFile){
		return __PHP_ASSET_URL_BASE__ . '?' . MFBQS::PACKAGE . '=' . $strFramework . '&' . MFBQS::VERSION . '=' . $strVersion . '&' . MFBQS::CTL_FILE . '=' . $strCtlFile; 
	}
	public static function BootPhpAsset($strPackageName, $strPackageVersion, $strCtlFile){
		$strCtlFile = str_replace('../', '', $strCtlFile);
		$strCtlFileLoc = __PACKAGE_DIR__ . '/' . $strPackageName . '/' . $strPackageVersion . '/inc/php/' . $strCtlFile;
		self::LoadPackage($strPackageName, $strPackageVersion);
		
		require_once($strCtlFileLoc);
	}
	
}