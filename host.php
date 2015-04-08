<?php
// http://forum.synology.com/enu/viewtopic.php?f=10&t=41181&start=30

	define('USER_IS_PREMIUM', 6); 
	// GetDownloadInfo result array indexes
	define('DEBUG_TO_FILE', FALSE); // if false echo debug info
	define("DEBUG_FILE", "/volume1/Downloads/putio.log");
	
	class SynoFileHostingPutio {   
		private $Url;
		private $Username;
		private $Password;
		private $HostInfo;
		private $PUTIO_COOKIE = '/tmp/putio4.cookie';
		private $PUTIO_LOGIN_URL = 'https://put.io/login';

		public function __construct($Url, $Username, $Password, $HostInfo) {
			$this->Url = $Url;;   
			$this->Username = $Username;
			$this->Password = $Password;
			$this->HostInfo = $HostInfo; // not used   
		}

		//This function returns download url.
		public function GetDownloadInfo() {
			$DownloadInfo = array(); // result

			//Check to see is http or https being used
			if (substr($this->Url,0,7) == "http://") {
			   $newHttp = "http://";
			} else if (substr($this->Url,0,8) == "https://") {
			   $newHttp = "https://";
			} else {
			   $DownloadInfo[DOWNLOAD_URL] = ERR_NOT_SUPPORT_TYPE;
			   return $DownloadInfo;
			}

			//Format URL as http\s://USERNAME:PASSWORD@URL
			$urlTidy = str_replace($newHttp,"", $this->Url); 
			$newUrl = $newHttp . $this->Username . ":" . $this->Password . "@" . $this->Url;

			$DownloadInfo[DOWNLOAD_URL] = $newUrl;

			//debug("URL: ", $DownloadInfo[DOWNLOAD_URL]);   
			return $DownloadInfo;
		}

		/* 
		Always premium account type
		*/ 
		public function Verify()
		{
			return $this->PutioLogin();
		}

		//This function performs login action.
		private function PutioLogin() {
			$ret = LOGIN_FAIL;
			//Save cookie file
			$PostData = array(
				'name'=>$this->Username,
				'password'=>$this->Password
			);

			$queryUrl = $this->PUTIO_LOGIN_URL;
			$PostData = http_build_query($PostData);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $PostData);
			curl_setopt($curl, CURLOPT_USERAGENT, DOWNLOAD_STATION_USER_AGENT);
			// curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $this->PUTIO_COOKIE);
			curl_setopt($curl, CURLOPT_HEADER, TRUE);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_URL, $queryUrl);
			$LoginInfo = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);

			if ($httpcode == 403 || $httpcode == '403') {
				$ret = LOGIN_FAIL;
				return $ret; 
			}

			//auth is putio logged in cookie value
			if (FALSE != $LoginInfo && file_exists($this->PUTIO_COOKIE)) {
				$cookieData = file_get_contents($this->PUTIO_COOKIE);
				if(strpos($cookieData,'session2') !== false) {
					$ret = USER_IS_PREMIUM;
					return $ret;
				} else {
					$ret = LOGIN_FAIL;
					return $ret;
				}
			}
			$ret = LOGIN_FAIL;
			return $ret;
		}
	}
?>