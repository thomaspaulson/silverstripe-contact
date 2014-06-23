<?php
//
if(Director::isLive()){
  define('EMAIL', 'info@athira.com');
} else {
  define('EMAIL', 'dev@athira.com');  
}
Email::setAdminEmail(EMAIL);
