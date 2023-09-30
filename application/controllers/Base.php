<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base extends CI_Controller {
    
    const NICE_PG_TYPE = 'NCPG',
          KSNET_PG_TYPE = 'KSPG';

    const PG_NAME = [
        self::NICE_PG_TYPE => 'NC',
        self::KSNET_PG_TYPE => 'KS'
    ];

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function index()
    {
        $data['list'] = array(
            'title' => 'My Title',
            'heading' => 'My Heading',
            'message' => 'My Message',
            'pg_name' => self::PG_NAME
        );
        $this->load->view('main', $data);
    }

    public function comments()
    {
        echo 'Look at this!';
    }
}
