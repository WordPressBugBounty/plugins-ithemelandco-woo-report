<?php

class ITWR_Activation_Controller
{
    public function index()
    {
        $is_active = ITWR_Activation_Data::is_active();
        $activation_skipped = ITWR_Activation_Data::skipped();
        $flush_message_repository = new ITWR_Flush_Message();
        $flush_message = $flush_message_repository->get();

        include_once ITWR_VIEWS_DIR . "activation/main.php";
    }
}
