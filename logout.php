<?php
   session_start();
   
   if(session_destroy()) {			session_regenerate_id(true); 
		header("location: https://myhomeworkrewards.com");
   }
?>