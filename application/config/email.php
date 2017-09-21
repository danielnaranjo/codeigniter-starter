<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
https://www.mailjet.com/docs/code/php/codeigniter
https://gist.github.com/waifung0207/10b7bcc4646eef8877e5
*/

$config['email']['protocol']     = 'smtp';
$config['email']['smtp_host']    = 'smtp.domain';
$config['email']['smtp_port']    = 'port';
$config['email']['smtp_timeout'] = '30';
$config['email']['smtp_user']    = 'user@domain';
$config['email']['smtp_pass']    = 'password';
$config['email']['smtp_crypto']  = '';
$config['email']['charset']      = 'utf-8';
$config['email']['mailtype']     = 'html';
$config['email']['wordwrap']     = TRUE;
$config['email']['newline']      = "\r\n";
