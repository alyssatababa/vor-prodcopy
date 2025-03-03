<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('change_tag_email'))
{
    function change_tag_email($message = "", $names)
    {
    	//format name to array('name' => 'name1', 'type' => 'type1') 

    	/*list of types
   		-sender
   		-approver
   		-vendor
   		-remark
      -receiver
		*/

    	$approver_pattern = array(
    		'[approvername]',
    		'[approver]',
    		'[approver_name]',
    		'[vrdstaff]',
    		'[vrdstaff_name]'
    	);
    	$sender_pattern = array(
    		'[sender]',
    		'[sendername]',
    		'[sender_name]'
    	);
    	$vendor_pattern = array(
    		'[vendorname]',
    		'[vendor]',
    		'[vendor_name]'
    	);

    	$remark_pattern = array(
    		'[remarks]',
    		'[reject_reason]',
    		'[remark]'
    	);

      $receiver_pattern = array(
      '[receiver]',
      '[buyername]',
      'buyer_name'
      );

    	$new_message = new stdClass;


    	if(isset($message->CONTENT) || isset($message->HEADER) || isset($message->SUBJECT) || isset($message->TOPIC) || isset($message->MESSAGE)){

    	foreach ($message as $assoc => $msg) {

    		$lmessage = $msg;
  		foreach ($names as $key => $value) {

  			switch ($value['type']) {
  				case 'sender'   :
  							$tmessage = str_replace($sender_pattern,$value['name'],$lmessage); 				
  					break;
  				case 'approver'   :
  							$tmessage = str_replace($approver_pattern,$value['name'],$lmessage); 
  					break;
  				case 'vendor'   :
  							$tmessage = str_replace($vendor_pattern,$value['name'],$lmessage); 					
  					break;
  				case 'remark'   :
  							$tmessage = str_replace($remark_pattern,$value['name'],$lmessage); 					
  					break;
          case 'receiver' :
                $tmessage = str_replace($receiver_pattern,$value['name'],$lmessage);          
            break;
  				default:
  					break;
  			}

  			$lmessage = $tmessage;


  		}

      if($assoc == "CONTENT"){
          $new_message->CONTENT = $tmessage;
      }elseif ($assoc == "TEMPLATE_HEADER") {
          $new_message->HEADER =  $tmessage;
      }elseif ($assoc == "SUBJECT") {
          $new_message->SUBJECT =  $tmessage;
      }elseif ($assoc == "TOPIC") {
          $new_message->TOPIC =  $tmessage;
      }elseif ($assoc == "MESSAGE") {
          $new_message->MESSAGE =  $tmessage;
      }
/*  		if($assoc == "CONTENT"){
  			$new_message->CONTENT = nl2br($tmessage);
  		}else{
  			$new_message->HEADER =  $tmessage;
  		}*/
  	}
  		return $new_message;
		}else{
    	foreach ($message as $assoc => $msg) {
    		$lmessage = $msg;
  		foreach ($names as $key => $value) {

  			switch ($value['type']) {
  				case 'sender'   :
  							$tmessage = str_replace($sender_pattern,$value['name'],$lmessage); 				
  					break;
  				case 'approver'   :
  							$tmessage = str_replace($approver_pattern,$value['name'],$lmessage); 
  					break;
  				case 'vendor'   :
  							$tmessage = str_replace($vendor_pattern,$value['name'],$lmessage); 					
  					break;
  				case 'remark'   :
  							$tmessage = str_replace($remark_pattern,$value['name'],$lmessage); 					
  					break;
  				default:
  					break;
  			}

  			$lmessage = $tmessage;
  		}
      if($assoc == "CONTENT"){
          $new_message->CONTENT = nl2br($tmessage);
      }elseif ($assoc == "TEMPLATE_HEADER") {
          $new_message->HEADER =  $tmessage;
      }elseif ($assoc == "SUBJECT") {
          $new_message->SUBJECT =  $tmessage;
      }elseif ($assoc == "TOPIC") {
          $new_message->TOPIC =  $tmessage;
      }elseif ($assoc == "MESSAGE") {
          $new_message->MESSAGE =  $tmessage;
      }
  	}
  		return $new_message;

  	}
  }

  function email_from(){

	// Modified MSF - 20191108 (IJR-10617)
    //$data['from'] = 'no-reply@smvendorportal.com';
    $data['from'] = 'smvendoronlineregistration@smretail.com';
    $data['bcc'] = '';
	// Added MSF - 20191108 (IJR-10617)
	$data['sender_alias'] = '[PROD_COPY 01] SM Vendor Online Registration';
   // $data['bcc'] = 'justine.jovero@novawaresystems.com,justine.pagarao@novawaresystems.com,marc.anthony.pacres@novawaresystems.com';
    return $data;

  }

}
  

