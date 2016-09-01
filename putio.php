<?php

	class SynoFileHostingPutio {   
		private $Url;
		private $Username;
		private $Password;
		private $HostInfo;
		private $AccessToken;
		private $Login2Token;
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
			
			if ($this->PutioLogin() == LOGIN_FAIL) {
				$DownloadInfo = array();
				$DownloadInfo[DOWNLOAD_ERROR] = LOGIN_FAIL;
				return $DownloadInfo;
			} else {
				
				$DownloadInfo = array();
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
				
				if ($urlArray[1] === 'v2' || ($urlArray[1] === 'files' || $urlArray[1] === 'file')) {
					if ($urlArray[1] === 'v2') {
						$fileID = $urlArray[3];
						$mp4Check = $urlArray[4];
					} else {
						$fileID = $urlArray[2];
						$mp4Check = $urlArray[3];
					}
					$DownloadInfo[DOWNLOAD_URL] = 'https://put.io/v2/files/' . $fileID . ($mp4Check === 'mp4' ? '/mp4':'') . '/download?token=' . $this->AccessToken;
				} elseif ($urlArray[1] === 'zipstream') {
					$query = explode('?', $urlArray[2]);
					$parameters = explode('&', $query[1]);
					$DownloadInfo[DOWNLOAD_URL] = 'https://' . $urlArray[0] . '/zipstream/' . $query[0] . '?' . $parameters[0] . '&token=' . $this->AccessToken . '&' . $parameters[2];
				} else {
					$DownloadInfo[DOWNLOAD_ERROR] = ERR_FILE_NO_EXIST;
				}
				
				return $DownloadInfo;
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
				
				if(strpos($cookieData,'login_token2') !== false) {
					
					// $myfile = fopen("/tmp/putio.json", "w") or die("Unable to open file!");
					// $txt = $this->Username . '-' . $this->Password;
					// fwrite($myfile, $txt);
					// fclose($myfile);
					
					foreach (explode(PHP_EOL, $cookieData) as $key => $value) {
						if (strpos($value, "login_token2") !== false) {
							$parsed = explode("login_token2", $value);
							$this->Login2Token = preg_replace('/\s+/', '', $parsed[1]);
						}
					}
					
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
		
		public function PutioAccessToken(){
			// create curl for getting access token
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_USERAGENT, DOWNLOAD_STATION_USER_AGENT);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $this->PUTIO_COOKIE);
			curl_setopt($curl, CURLOPT_HEADER, TRUE);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Putio-LoginToken: ' . $this->Login2Token));
			curl_setopt($curl, CURLOPT_URL, 'https://put.io/v2/account/info?access_token=1');
			$AccessInfo = curl_exec($curl);
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($AccessInfo, 0, $header_size);
			$body = substr($AccessInfo, $header_size);
			curl_close($curl);
			
			$obj = json_decode($body, TRUE);
			$this->AccessToken = $obj['info']['access_token'];
		}
	}
?>