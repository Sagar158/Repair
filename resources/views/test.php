<?php require_once('load.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header('Content-type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$result = array('code'=> 100, 'status' => 'waiting' , 'response' => '', 'link' => strtolower("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '') {
	$req_arr = explode("api/", $_SERVER['REQUEST_URI']);
	$req_arr = explode("/", $req_arr[1]);
	
	if(isset($req_arr[0]) && $req_arr[0] != '') {
		$controller = strtolower($db->escape_value($req_arr[0]));
	} else {
		$controller = "";
	}
	if(isset($req_arr[1]) && $req_arr[1] != '') {
		$action = strtolower($db->escape_value($req_arr[1]));
	} else {
		$action = "";
	}
	if(isset($req_arr[2]) && $req_arr[2] != '') {
		$id = $db->escape_value($req_arr[2]);
	} else {
		$id = "";
	}
	if(isset($req_arr[3]) && $req_arr[3] != '') {
		$extra = $db->escape_value($req_arr[3]);
	} else {
		$extra = "";
	}
} else {
	$controller = "";
	$action = "";
	$id = "";
	$extra = "";
}
if(isset($siteGuard_settings['api']) && $siteGuard_settings['api'] == 'on' ) {

		switch ($controller) {
			case 'prepare-user-jwt' :
			
			if(!isset($_POST['api_key'])) {
				$result['code'] = 401;
				$result['status'] = 'error';
				$result['response'] = 'Invalid User API key';
				echo json_encode($result);
				die();
			}	
			
			$sent_api_key = mjdecode($_POST['api_key'], $siteGuard_settings['api_salt']);
			
				if(is_numeric($action)) {
						if(!User::check_id_existance($action)) {
							$result['code'] = 404;
							$result['status'] = 'error';
							$result['response'] = "User not found!";
							echo json_encode($result);
							die();
						} else {
							$user = User::get_specific_id($action);
							if($sent_api_key != $user->api_key) {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Invalid User API Key";
								echo json_encode($result);
								die();
							}
							
							$jwt = $siteGuard->encode_jwt($_POST['api_key']);
							$result['code'] = 200;
							$result['status'] = 'success';
							$result['response'] = $jwt;
							echo json_encode($result);
							die();
						}
				} else {
					$result['code'] = 400;
					$result['status'] = 'error';
					$result['response'] = 'Please specify valid User ID';
					echo json_encode($result);
					die();
				}
			break;
			
			case 'prepare-public-jwt' :
			if(!isset($_POST['api_key'])) {
				$result['code'] = 401;
				$result['status'] = 'error';
				$result['response'] = 'Invalid Public API key';
				echo json_encode($result);
				die();
			}	
			
			$sent_api_key = mjdecode($_POST['api_key'], $siteGuard_settings['api_salt']);
			
				if($sent_api_key != $siteGuard_settings['api_key']) {
					$result['code'] = 403;
					$result['status'] = 'error';
					$result['response'] = "Invalid Public API Key";
					echo json_encode($result);
					die();
				}
				
				$jwt = $siteGuard->encode_jwt($_POST['api_key']);
				$result['code'] = 200;
				$result['status'] = 'success';
				$result['response'] = $jwt;
				echo json_encode($result);
				die();
			break;
			
			case 'auth' :
				if($action == 'reset-password') {
					if(is_numeric($id)) {
						if(!User::check_id_existance($id)) {
							$result['code'] = 404;
							$result['status'] = 'error';
							$result['response'] = "User not found!";
						} else {
							$user = User::get_specific_id($id);

							/* Check JWT Validity */
							$jwt = getBearerToken();
							if($jwt) {
								try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
								if(is_array($info)) {
									if($info['type'] == 'success') {
										$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
										if($sent_api_key != $user->api_key) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = "Invalid User API Key";
											echo json_encode($result);
											die();
										}
									} else {
										$result['code'] = 401;
										$result['status'] = 'error';
										$result['response'] = $info['api_key'];
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = "Invalid Authorization Bearer.";
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Missing Authorization Bearer.";
								echo json_encode($result);
								die();
							}
							/***************/
							
							if ($user->closed == "1") {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = 'Account closed! please contact system administration.';
							} elseif ($user->disabled == "1") {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = 'Account banned! please contact system administration.';
							} elseif($user->pending == "1") {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = 'Account pending admin approval.';
							} elseif($user->throttle_from != '' && time() < $user->throttle_from + $user->throttle_time) {
								$then = ($user->throttle_from + $user->throttle_time) - time();
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Account Locked ! Please try again after " . secondsToTime($then);
							} else {
							
								if(isset($_POST['current_password']) && $_POST['current_password'] != '' && isset($_POST['new_password']) && $_POST['new_password'] != '' && isset($_POST['confirm_new_password']) && $_POST['confirm_new_password'] != '') {
									$current_password = $db->escape_value($_POST['current_password']);
									$new_password = $db->escape_value($_POST['new_password']);
									$confirm_new_password = $db->escape_value($_POST['confirm_new_password']);
									if($new_password != $confirm_new_password) {
										$result['code'] = 400;
										$result['status'] = 'error';
										$result['response'] = 'passwords does not match.';
									} else {
										
										if($user->tfa && isset($siteGuard_settings['2fa']) && $siteGuard_settings['2fa']  == 'on' ) {
											
											
											if(!isset($_POST['otp'])) {
												$error_message = "OTP Code is invalid! please try again.";
												if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
													$user->invalid_login($siteGuard_settings['attempts']);
													$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $user->invalid_logins;
													if($attempts < 0) {
														$attempts = 0;
													}
													$error_message .= " you have ({$attempts}) attempts left";
												}
												$result['code'] = 403;
												$result['status'] = 'error';
												$result['response'] = $error_message;
											} else {
												$ga = new Authenticator();
												$otp = $db->escape_value($_POST['otp']);
												$backup_pass = false;
												$checkResult = $ga->verify($user->tfa_secret, $otp);
												if($user->tfa_codes) {
													$backup_codes = explode(',' , $user->tfa_codes);
													if (in_array($otp, $backup_codes)) {
														$backup_pass = true;
														$key = array_search($otp, $backup_codes);
														unset($backup_codes[$key]);
														$user->tfa_codes = implode(',' , $backup_codes);
													}
												}
												if($checkResult || $backup_pass == true) {
													$phpass = new PasswordHash(8, true);
													if($phpass->CheckPassword($current_password, $user->password)) {
														$hashedpassword = $phpass->HashPassword($new_password);
														$user->password = $hashedpassword;	
														if($user->update()) {
															Log::log_action($user->id , "Change Password" , "Change password via API Call");
															if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {$user->clear_invalid_login();}
															$result['code'] = 200;
															$result['status'] = 'success';
															$result['response'] = 'password changed successfully.';
														} else {
															$result['code'] = 400;
															$result['status'] = 'error';
															$result['response'] = 'No changes detected in user data.';
														}
													} else {
														$result['code'] = 400;
														$result['status'] = 'error';
														$result['response'] = 'Wrong password.';
													}
												} else {
													$error_message = "OTP Code is invalid! please try again.";
													if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
														$user->invalid_login($siteGuard_settings['attempts']);
														$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $user->invalid_logins;
														if($attempts < 0) {
															$attempts = 0;
														}
														$error_message .= " you have ({$attempts}) attempts left";
													}
													$result['code'] = 403;
													$result['status'] = 'error';
													$result['response'] = $error_message;
												}
												
											}
										
											
											
										} else {
										
											$phpass = new PasswordHash(8, true);
											if($phpass->CheckPassword($current_password, $user->password)) {
												$hashedpassword = $phpass->HashPassword($new_password);
												$user->password = $hashedpassword;	
												if($user->update()) {
													Log::log_action($user->id , "Change Password" , "Change password via API Call");
													$result['code'] = 200;
													$result['status'] = 'success';
													$result['response'] = 'password changed successfully.';
												} else {
													$result['code'] = 400;
													$result['status'] = 'error';
													$result['response'] = 'No changes detected in user data.';
												}
											} else {
												$result['code'] = 400;
												$result['status'] = 'error';
												$result['response'] = 'Wrong password.';
											}
										}
									}
									
								} else {
									$result['code'] = 400;
									$result['status'] = 'error';
									$result['response'] = 'Please enter all required fields.';
								}
							}
						}
					} else {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please specify valid User ID';
					}
					
				} elseif($action == 'logout') {
					
					if ($siteguard_session->is_logged_in() == true ) {
							$current_user = User::get_specific_id($siteguard_session->get_admin_id());
							
							/* Check JWT Validity */
							$jwt = getBearerToken();
							if($jwt) {
								try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
								if(is_array($info)) {
									if($info['type'] == 'success') {
										$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
										if($sent_api_key != $siteGuard_settings['api_key']) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = "Invalid Public API Key";
											echo json_encode($result);
											die();
										}
									} else {
										$result['code'] = 401;
										$result['status'] = 'error';
										$result['response'] = $info['api_key'];
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = "Invalid Authorization Bearer.";
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Missing Authorization Bearer.";
								echo json_encode($result);
								die();
							}
							/***************/
							
							$current_user->last_seen = "0";
							$current_user->update();
							$online = Online::get_everything(" AND user_id = '{$current_user->id}' ");
							if($online) {
								foreach($online as $onl) {
									$onl->delete();
								}
							}
							$_SESSION = array();
							if (isset($_COOKIE[session_name()])) {
								setcookie(session_name() , '' , time()-42000 , '/');		
							}
							session_destroy();
							$result['code'] = 200;
							$result['status'] = 'success';
							$result['response'] = 'true';
						} else {
							$result['code'] = 400;
							$result['status'] = 'error';
							$result['response'] = 'Please login first!';
						}
					
				} elseif($action == 'login') {
					if ($siteguard_session->is_logged_in() == true ) {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please logout first!';
					} else {
						/* Check JWT Validity */
						$jwt = getBearerToken();
						if($jwt) {
							try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
							if(is_array($info)) {
								if($info['type'] == 'success') {
									$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
									if($sent_api_key != $siteGuard_settings['api_key']) {
										$result['code'] = 403;
										$result['status'] = 'error';
										$result['response'] = "Invalid Public API Key";
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = $info['api_key'];
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Invalid Authorization Bearer.";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = "Missing Authorization Bearer.";
							echo json_encode($result);
							die();
						}
						/***************/
						if(isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != '' ) {
							$username = trim($_POST["username"]);
							$password = trim($_POST["password"]);
							$found_user =User::hash_authenticate($username);
							if ($found_user) {
								if ($found_user->disabled == "1") {
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = 'Account banned! please contact system administration.';
								} elseif($found_user->pending == "1") {
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = 'Account pending admin approval.';
								} elseif($found_user->throttle_from != '' && time() < $found_user->throttle_from + $found_user->throttle_time) {
									$then = ($found_user->throttle_from + $found_user->throttle_time) - time();
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = "Account Locked ! Please try again after " . secondsToTime($then);
								} else  {
									
									$group = Group::get_specific_id($found_user->prvlg_group);
									if($group->max_connections) {
										$cur_connections = Online::count_everything(" AND user_id = '{$found_user->id}' ");
										if ($cur_connections > $group->max_connections) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = 'This account has reached the maximum number of simultaneous sessions.';
											echo json_encode($result);
											die();
										}
									}
									
									$saltedhash = $found_user->password;
									$phpass = new PasswordHash(8, true);
									if ($phpass->CheckPassword($password, $saltedhash)) {
										if($found_user->tfa && isset($siteGuard_settings['2fa']) && $siteGuard_settings['2fa']  == 'on' ) {
											
											if(!isset($_POST['otp'])) {
												$error_message = "OTP Code is invalid! please try again.";
												if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
													$found_user->invalid_login($siteGuard_settings['attempts']);
													$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
													if($attempts < 0) {
														$attempts = 0;
													}
													$error_message .= " you have ({$attempts}) attempts left";
												}
												$result['code'] = 403;
												$result['status'] = 'error';
												$result['response'] = $error_message;
											} else {
												$ga = new Authenticator();
												$otp = $db->escape_value($_POST['otp']);
												$backup_pass = false;
												$checkResult = $ga->verify($found_user->tfa_secret, $otp);
												if($found_user->tfa_codes) {
													$backup_codes = explode(',' , $found_user->tfa_codes);
													if (in_array($otp, $backup_codes)) {
														$backup_pass = true;
														$key = array_search($otp, $backup_codes);
														unset($backup_codes[$key]);
														$found_user->tfa_codes = implode(',' , $backup_codes);
													}
												}
												if($checkResult || $backup_pass == true) {
													$siteguard_session->login($found_user);
													if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {$found_user->clear_invalid_login();}
													Log::log_action($found_user->id , "Login" , "Login to system via API Call");
													$result['code'] = 200;
													$result['status'] = 'success';
													$result['response'] = 'Logged in successfully.';
												} else {
													$error_message = "OTP Code is invalid! please try again.";
													if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
														$found_user->invalid_login($siteGuard_settings['attempts']);
														$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
														if($attempts < 0) {
															$attempts = 0;
														}
														$error_message .= " you have ({$attempts}) attempts left";
													}
													$result['code'] = 403;
													$result['status'] = 'error';
													$result['response'] = $error_message;
												}
												
											}
										} else {
											$siteguard_session->login($found_user);
											if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {$found_user->clear_invalid_login();}
											Log::log_action($found_user->id , "Login" , "Login to system via API Call");
											$result['code'] = 200;
											$result['status'] = 'success';
											$result['response'] = 'Logged in successfully.';
										}
									} else {
										$result['code'] = 403;
										$result['status'] = 'error';
										$error_message = "Wrong password! please try again.";
										if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
											$found_user->invalid_login($siteGuard_settings['attempts']);
											$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
											$error_message .= " you have ({$attempts}) attempts left";
										}
										$result['response'] = $error_message;
									}
								}
								
							} else {
								$result['code'] = 404;
								$result['status'] = 'error';
								$result['response'] = 'User not found!';
							}
							
						} else {
							$result['code'] = 400;
							$result['status'] = 'error';
							$result['response'] = 'Please enter valid username/password';
						}
					}
				}
 elseif($action == 'social') {
					if ($siteguard_session->is_logged_in() == true ) {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please logout first!';
					} else {
						/* Check JWT Validity */
						$jwt = getBearerToken();
						if($jwt) {
							try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
							if(is_array($info)) {
								if($info['type'] == 'success') {
									$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
									if($sent_api_key != $siteGuard_settings['api_key']) {
										$result['code'] = 403;
										$result['status'] = 'error';
										$result['response'] = "Invalid Public API Key";
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = $info['api_key'];
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Invalid Authorization Bearer.";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = "Missing Authorization Bearer.";
							echo json_encode($result);
							die();
						}
						/***************/
						if(isset($_POST['username']) && $_POST['username'] != '' ) {
							$username = trim($_POST["username"]);
						
							$found_user =User:: firebase_auth($username);
							if ($found_user) {
								if ($found_user->disabled == "1") {
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = 'Account banned! please contact system administration.';
								} elseif($found_user->pending == "1") {
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = 'Account pending admin approval.';
								} elseif($found_user->throttle_from != '' && time() < $found_user->throttle_from + $found_user->throttle_time) {
									$then = ($found_user->throttle_from + $found_user->throttle_time) - time();
									$result['code'] = 403;
									$result['status'] = 'error';
									$result['response'] = "Account Locked ! Please try again after " . secondsToTime($then);
								} else  {
									
									$group = Group::get_specific_id($found_user->prvlg_group);
									if($group->max_connections) {
										$cur_connections = Online::count_everything(" AND user_id = '{$found_user->id}' ");
										if ($cur_connections > $group->max_connections) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = 'This account has reached the maximum number of simultaneous sessions.';
											echo json_encode($result);
											die();
										}
									}
									
									$saltedhash = $found_user->password;
									$phpass = new PasswordHash(8, true);
									if ($phpass->CheckPassword($password, $saltedhash)) {
										if($found_user->tfa && isset($siteGuard_settings['2fa']) && $siteGuard_settings['2fa']  == 'on' ) {
											
											if(!isset($_POST['otp'])) {
												$error_message = "OTP Code is invalid! please try again.";
												if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
													$found_user->invalid_login($siteGuard_settings['attempts']);
													$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
													if($attempts < 0) {
														$attempts = 0;
													}
													$error_message .= " you have ({$attempts}) attempts left";
												}
												$result['code'] = 403;
												$result['status'] = 'error';
												$result['response'] = $error_message;
											} else {
												$ga = new Authenticator();
												$otp = $db->escape_value($_POST['otp']);
												$backup_pass = false;
												$checkResult = $ga->verify($found_user->tfa_secret, $otp);
												if($found_user->tfa_codes) {
													$backup_codes = explode(',' , $found_user->tfa_codes);
													if (in_array($otp, $backup_codes)) {
														$backup_pass = true;
														$key = array_search($otp, $backup_codes);
														unset($backup_codes[$key]);
														$found_user->tfa_codes = implode(',' , $backup_codes);
													}
												}
												if($checkResult || $backup_pass == true) {
													$siteguard_session->login($found_user);
													if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {$found_user->clear_invalid_login();}
													Log::log_action($found_user->id , "Login" , "Login to system via API Call");
													$result['code'] = 200;
													$result['status'] = 'success';
													$result['response'] = 'Logged in successfully.';
												} else {
													$error_message = "OTP Code is invalid! please try again.";
													if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
														$found_user->invalid_login($siteGuard_settings['attempts']);
														$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
														if($attempts < 0) {
															$attempts = 0;
														}
														$error_message .= " you have ({$attempts}) attempts left";
													}
													$result['code'] = 403;
													$result['status'] = 'error';
													$result['response'] = $error_message;
												}
												
											}
										} else {
											$siteguard_session->login($found_user);
											if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {$found_user->clear_invalid_login();}
											Log::log_action($found_user->id , "Login" , "Login to system via API Call");
											$result['code'] = 200;
											$result['status'] = 'success';
											$result['response'] = 'Logged in successfully.';
										}
									} else {
										$result['code'] = 403;
										$result['status'] = 'error';
										$error_message = "Wrong password! please try again.";
										if(isset($siteGuard_settings['disable_after']) && $siteGuard_settings['disable_after'] == "on" ) {
											$found_user->invalid_login($siteGuard_settings['attempts']);
											$attempts = str_replace('-','',$siteGuard_settings['attempts']) - $found_user->invalid_logins;
											$error_message .= " you have ({$attempts}) attempts left";
										}
										$result['response'] = $error_message;
									}
								}
								
							} else {
								
							}
							
						} else {
							    $name = $db->escape_value($_POST['name']);
								$email = $username."@firebase.com";
						
								$password = rand(100000,999999);
								
							
									$email_exists = User::check_existance("email", $email , " AND deleted = 0 ");
									if($email_exists) {
										$result['code'] = 400;
										$result['status'] = 'error';
										$result['response'] = "Email already exists in database! please try again";
									} else {
										$username_exists = User::check_existance("username", $username);
										if($username_exists) {
											$result['code'] = 400;
											$result['status'] = 'error';
											$result['response'] = "Username already exists in database! please try again";
										} else {
											$acc = New User();
											$acc->name = $name;
											$acc->email = $email;
											$acc->username = $username;
											$phpass = new PasswordHash(8, true);
											$hashedpassword = $phpass->HashPassword($password);
											$acc->prvlg_group = $siteGuard_settings['registration_group'];
											$acc->password = $hashedpassword;
											$acc->registered = strftime("%Y-%m-%d %H:%M:%S");
											$acc->pending = 0;
											if($acc->create()) {
												$result['code'] = 200;
												$result['status'] = 'success';
												Log::log_action($acc->id , "Register" , "Register account via API Call");
												$result['response'] = "Account created successfully! please wait admin approval.";
											} else {
												$result['code'] = 400;
												$result['status'] = 'error';
												$result['response'] = "Account creation failed! Please try registering again";
											}
										}
									}
								
						}
					}
				}				elseif($action == 'register') {
					
					
												
							/* Check JWT Validity */
							$jwt = getBearerToken();
							if($jwt) {
								try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
								if(is_array($info)) {
									if($info['type'] == 'success') {
										$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
										if($sent_api_key != $siteGuard_settings['api_key']) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = "Invalid Public API Key";
											echo json_encode($result);
											die();
										}
									} else {
										$result['code'] = 401;
										$result['status'] = 'error';
										$result['response'] = $info['api_key'];
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = "Invalid Authorization Bearer.";
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Missing Authorization Bearer.";
								echo json_encode($result);
								die();
							}
							/***************/
							
							if(isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['confirm_password']) && $_POST['confirm_password'] != '' ) {
								$name = $db->escape_value($_POST['name']);
								$email = $db->escape_value($_POST['email']);
								$username = $db->escape_value(trim(str_replace(' ','',$_POST['username'])));
								$password = $db->escape_value($_POST['password']);
								$confirm_password = $db->escape_value($_POST['confirm_password']);
								if($password != $confirm_password) {
									$result['code'] = 400;
									$result['status'] = 'error';
									$result['response'] = 'Confirm password does not match';
								} else {
									$email_exists = User::check_existance("email", $email , " AND deleted = 0 ");
									if($email_exists) {
										$result['code'] = 400;
										$result['status'] = 'error';
										$result['response'] = "Email already exists in database! please try again";
									} else {
										$username_exists = User::check_existance("username", $username);
										if($username_exists) {
											$result['code'] = 400;
											$result['status'] = 'error';
											$result['response'] = "Username already exists in database! please try again";
										} else {
											$acc = New User();
											$acc->name = $name;
											$acc->email = $email;
											$acc->username = $username;
											$phpass = new PasswordHash(8, true);
											$hashedpassword = $phpass->HashPassword($password);
											$acc->prvlg_group = $siteGuard_settings['registration_group'];
											$acc->password = $hashedpassword;
											$acc->registered = strftime("%Y-%m-%d %H:%M:%S");
											$acc->pending = 0;
											if($acc->create()) {
												$result['code'] = 200;
												$result['status'] = 'success';
												Log::log_action($acc->id , "Register" , "Register account via API Call");
												$result['response'] = "Account created successfully! please wait admin approval.";
											} else {
												$result['code'] = 400;
												$result['status'] = 'error';
												$result['response'] = "Account creation failed! Please try registering again";
											}
										}
									}
								}
							} else {
								$result['code'] = 400;
								$result['status'] = 'error';
								$result['response'] = 'Please enter all required fields';
							}
							
										}else {
					$result['code'] = 400;
					$result['status'] = 'error';
					$result['response'] = 'Please specify valid function for (Auth) Model';
				}
			break;
			
			case 'user' :
				if($action == 'update') {
					
					if(is_numeric($id)) {
						
						if(!User::check_id_existance($id)) {
							$result['code'] = 404;
							$result['status'] = 'error';
							$result['response'] = "User not found!";
						} else {
							$user = User::get_specific_id($id);
							
							/* Check JWT Validity */
							$jwt = getBearerToken();
							if($jwt) {
								try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
								if(is_array($info)) {
									if($info['type'] == 'success') {
										$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
										if($sent_api_key != $user->api_key) {
											$result['code'] = 403;
											$result['status'] = 'error';
											$result['response'] = "Invalid User API Key";
											echo json_encode($result);
											die();
										}
									} else {
										$result['code'] = 401;
										$result['status'] = 'error';
										$result['response'] = $info['api_key'];
										echo json_encode($result);
										die();
									}
								} else {
									$result['code'] = 401;
									$result['status'] = 'error';
									$result['response'] = "Invalid Authorization Bearer.";
									echo json_encode($result);
									die();
								}
							} else {
								$result['code'] = 401;
								$result['status'] = 'error';
								$result['response'] = "Missing Authorization Bearer.";
								echo json_encode($result);
								die();
							}
							/***************/
							
							$qr_link= '';
							
						if(isset($_POST['password']) && $_POST['password'] != '' ) {
							$password = $db->escape_value($_POST['password']);
							$saltedhash = $user->password;
							$phpass = new PasswordHash(8, true);
							if ($phpass->CheckPassword($password, $saltedhash)) {
								if(isset($_POST['name']) && $_POST['name'] != '' ) {
									$name = $db->escape_value($_POST['name']);
									$user->name = $name;
								}if(isset($_POST['phone']) && $_POST['phone'] != '' ) {
									$phone = $db->escape_value($_POST['phone']);
									$user->mobile = $phone;
								}if(isset($_POST['address']) && $_POST['address'] != '' ) {
									$address = $db->escape_value($_POST['address']);
									$user->address = $address;
								}if(isset($_POST['email']) && $_POST['email'] != '' ) {
									$email = $db->escape_value($_POST['email']);
									$user->email = $email;
								}if(isset($_POST['banned']) && $_POST['banned'] == '1' && $id != '1' ) {
									if($_POST['banned'] == '1') {
										$user->disabled = 1;
									} elseif($_POST['banned'] == '0') {
										$user->disabled = 0;
									}
								}if(isset($_POST['tfa']) && $_POST['tfa'] != '' ) {
									if($_POST['tfa'] == '1') {
										$tfa = new Authenticator();
										$user->tfa = 1;
										if($user->tfa_secret == '') {
											$tfa_secret =$tfa->createSecret();
										} else {
											$tfa_secret =$user->tfa_secret;
										}
										$user->tfa_secret = $tfa_secret;
										if($user->tfa_codes == '') {
											$codes = array();
											for($i = 1 ; $i <= 5 ; $i++) {
												$codes[] = get_random_num(6);
											}
											$user->tfa_codes = implode(',',$codes);
										}
										if(isset($siteGuard_settings['site_name']) && $siteGuard_settings['site_name'] != '' ) { $site_name = $siteGuard_settings['site_name']; } else { $site_name = "SiteGuard"; }
										$qr_link = ''.$tfa->GetQR("{$site_name} ({$user->username})", $tfa_secret);
									} elseif($_POST['tfa'] == '0') {
										$user->tfa = 0;
										$user->tfa_secret = "";
										$user->tfa_codes = "";
									}
								}
								if($user->update()) {
									Log::log_action($user->id , "Update User" , "Update user info via API Call" );
									$result['code'] = 200;
									$result['status'] = 'success';
									if($qr_link) {
										$result['response'] = $qr_link;
									} else {
										$result['response'] = 'Data updated successfully.';
									}
								} else {
									$result['code'] = 200;
									$result['status'] = 'error';
									$result['response'] = 'No changes detected in user data';
								}
							} else {
								$result['code'] = 400;
								$result['status'] = 'error';
								$result['response'] = 'Wrong password.';
							}
								
							} else {
								$result['code'] = 400;
								$result['status'] = 'error';
								$result['response'] = 'Please enter user password.';
							}
						}
					} else {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please specify valid User ID';
					}
			
				} elseif($action == 'find') {
					
					/* Check JWT Validity */
				$jwt = getBearerToken();
				if($jwt) {
					try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
					if(is_array($info)) {
						if($info['type'] == 'success') {
							$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
							if($sent_api_key != $siteGuard_settings['api_key']) {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Invalid Public API Key";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = $info['api_key'];
							echo json_encode($result);
							die();
						}
					} else {
						$result['code'] = 401;
						$result['status'] = 'error';
						$result['response'] = "Invalid Authorization Bearer.";
						echo json_encode($result);
						die();
					}
				} else {
					$result['code'] = 401;
					$result['status'] = 'error';
					$result['response'] = "Missing Authorization Bearer.";
					echo json_encode($result);
					die();
				}
				/***************/
					
					if($id != '') {
						$query = " AND (name LIKE '%{$id}%' OR username LIKE '%{$id}%' OR email LIKE '%{$id}%' OR mobile LIKE '%{$id}%' )";
						$users = User::get_everything( " {$query} AND deleted = 0 ORDER BY name ASC " );
						$return_arr = array();
						if($users) {
							foreach($users as $user) {
								$group = Group::get_specific_id($user->prvlg_group);
								
								$user_arr = array();
								$user_arr['name'] = $user->name;
								$user_arr['email'] = $user->email;
								$user_arr['username'] = $user->username;
								$user_arr['phone'] = $user->mobile;
								$user_arr['address'] = $user->address;
								$user_arr['about'] = $user->about;
								$user_arr['registeration_date'] = $user->registered;
								$user_arr['access_level'] = $group->name;
								$user_arr['avatar'] = $user->get_avatar();
								if($extra != '' && array_key_exists($extra, $user_arr)) {
									$temp = $user_arr[$extra];
									$user_arr = array();
									$user_arr[$extra] = $temp;
								}
								
								$return_arr[] = $user_arr;
							}
							$result['code'] = 200;
							$result['status'] = 'success';
							$result['response'] = $return_arr;
						} else {
							$result['code'] = 404;
							$result['status'] = 'error';
							$result['response'] = 'No users found matching search criteria';
						}
					} else {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please enter search keyword.';
					}
				} elseif($action == 'get') {
					
					
				/* Check JWT Validity */
				$jwt = getBearerToken();
				if($jwt) {
					try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
					if(is_array($info)) {
						if($info['type'] == 'success') {
							$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
							if($sent_api_key != $siteGuard_settings['api_key']) {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Invalid Public API Key";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = $info['api_key'];
							echo json_encode($result);
							die();
						}
					} else {
						$result['code'] = 401;
						$result['status'] = 'error';
						$result['response'] = "Invalid Authorization Bearer.";
						echo json_encode($result);
						die();
					}
				} else {
					$result['code'] = 401;
					$result['status'] = 'error';
					$result['response'] = "Missing Authorization Bearer.";
					echo json_encode($result);
					die();
				}
				/***************/
					
					if($id == 'all') {
						//get all users
						$users = User::get_everything( " AND deleted = 0 ORDER BY name ASC " );
						$return_arr = array();
						foreach($users as $user) {
							$group = Group::get_specific_id($user->prvlg_group);
							
							$user_arr = array();
							$user_arr['name'] = $user->name;
							$user_arr['email'] = $user->email;
							$user_arr['username'] = $user->username;
							$user_arr['phone'] = $user->mobile;
							$user_arr['address'] = $user->address;
							$user_arr['about'] = $user->about;
							$user_arr['registeration_date'] = $user->registered;
							$user_arr['access_level'] = $group->name;
							$user_arr['avatar'] = $user->get_avatar();
							if($extra != '' && array_key_exists($extra, $user_arr)) {
								$temp = $user_arr[$extra];
								$user_arr = array();
								$user_arr[$extra] = $temp;
							}
							
							$return_arr[] = $user_arr;
						}
						$result['code'] = 200;
						$result['status'] = 'success';
						$result['response'] = $return_arr;
						
					} elseif($id == 'banned') {
						//get all users
						$users = User::get_everything( " AND disabled = 1 AND deleted = 0 ORDER BY name ASC " );
						$return_arr = array();
						foreach($users as $user) {
							$group = Group::get_specific_id($user->prvlg_group);
							
							$user_arr = array();
							$user_arr['name'] = $user->name;
							$user_arr['email'] = $user->email;
							$user_arr['username'] = $user->username;
							$user_arr['phone'] = $user->mobile;
							$user_arr['address'] = $user->address;
							$user_arr['about'] = $user->about;
							$user_arr['registeration_date'] = $user->registered;
							$user_arr['access_level'] = $group->name;
							$user_arr['avatar'] = $user->get_avatar();
							if($extra != '' && array_key_exists($extra, $user_arr)) {
								$temp = $user_arr[$extra];
								$user_arr = array();
								$user_arr[$extra] = $temp;
							}
							
							$return_arr[] = $user_arr;
						}
						$result['code'] = 200;
						$result['status'] = 'success';
						$result['response'] = $return_arr;
						
					} elseif($id == 'pending') {
						//get all users
						$users = User::get_everything( " AND pending = 1 AND deleted = 0 ORDER BY name ASC " );
						$return_arr = array();
						foreach($users as $user) {
							$group = Group::get_specific_id($user->prvlg_group);
							
							$user_arr = array();
							$user_arr['name'] = $user->name;
							$user_arr['email'] = $user->email;
							$user_arr['username'] = $user->username;
							$user_arr['phone'] = $user->mobile;
							$user_arr['address'] = $user->address;
							$user_arr['about'] = $user->about;
							$user_arr['registeration_date'] = $user->registered;
							$user_arr['access_level'] = $group->name;
							$user_arr['avatar'] = $user->get_avatar();
							if($extra != '' && array_key_exists($extra, $user_arr)) {
								$temp = $user_arr[$extra];
								$user_arr = array();
								$user_arr[$extra] = $temp;
							}
							
							$return_arr[] = $user_arr;
						}
						$result['code'] = 200;
						$result['status'] = 'success';
						$result['response'] = $return_arr;
						
					} elseif($id == 'active') {
						//get active users
						$time_check = time() - 300;
						$users = Online::get_everything(" AND time > '{$time_check}' ");
						$return_arr = array();
						foreach($users as $online_user) {
							$user = User::get_specific_id($online_user->user_id);
							$group = Group::get_specific_id($user->prvlg_group);
							
							$user_arr = array();
							$user_arr['name'] = $user->name;
							$user_arr['email'] = $user->email;
							$user_arr['username'] = $user->username;
							$user_arr['phone'] = $user->mobile;
							$user_arr['address'] = $user->address;
							$user_arr['about'] = $user->about;
							$user_arr['registeration_date'] = $user->registered;
							$user_arr['access_level'] = $group->name;
							$user_arr['avatar'] = $user->get_avatar();
							$user_arr['ip'] = $online_user->ip;
							$user_arr['currently_viewing'] = $online_user->current_page;
							$details = json_decode(file_get_contents("https://ipinfo.io/{$online_user->ip}/json"));
							if($details) {
								$countries = json_decode(file_get_contents("http://country.io/names.json"), true);
								if(isset($details->country)) {
									$user_arr['country'] = $countries[$details->country];
									$user_arr['city'] = $details->city;
								}
							}
							if($extra != '' && array_key_exists($extra, $user_arr)) {
								$temp = $user_arr[$extra];
								$user_arr = array();
								$user_arr[$extra] = $temp;
							}
							$return_arr[] = $user_arr;
						}
						$result['code'] = 200;
						$result['status'] = 'success';
						$result['response'] = $return_arr;
						
					} elseif(is_numeric($id)) {
						
						if(!User::check_id_existance($id)) {
							$result['code'] = 404;
							$result['status'] = 'error';
							$result['response'] = "User not found!";
						} else {
						
						$user = User::get_specific_id($id);
						$return_arr = array();
						
							$group = Group::get_specific_id($user->prvlg_group);
							
							$user_arr = array();
							$user_arr['name'] = $user->name;
							$user_arr['email'] = $user->email;
							$user_arr['username'] = $user->username;
							$user_arr['phone'] = $user->mobile;
							$user_arr['address'] = $user->address;
							$user_arr['about'] = $user->about;
							$user_arr['registeration_date'] = $user->registered;
							$user_arr['access_level'] = $group->name;
							$user_arr['avatar'] = $user->get_avatar();
							if($extra != '' && array_key_exists($extra, $user_arr)) {
								$temp = $user_arr[$extra];
								$user_arr = array();
								$user_arr[$extra] = $temp;
							}
							$return_arr[] = $user_arr;
						
						$result['code'] = 200;
						$result['status'] = 'success';
						$result['response'] = $return_arr;
						
						}
					} else {
						$result['code'] = 400;
						$result['status'] = 'error';
						$result['response'] = 'Please specify valid User ID';
					}
				} elseif($action == 'privilege') {
					
				/* Check JWT Validity */
				$jwt = getBearerToken();
				if($jwt) {
					try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
					if(is_array($info)) {
						if($info['type'] == 'success') {
							$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
							if($sent_api_key != $siteGuard_settings['api_key']) {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Invalid Public API Key";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = $info['api_key'];
							echo json_encode($result);
							die();
						}
					} else {
						$result['code'] = 401;
						$result['status'] = 'error';
						$result['response'] = "Invalid Authorization Bearer.";
						echo json_encode($result);
						die();
					}
				} else {
					$result['code'] = 401;
					$result['status'] = 'error';
					$result['response'] = "Missing Authorization Bearer.";
					echo json_encode($result);
					die();
				}
				/***************/
					
					
						if($id != "" && is_numeric($id)) {
							
							if(!User::check_id_existance($id)) {
								$result['code'] = 404;
								$result['status'] = 'error';
								$result['response'] = "User not found!";
							} else {
								if($extra == '') {
									$result['code'] = 400;
									$result['status'] = 'error';
									$result['response'] = 'Please specify valid Privilege';
								} else {
									$user = User::get_specific_id($id);
									
									$result['code'] = 200;
									$result['status'] = 'success';
									if($siteGuard->group_privilege($extra , $user->prvlg_group)) {
										$result['response'] = 'true';
									} else {
										$result['response'] = 'false';
									}
								}
							}
						} else {
							$result['code'] = 400;
							$result['status'] = 'error';
							$result['response'] = 'Please specify valid User ID';
						}
				} elseif($action == 'page') {
					
					/* Check JWT Validity */
				$jwt = getBearerToken();
				if($jwt) {
					try { $info = json_decode($siteGuard->decode_jwt($jwt),true); } catch(Exception $e) { $result['code'] = 403; $result['status'] = 'error'; $result['response'] = $e->getMessage(); echo json_encode($result); die(); }
					if(is_array($info)) {
						if($info['type'] == 'success') {
							$sent_api_key = mjdecode($info['api_key'], $siteGuard_settings['api_salt']);
							if($sent_api_key != $siteGuard_settings['api_key']) {
								$result['code'] = 403;
								$result['status'] = 'error';
								$result['response'] = "Invalid Public API Key";
								echo json_encode($result);
								die();
							}
						} else {
							$result['code'] = 401;
							$result['status'] = 'error';
							$result['response'] = $info['api_key'];
							echo json_encode($result);
							die();
						}
					} else {
						$result['code'] = 401;
						$result['status'] = 'error';
						$result['response'] = "Invalid Authorization Bearer.";
						echo json_encode($result);
						die();
					}
				} else {
					$result['code'] = 401;
					$result['status'] = 'error';
					$result['response'] = "Missing Authorization Bearer.";
					echo json_encode($result);
					die();
				}
				/***************/
					
						if($id != "" && is_numeric($id)) {
							
							if(!User::check_id_existance($id)) {
								$result['code'] = 404;
								$result['status'] = 'error';
								$result['response'] = "User not found!";
							} else {
								if($extra == '') {
									$result['code'] = 400;
									$result['status'] = 'error';
									$result['response'] = 'Please specify valid Page Name';
								} else {
									$user = User::get_specific_id($id);
									
									$result['code'] = 200;
									$result['status'] = 'success';
									if($siteGuard->group_privilege($extra.'.read' , $user->prvlg_group)) {
										$result['response'] = 'true';
									} else {
										$result['response'] = 'false';
									}
								}
							}
						} else {
							$result['code'] = 400;
							$result['status'] = 'error';
							$result['response'] = 'Please specify valid User ID';
						}
				} else {
					$result['code'] = 400;
					$result['status'] = 'error';
					$result['response'] = 'Please specify valid function for (User) Model';
				}
			break;
			
			
			default:
				$result['code'] = 400;
				$result['status'] = 'error';
				$result['response'] = 'Invalid API Request';
			break;
		}
	
	

} elseif(!isset($siteGuard_settings['api']) || isset($siteGuard_settings['api']) && $siteGuard_settings['api'] == 'off') {
	$result['code'] = 403;
	$result['status'] = 'error';
	$result['response'] = 'API Server is disabled';
}

echo json_encode($result);
