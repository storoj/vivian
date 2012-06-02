<?php

class Mailer {

    public $mail_sent = 0;
    public $email_pull = array();

    private $allowed_types = array('text/plain', 'text/html');
    private $from = 'mailer@topcreator.org';

    public function sendMail($subj, $text, $to = array(), $from = NULL,
                               $reply = NULL, $content_type = NULL) {

        if (is_string($to) && checkEmail($to)) {
            $to = array($to);
        } elseif (!is_array($to) || empty($to)) {
            return false;
        }

        if (is_null($from) || !$this->checkEmail($from)) {
            $from = $this->from;
        }
        // set headers
        if ($headers = $this->generateHeaders($from, $reply, $content_type)) {
            // generate base64 subj
            $subj = $this->generateSubject($subj);

            foreach($to as $el) {
                if ($this->checkEmail($el)) {
                    // send mails
                    if (mail($el, $subj, $text, $headers)) {
                        // save history
                        $this->mail_sent++;
                        $this->email_pull[] = $el;
                    }
                }
            }

            // return num of sent emails
            return $this->mail_sent;
        }

        return false;
    }

    private function generateHeaders($from, $reply = NULL, $type = 'text/html') {
        if (!$this->checkEmail($from))
            return false;

        // set reply as from if not given
        if (is_null($reply) || !$this->checkEmail($reply)) {
            $reply = $from;
        }

        // check content type given
        if (!in_array($type, $this->allowed_types)) {
            $type = 'text/html';
        }

        return $headers = 'From: '.$from."\r\n".
                'Reply-To: '.$reply."\r\n".
                'Content-Type: '.$type.'; charset="utf-8"'."\r\n" .
                'X-Mailer: PHP/'.phpversion();
    }

    private function generateSubject($subj) {
        if ($subj) {
            $subj =  "=?UTF-8?B?".base64_encode($subj)."==?=";
        }
        return $subj;
    }

    private function checkEmail($email) {
        return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', trim($email));
    }
}
