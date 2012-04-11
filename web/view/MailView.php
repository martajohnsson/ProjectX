<?php
class MailView
{
    public function mailView()
    {
        $html = '<div class="mail">
            		<form id="formmail" action="" method ="POST">
            			<label>Your mail :</label>
            			<input type="text" name="mail" id="mail" />
            			<input type="submit" id="sendByMail" name="sendByMail" value="send mail" />
            		</form>
                    <div id="response">
                    </div>
        	</div>';

        return $html;
    }
    
    public function getMail()
    {
        $mail = $_POST['mail'];
        if ($mail == null) {
            return null;
        } else {
            return $mail;
        }
        return false;
    }
    
    public function getSubject()
    {
        $subject = $_POST['subject'];
        if ($subject == null) {
            return null;
        } else {
            return $subject;
        }
        return false;
    }
    
    public function getText()
    {
        $text = $_POST['text'];
        if ($text == null) {
            return null;
        } else {
            return $text;
        }
        return false;
    }
}