<?php

class IndeedImporter
{
    private $_publisher_id;
    private $_attribute = '<span id=indeed_at><a href="http://jp.indeed.com/">求人検索</a> by <a href="http://jp.indeed.com/" title="Job Search"><img src="http://www.indeed.com/p/jobsearch.gif" style="border: 0; vertical-align: middle;" alt="Indeed job search"></a></span>';
    private $_tracking = '<script type="text/javascript" src="http://gdc.indeed.com/ads/apiresults.js"></script>';

    private $_limit = 20;

    private $_api_search_url = 'http://api.indeed.com/ads/apisearch?';

    public function __construct($publisher_id)
    {
        if (!$publisher_id) {
            die();
        }
        $this->_publisher_id = $publisher_id;
    }

    private $_last_query = null;

    public function getSearch($data_field = null)
    {
        $page = (isset($data_field['page']) && $data_field['page']) ? $data_field['page'] : 1;
        $start = ($page > 1) ? $page * $this->getLimit() : 0;

        $queryArray = array(
          'publisher' => $this->_publisher_id,
          'v' => '2',
          'format' => ($data_field['format']) ? $data_field['format'] : 'json',
          'q' => ($data_field['q']) ? $data_field['q'] : '',
          'l' => ($data_field['l']) ? $data_field['l'] : '',
          'sort' => ($data_field['sort']) ? $data_field['sort'] : '',
          'radius' => ($data_field['radius']) ? $data_field['radius'] : '25',
          'st' => ($data_field['st']) ? $data_field['st'] : '',
          'jt' => ($data_field['jt']) ? $data_field['jt'] : '',
          'start' => ($data_field['start'] && $start == 0) ? $data_field['start'] : $start,
          'limit' => ($data_field['limit']) ? $data_field['limit'] : $this->getLimit(),
          'fromage' => ($data_field['fromage']) ? $data_field['fromage'] : '',
          'highlight' => ($data_field['highlight']) ? $data_field['highlight'] : '0',
          'filter' => ($data_field['filter']) ? $data_field['filter'] : '1',
          'latlong' => ($data_field['latlong']) ? $data_field['latlong'] : '1',
          'co' => ($data_field['co']) ? $data_field['co'] : 'jp',
          'chnl' => ($data_field['chnl']) ? $data_field['chnl'] : '',
          'userip' => $_SERVER['REMOTE_ADDR'],
          'useragent' => $_SERVER['HTTP_USER_AGENT'],
        );
        $this->_last_query = $this->_api_search_url.http_build_query($queryArray);
        //$data = @file_get_contents($this->_api_search_url.http_build_query($queryArray));

        $data = curl_init($this->_api_search_url.http_build_query($queryArray));
        curl_setopt($data, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($data);

        $data = json_decode($data, true);

        if (isset($data['error'])) {
            return 'Indeed Jobs Shortcode encountered error. Please check your Publisher ID.';
        }

        return $data;
    }

    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    public function getLimit()
    {
        return $this->_limit;
    }

    public function getLastQuery()
    {
        return $this->_last_query;
    }

    public function getAttribute()
    {
        return $this->_attribute;
    }
    public function getTracking()
    {
        return $this->_tracking;
    }
}
