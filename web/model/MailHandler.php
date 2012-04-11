<?php

class MailHandler
{
    /**
     * sending email with snippet
     */
    public function sendMail($to, $subject,$message)
    {
//         if(mail($to, $subject,$message)){
//	       return true;
//        } 
//        else{ 
//        	return false;
//        }
        mail($to, $subject,$message);
    }
}