<?php
//
if(Director::isLive()){
  define('EMAIL', 'info@url.com');
} else {
  define('EMAIL', 'dev@url.com');  
}
Email::setAdminEmail(EMAIL);
