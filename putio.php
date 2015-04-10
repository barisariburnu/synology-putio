<?php
	define('USER_IS_PREMIUM', 6); 
	define('LOGIN_FAIL', 4);
	define('USER_IS_PREMIUM', 6);
	define('ERR_FILE_NO_EXIST', 114);
	define('DOWNLOAD_STATION_USER_AGENT', "Mozilla/4.0 (compatible; MSIE 6.1; Windows XP)");
	define('DOWNLOAD_URL', 'downloadurl'); // Real download url
	
	class SynoFileHostingPutio {   
		private $Url;
		private $Username;
		private $Password;
		private $HostInfo;
		private $AccessToken;
		private $PUTIO_COOKIE = '/tmp/putio.cookie';
		private $PUTIO_LOGIN_URL = 'https://put.io/login';

		public function __construct($Url, $Username, $Password, $HostInfo) {
			$this->Url = $Url;   
			$this->Username = $Username;
			$this->Password = $Password;
			$this->HostInfo = $HostInfo; // not used   
		}

		//This function returns download url.
		public function GetDownloadInfo() {
			
			if($this->PutioLogin()==LOGIN_FAIL) {
				$DownloadInfo = array();
				$DownloadInfo[DOWNLOAD_ERROR] = ERR_REQUIRED_PREMIUM;
				print('DOWNLOAD_ERROR: ' . $DownloadInfo[DOWNLOAD_ERROR]);
				return $DownloadInfo[DOWNLOAD_ERROR];
			} else {
				return $this->getDownloadLink();
			}
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

		private function PutioAccessToken(){
			// create curl for getting access token
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_USERAGENT, DOWNLOAD_STATION_USER_AGENT);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $this->PUTIO_COOKIE);
			curl_setopt($curl, CURLOPT_HEADER, TRUE);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_URL, 'https://soon.put.io/v2/account/info?access_token=1');
			$AccessInfo = curl_exec($curl);
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($AccessInfo, 0, $header_size);
			$body = substr($AccessInfo, $header_size);
			curl_close($curl);

			$obj = json_decode($body, TRUE);
			$this->AccessToken = $obj['info']['access_token'];
		}

		private function setDownloadLink() {
			//Check to see is http or https being used
			if (substr($this->Url,0,7) == "http://") {
			   $newHttp = "http://";
			} else if (substr($this->Url,0,8) == "https://") {
			   $newHttp = "https://";
			} else {
			   $DownloadInfo[DOWNLOAD_URL] = ERR_NOT_SUPPORT_TYPE;
			   return $DownloadInfo;
			}

			$urlTidy = str_replace($newHttp,"", $this->Url); 
			$urlArray = explode('/', $urlTidy);

			$this->PutioAccessToken();

			if ($urlArray[1] === 'v2') {
				$DownloadInfo[DOWNLOAD_URL] = 'https://put.io/v2/files/' . $urlArray[3] . '/download?token=' . $this->AccessToken;
			} elseif ($urlArray[1] === 'files' || $urlArray[1] === 'file') {
				$DownloadInfo[DOWNLOAD_URL] = 'https://put.io/v2/files/' . $urlArray[2] . '/download?token=' . $this->AccessToken;
			} else {
				$DownloadInfo[DOWNLOAD_ERROR] = ERR_FILE_NO_EXIST;
			}

			return $DownloadInfo; 
		}

		private function getDownloadLink() {
			$ret = false;
			$DownloadInfo = $this->setDownloadLink();
			$this->Url = $DownloadInfo[DOWNLOAD_URL];
			print('DOWNLOAD_URL: ' . $this->Url);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_USERAGENT, DOWNLOAD_STATION_USER_AGENT);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $this->PUTIO_COOKIE);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $this->Url);
			//curl exec has to be called before getinfo
			$info = curl_exec($curl);
			$info = curl_getinfo($curl);
			$url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL); 
			curl_close($curl);	
			$http_code = $info['http_code'];
			print('http_code: '. $http_code);

			//if 200 found in header - file is working and downloadable
			if ($http_code == 200) { 
				$DownloadInfo[DOWNLOAD_URL] = $url;
				return $DownloadInfo[DOWNLOAD_URL];
			} else {
				$DownloadInfo[DOWNLOAD_ERROR] = ERR_FILE_NO_EXIST;
				return $DownloadInfo[DOWNLOAD_ERROR];
			}	
		}
	}
?>