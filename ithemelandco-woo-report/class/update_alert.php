<?php

class ITWR_Update_Alert
{
    private static $instance;

    private $license_service;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'upload-plugin') {
            return false;
        }

        $this->license_service = ITWR_License_Service::get_instance();
        $update_data_repository = ITWR_Singleton_Update_Data::get_instance();
        $update_data = $update_data_repository->get();
        if (!empty($update_data) && isset($update_data['key'])) {
            if (ITWR_Update_Helper::has_available_update(ITWR_VERSION, $update_data)) {
                $filter_name = ($this->license_check()) ? "itwr_update_alert_items" : "itwr_license_alert_items";
                add_filter($filter_name, function ($items) use ($update_data) {
                    $items[] = $update_data;
                    return $items;
                });
            }

            add_action('admin_notices', [$this, 'init_update_alert']);
            add_action('admin_notices', [$this, 'init_license_alert']);
        }
    }

    private function license_check()
    {
        $license_is_valid = $this->license_service->license_is_valid();
        if ($license_is_valid) {
            $license_repository = new ITWR_License_Repository();
            $license_repository->matched_license();
        }

        return $license_is_valid;
    }

    public function init_update_alert()
    {
        if (!has_filter('itwr_update_alert_items')) {
            return false;
        }

        $items = apply_filters('itwr_update_alert_items', []);
        if (!empty($items)) {
            $plugins = "<ul>";
            foreach ($items as $item) {
                $plugins .= '<li><strong>' . sanitize_text_field($item['label']) . '</strong></li>';
            }
            $plugins .= "</ul>";
        }

        $update_link = admin_url() . "update-core.php";
        $output = '<style>
            .italic {
                font-style: italic;
            }
            .itwr-required-alert ul {
                padding-left:20px;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
            }
            .itwr-required-alert ul li{
                list-style: disc;
            }
            .itwr-required-alert{
                width:100%;
                background:#fff;
                display:inline-table;
                margin-top:10px;
                padding:20px;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                text-align:left;
            }
            .itwr-required-alert-icon{
                width:12%;
                float:left;
                border-right: 2px #dbdbdb solid;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                text-align:center;
                padding:15px 0;
            }
            .itwr-required-alert .itwr-required-alert-right{
                width:85%;
                float:left;
                margin-left:20px;
            }
            .itwr-required-alert .itwr-required-alert-text{
                width:100%;
                display:inline-table;
                margin: 0 0 20px 0;
                font-size:13px;
                line-height:23px;
                float:left;
            }
            .itwr-required-alert .itwr-required-alert-text .title{
                font-size:17px;
                line-height:30px;
            }
            .itwr-required-install,
            .itwr-required-read-more{
                height:42px;
                padding:0 30px;
                text-decoration:none;
                border-radius:4px;
                -moz-border-radius:4px;
                -webkit-border-radius:4px;
                display:inline-table;
                font-size:12pt;
                line-height:42px;
                cursor:pointer;
            }

            .itwr-required-install{
                border:0;
                background:#11db6d;
                color:#fff;
            }

            .itwr-required-install:hover,
            .itwr-required-install:focus{
                color:#fff;
            }
            
            .itwr-required-read-more{
                background:#f3f2f0;
                border:1px #e4e3e1 solid;
                color:#3e3e3e;
            }

            .itwr-required-read-more:hover,
            .itwr-required-read-more:focus{
                color:#3e3e3e;
            }

            .itwr-ml5{
                margin-left:5px;
            }
        </style>';
        $output .= '<div class="wrap"><div class="itwr-required-alert">';
        $output .= '<div class="itwr-required-alert-icon">';
        $output .= '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="110px" height="110px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
        <g>
            <g>
                <path style="fill:#000" d="M451.952,274.66v-63.586h14.996c8.282,0,14.996-6.714,14.996-14.996v-58.985c0-24.807-20.182-44.989-44.989-44.989
                    h-54.582c24.695-42.111-6.968-94.288-55.136-92.032c-21.459,0.991-40.394,12.851-50.67,31.758l-22.096,40.895L232.36,31.797
                    c-10.257-18.874-29.193-30.734-50.653-31.725c-48.164-2.246-79.84,49.907-55.137,92.032H75.045
                    c-24.807,0-44.989,20.182-44.989,44.989v58.986c0,8.282,6.714,14.996,14.996,14.996h14.996v90.978H15.06
                    c-8.282,0-14.996,6.714-14.996,14.996v179.956c0,8.282,6.714,14.996,14.996,14.996h59.985c7.365,0,13.478-5.314,14.743-12.314
                    c29.39,8.053,61.247,12.314,92.293,12.314h133.905c32.757,0,64.052-15.594,83.783-41.73
                    c0.305-0.373,102.894-125.86,103.183-126.245c5.878-7.835,8.985-17.164,8.985-26.977
                    C511.937,286.313,481.443,264.212,451.952,274.66z M60.049,377.034v104.974H30.056V332.045h29.993V377.034z M436.956,122.097
                    c8.269,0,14.996,6.727,14.996,14.996v43.989H300.989v-58.986C305.724,122.097,432.37,122.097,436.956,122.097z M421.96,211.075
                    v87.855l-62.842,65.336c-5.518-18.605-22.76-32.221-43.132-32.221h-14.996v-120.97H421.96z M302.937,46.118
                    c5.202-9.571,14.803-15.584,25.684-16.087c23.681-1.096,40.124,24.038,28.576,45.672c-5.482,10.269-15.632,16.4-27.152,16.4
                    c-6.284,0-46.1,0-51.953,0L302.937,46.118z M241.004,122.097c11.164,0,19.117,0,29.993,0v209.948h-24.289
                    c-1.919,0-3.825-0.144-5.704-0.415V122.097z M178.855,29.998c0.488,0,0.978,0.011,1.469,0.034
                    c10.881,0.503,20.483,6.516,25.667,16.055l24.863,46.018c-23.297,0-37.358,0-51.953,0c-11.521-0.001-21.671-6.131-27.152-16.401
                    C140.443,54.526,155.946,29.998,178.855,29.998z M60.049,181.082v-43.989c0-8.269,6.727-14.996,14.996-14.996
                    c1.109,0,134.185,0,135.967,0v58.986H60.049z M211.011,211.075v103.732c-17.578-12.22-38.647-18.867-60.485-18.867
                    s-42.908,6.647-60.485,18.867V211.075H211.011z M479.114,325.819L376.358,451.523c-0.132,0.162-0.261,0.326-0.387,0.494
                    c-14.086,18.78-36.51,29.993-59.985,29.993H182.082c-33.957,0-61.972-4.934-92.04-13.386v-91.588v-23.213l10.45-9.197
                    c28.524-25.102,71.544-25.102,100.069,0c12.821,11.282,29.346,17.413,46.147,17.413h69.278c8.269,0,14.996,6.727,14.996,14.996
                    s-6.727,14.996-14.996,14.996h-106.58c-8.282,0-14.996,6.714-14.996,14.996c0,8.282,6.714,14.996,14.996,14.996h116.631
                    c12.395,0,24.352-5.184,32.827-14.226c3.772-3.922,93.739-97.462,96.942-100.792c0.027-0.028,0.049-0.059,0.076-0.087
                    c0.029-0.03,0.061-0.056,0.089-0.086c9.202-9.88,25.974-3.422,25.974,10.217C481.945,320.233,480.967,323.26,479.114,325.819z"/>
            </g>
        </g>
        </svg>
        ';
        $output .= '</div>';
        $output .= '<div class="itwr-required-alert-right">';
        $output .= '<div class="itwr-required-alert-text"> <strong class="title">The new update is available for below plugin(s), please update for the best performance.</strong> <br> ' . $plugins;
        $output .= '</div><div class="itwr-required-alert-buttons">';
        $output .= '<a href="' . esc_url($update_link) . '" class="itwr-required-install">Update</a>';
        $output .= '</div></div></div></div>';
        echo sprintf('%s', esc_attr($output));
    }

    public function init_license_alert()
    {
        if (!has_filter('itwr_license_alert_items')) {
            return false;
        }

        $items = apply_filters('itwr_license_alert_items', []);
        if (!empty($items)) {
            $plugins = "<ul>";
            foreach ($items as $item) {
                $plugins .= '<li><strong>' . sanitize_text_field($item['label']) . '</strong></li>';
            }
            $plugins .= "</ul>";
        }

        $purchase_url = 'https://ithemelandco.com/shop';
        $output = '<style>
            .italic {
                font-style: italic;
            }
            .itwr-required-alert ul {
                padding-left:20px;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
            }
            .itwr-required-alert ul li{
                list-style: disc;
            }
            .itwr-required-alert{
                width:100%;
                background:#fff;
                display:inline-table;
                margin-top:10px;
                padding:20px;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                text-align:left;
            }
            .itwr-required-alert-icon{
                width:12%;
                float:left;
                border-right: 2px #dbdbdb solid;
                box-sizing:border-box;
                -moz-box-sizing:border-box;
                -webkit-box-sizing:border-box;
                text-align:center;
                padding:15px 0;
            }
            .itwr-required-alert .itwr-required-alert-right{
                width:85%;
                float:left;
                margin-left:20px;
            }
            .itwr-required-alert .itwr-required-alert-text{
                width:100%;
                display:inline-table;
                margin: 0 0 20px 0;
                font-size:13px;
                line-height:23px;
                float:left;
            }
            .itwr-required-alert .itwr-required-alert-text .title{
                font-size:17px;
                line-height:30px;
            }
            .itwr-required-install,
            .itwr-required-read-more{
                height:42px;
                padding:0 30px;
                text-decoration:none;
                border-radius:4px;
                -moz-border-radius:4px;
                -webkit-border-radius:4px;
                display:inline-table;
                font-size:12pt;
                line-height:42px;
                cursor:pointer;
            }

            .itwr-required-install{
                border:0;
                background:#11db6d;
                color:#fff;
            }

            .itwr-required-install:hover,
            .itwr-required-install:focus{
                color:#fff;
            }
            
            .itwr-required-read-more{
                background:#f3f2f0;
                border:1px #e4e3e1 solid;
                color:#3e3e3e;
            }

            .itwr-required-read-more:hover,
            .itwr-required-read-more:focus{
                color:#3e3e3e;
            }

            .itwr-ml5{
                margin-left:5px;
            }
        </style>';
        $output .= '<div class="wrap"><div class="itwr-required-alert">';
        $output .= '<div class="itwr-required-alert-icon">';
        $output .= '<svg height="110px" viewBox="0 0 512 512" width="110px" style="fill:#3e3e3e" xmlns="http://www.w3.org/2000/svg"><path d="m184.296875 512c-4.199219 0-8.277344-1.644531-11.304687-4.691406-3.925782-3.925782-5.527344-9.582032-4.265626-14.976563l23.253907-98.667969c.679687-2.902343 2.152343-5.546874 4.265625-7.636718l203.648437-203.648438c18.109375-18.132812 49.75-18.15625 67.882813 0l30.164062 30.164063c9.066406 9.046875 14.058594 21.121093 14.058594 33.921875 0 12.820312-4.992188 24.894531-14.058594 33.941406l-203.648437 203.625c-2.113281 2.113281-4.757813 3.585938-7.636719 4.265625l-98.667969 23.253906c-1.234375.320313-2.472656.449219-3.691406.449219zm37.78125-106.582031-16.277344 69.078125 69.078125-16.277344 200.429688-200.425781c3.027344-3.03125 4.691406-7.039063 4.691406-11.308594 0-4.265625-1.664062-8.296875-4.691406-11.304687l-30.167969-30.167969c-6.25-6.226563-16.382813-6.25-22.632813 0zm60.910156 67.328125h.210938zm0 0"/><path d="m433.835938 337.898438c-4.097657 0-8.191407-1.558594-11.308594-4.691407l-75.433594-75.4375c-6.25-6.25-6.25-16.382812 0-22.632812s16.382812-6.25 22.632812 0l75.4375 75.433593c6.25 6.25 6.25 16.382813 0 22.636719-3.136718 3.132813-7.234374 4.691407-11.328124 4.691407zm0 0"/><path d="m145.921875 448h-97.921875c-26.476562 0-48-21.523438-48-48v-352c0-26.476562 21.523438-48 48-48h309.332031c26.476563 0 48 21.523438 48 48v98.773438c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-98.773438c0-8.832031-7.167969-16-16-16h-309.332031c-8.832031 0-16 7.167969-16 16v352c0 8.832031 7.167969 16 16 16h97.921875c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m389.332031 138.667969h-373.332031c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h373.332031c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m310.828125 245.332031h-294.828125c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h294.828125c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m202.667969 352h-186.667969c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h186.667969c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m202.667969 352c-8.832031 0-16-7.167969-16-16v-213.332031c0-8.832031 7.167969-16 16-16s16 7.167969 16 16v213.332031c0 8.832031-7.167969 16-16 16zm0 0"/></svg>';
        $output .= '</div>';
        $output .= '<div class="itwr-required-alert-right">';
        $output .= '<div class="itwr-required-alert-text"> <strong class="title">A new update is available for below plugin(s). If you want to get it, you must activate your plugin. <br> Please purchase Pro version and then activate your plugin.</strong> <br>' . $plugins;
        $output .= '</div><div class="itwr-required-alert-buttons">';
        $output .= '<a href="' . esc_url($purchase_url) . '" target="_blank" class="itwr-required-install">Purchase</a>';
        $output .= '</div></div></div></div>';
        echo sprintf('%s', esc_attr($output));
    }
}
