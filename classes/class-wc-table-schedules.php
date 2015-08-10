<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WC_Table_Schedules extends WP_List_Table {

    var $current_destination = '';

    public function __construct() {

        parent::__construct(array(
            'singular' => __('item', 'woocommerce-pickingpal'),
            'plural' => __('items', 'woocommerce-pickingpal'),
            'ajax' => true
        ));
    }

    /**
     * Output the report
     */
    public function output() {
        $this->prepare_items();
        ?>

        <div class="wp-wrap">
            <?php
            $this->display();
            ?>
        </div>
        <?php
    }

    public function display_tablenav($which) {
        if ('top' != $which)
            return;
        ?>
        <div>
            <input type="button" class="button-secondary" value="Add Schedule" id="add_schedule">
        </div>
        <?php
    }

    public function prepare_items() {


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();

        $this->_column_headers = array($columns, $hidden, $sortable);

//		$this->items = array(
//			0 => array( 'recurrence' => 2 ),
//		);
        $this->items = get_option('woocommerce-order-export-cron', array());

        foreach ($this->items as $index => $item) {
            $this->items[$index]['id'] = $index;
        }
//		var_dump( $this->items );
    }

    public function get_columns() {
        $columns = array();
        $columns['recurrence'] = 'Recurrence';
        $columns['destination'] = 'Destination';
        $columns['destination_details'] = 'Destination Details';
        $columns['next_event'] = 'Next event';
        $columns['actions'] = 'Actions';
        return $columns;
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'recurrence':
                $r = '';
                if (isset($item['schedule'])) {
                    if ($item['schedule']['type'] == 'schedule-1') {
                        $r = 'Run ';
                        if (isset($item['schedule']['weekday'])) {
                            $days = array_keys($item['schedule']['weekday']);
                            $r .= " on " . implode(', ', $days);
                        }
                        if (isset($item['schedule']['run_at'])) {
                            $r .= '  at ' . $item['schedule']['run_at'];
                        }
                    } else {
                        if ($item['schedule']['interval'] == 'custom') {
                            $r = "to run every {$item['schedule']['custom_interval']} minute(s)";
                        } else {
                            foreach (wp_get_schedules() as $name => $schedule) {
                                if ($item['schedule']['interval'] == $name)
                                    $r = $schedule['display'];
                            }
                        }
                    }
                }
                return $r;
            case 'destination':
                $this->current_destination = $item['destination']['type'];
                $al = array('ftp' => 'Ftp', 'http' => 'Http post', 'email' => 'Email');
                if (isset($item['destination']['type'])) {
                    return $al[$item['destination']['type']];
                }
                return '';
            case 'destination_details':
                if ($this->current_destination == 'http')
                    return esc_html($item['destination']['http_post_url']);
                if ($this->current_destination == 'email')
                    return __('Subject: ', 'woocommerce-order-export') . esc_html($item['destination']['email_subject']) . "<br>" . __('To: ', 'woocommerce-order-export') . esc_html($item['destination']['email_recipients']);
                if ($this->current_destination == 'ftp')
                    return esc_html($item['destination']['ftp_user']) . "@" . esc_html($item['destination']['ftp_server']) . $item['destination']['ftp_path'];

                //print_r($item);
                return '';
            case 'next_event':
                if ($item['schedule']['type'] == 'schedule-1')
                    return WC_Order_Export_Admin::next_event_for_schedule_weekday(array_keys($item['schedule']['weekday']), $item['schedule']['run_at']);
                else {
                    $timestamp = wp_next_scheduled('wc_export_cron_job', array('job_id' => intval($item['id'])));
                    if ($timestamp && $timestamp > 0)
                        return date("D M j Y G:i:s", $timestamp);
                    else
                        return "At next page refresh";
                }
            case 'actions':
                return '<div class="btn-edit button-secondary" data-id="' . $item['id'] . '"><span class="dashicons dashicons-edit"></span></div>' .
                        '<div class="btn-trash button-secondary" data-id="' . $item['id'] . '"><span class="dashicons dashicons-trash"></span></div>';
                break;
            default:

                return isset($item[$column_name]) ? $item[$column_name] : '';
        }
    }

}
