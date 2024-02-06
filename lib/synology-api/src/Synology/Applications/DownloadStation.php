<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class DownloadStation
 *
 * @package Synology\Applications
 */
class DownloadStation extends Authenticate
{
    const API_SERVICE_NAME = 'DownloadStation';
    const API_NAMESPACE = 'SYNO';

    /**
     * Info API setup
     *
     * @param string $address
     * @param int    $port
     * @param string $protocol
     * @param int    $version
     * @param bool   $verifySSL
     */
    public function __construct($address, $port = null, $protocol = null, $version = 1, $verifySSL = false)
    {
        parent::__construct(self::API_SERVICE_NAME, self::API_NAMESPACE, $address, $port, $protocol, $version, $verifySSL);
    }

    /**
     * Return Information about DownloadStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'DownloadStation/info.cgi', 'getinfo');
    }

    /**
     * Get Limitation settings
     *
     * @return \stdClass
     */
    public function getConfig()
    {
        return $this->_request('Info', 'DownloadStation/info.cgi', 'getconfig');
    }

    /**
     * Set Limitation settings
     *
     * @param $params
     *
     * @return bool
     *
     * @throws Exception
     */
    public function setConfig($params)
    {
        return $this->_request('Info', 'DownloadStation/info.cgi', 'setserverconfig', $params, null, 'post');
    }

    /**
     * Get Schedule settings
     *
     * @return \stdClass
     */
    public function getScheduleConfig()
    {
        return $this->_request('Schedule', 'DownloadStation/schedule.cgi', 'getconfig');
    }

    /**
     * Set Limitation settings
     *
     * @param $params
     *
     * @return bool
     *
     * @throws Exception
     */
    public function setScheduleConfig($params)
    {
        return $this->_request('Schedule', 'DownloadStation/schedule.cgi', 'setserverconfig', $params, null, 'post');
    }

    /**
     * Get a list of Task
     *
     * @param int   $offset
     * @param int   $limit
     * @param array $additional (detail,transfer,file,tracker,peer)
     *
     * @return boolean
     */
    public function getTaskList($offset = 0, $limit = -1, $additional = null)
    {
        $params = [];
        if (isset($offset)) {
            $params['offset'] = $offset;
        }

        if (isset($limit) && $limit > 0) {
            $params['limit'] = $limit;
        }

        if (isset($additional) && is_array($additional)) {
            $params['additional'] = implode(',', $additional);
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'list', $params);
    }

    /**
     * Get more info about a task
     *
     * @param string|array $taskId     (one ID or a list of ID)
     * @param array        $additional (detail,transfer,file,tracker,peer)
     *
     * @return boolean
     */
    public function getTaskInfo($taskId, $additional = null)
    {
        $params = [];

        if (is_array($taskId)) {
            $params['id'] = implode(',', $taskId);
        } else {
            $params['id'] = $taskId;
        }

        if (isset($additional) && is_array($additional)) {
            $params['additional'] = implode(',', $additional);
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'getinfo', $params);
    }

    /**
     * Add a new Task
     *
     * @param string $uri
     * @param string $file
     * @param string $login
     * @param string $password
     * @param string $zipPassword
     * @param string $destination
     *
     * @return \stdClass
     */
    public function addTask($uri, $file = null, $login = null, $password = null, $zipPassword = null, $destination = null)
    {
        $params = ['uri' => $uri];
        if (!empty($login)) {
            $params['login'] = $login;
        }

        if (!empty($password)) {
            $params['login'] = $password;
        }

        if (!empty($zipPassword)) {
            $params['login'] = $zipPassword;
        }

        if (!empty($destination)) {
            $params['destination'] = $destination;
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'create', $params, null, 'post');
    }

    /**
     * Delete a task
     *
     * @param string|array $taskId
     * @param bool         $forceComplete
     *
     * @return \stdClass
     */
    public function deleteTask($taskId, $forceComplete = false)
    {
        $params = [];
        if (is_array($taskId)) {
            $params['id'] = implode(',', $taskId);
        } else {
            $params['id'] = $taskId;
        }

        if ($forceComplete === true) {
            $params['force_complete'] = 'true';
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'delete', $params, null, 'post');
    }

    /**
     * Pause a task
     *
     * @param string|array $taskId
     *
     * @return \stdClass
     */
    public function pauseTask($taskId)
    {
        $params = [];
        if (is_array($taskId)) {
            $params['id'] = implode(',', $taskId);
        } else {
            $params['id'] = $taskId;
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'pause', $params, null, 'post');
    }

    /**
     * Resume a task
     *
     * @param string|array $taskId
     *
     * @return \stdClass
     */
    public function resumeTask($taskId)
    {
        $params = [];
        if (is_array($taskId)) {
            $params['id'] = implode(',', $taskId);
        } else {
            $params['id'] = $taskId;
        }

        return $this->_request('Task', 'DownloadStation/task.cgi', 'resume', $params, null, 'post');
    }

    /**
     * Get Statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->_request('Statistic', 'DownloadStation/task.cgi', 'getinfo');
    }

    /**
     * Get a list of RSS "feeds"
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \stdClass
     */
    public function getRssList($offset = 0, $limit = -1)
    {
        $params = [];
        if (isset($offset)) {
            $params['offset'] = $offset;
        }

        if (isset($limit) && $limit > 0) {
            $params['limit'] = $limit;
        }

        return $this->_request('RSS.Site', 'DownloadStation/RSSsite.cgi', 'list', $params);
    }

    /**
     * Refresh all RSS
     *
     * @param string|array $rssId
     *
     * @return \stdClass
     */
    public function refreshRss($rssId = 'ALL')
    {
        $params = [];
        if (is_array($rssId)) {
            $params['id'] = implode(',', $rssId);
        } else {
            $params['id'] = $rssId;
        }

        return $this->_request('RSS.Site', 'DownloadStation/RSSsite.cgi', 'list', []);
    }

    /**
     * Refresh all RSS
     *
     * @param string|array $rssId
     * @param int          $offset
     * @param int          $limit
     *
     * @return \stdClass
     *
     * @throws Exception
     */
    public function getRssFeedList($rssId = 'ALL', $offset = 0, $limit = -1)
    {
        $params = [];
        if (is_array($rssId)) {
            $params['id'] = implode(',', $rssId);
        } else {
            $params['id'] = $rssId;
        }

        if (isset($offset)) {
            $params['offset'] = $offset;
        }

        if (isset($limit) && $limit > 0) {
            $params['limit'] = $limit;
        }

        return $this->_request('RSS.Feed', 'DownloadStation/RSSfeed.cgi', 'list', $params);
    }

    /**
     * Starts a new search task
     *
     * @param  string $keyword The search keyword
     * @param  string $module  Module name concatenated by ',' or use 'all' or 'enabled'
     *
     * @return \stdClass
     */
    public function startSearchTask($keyword, $module = 'enabled')
    {
        $params = [
            'keyword' => $keyword,
            'module'  => $module
        ];

        return $this->_request('BTSearch', 'DownloadStation/btsearch.cgi', 'start', $params);
    }

    /**
     * Get list of the given search task
     *
     * @param  string  $taskId
     * @param  integer $offset         Beginning task on the requested record
     * @param  integer $limit          Number of records requested
     * @param  string  $sortBy         Possible value is title, size, date, peers, providers, seeds or leech
     * @param  string  $sortDirection  Possible value is desc or asc
     * @param  string  $filterCategory Filter the records by the category using Category ID returned by getCategory function
     * @param  string  $filterTitle    Filter the records by the title using this parameter
     *
     * @return \stdClass
     */
    public function getSearchList($taskId, $offset = 0, $limit = -1, $sortBy = 'title', $sortDirection = 'desc', $filterCategory = '', $filterTitle = '')
    {
        $params = [
            'taskid'          => $taskId,
            'offset'          => $offset,
            'limit'           => $limit,
            'sort_by'         => $sortBy,
            'sort_direction'  => $sortDirection,
            'filter_category' => $filterCategory,
            'filter_title'    => $filterTitle
        ];

        return $this->_request('BTSearch', 'DownloadStation/btsearch.cgi', 'list', $params);
    }

    /**
     * Gets available categories from BTSearch
     *
     * @return \stdClass
     */
    public function getCategory()
    {
        return $this->_request('BTSearch', 'DownloadStation/btsearch.cgi', 'getCategory');
    }

    /**
     * Stops and destroys a search task
     *
     * @param string $taskId
     *
     * @return \stdClass
     */
    public function cleanSearch($taskId)
    {
        return $this->_request('BTSearch', 'DownloadStation/btsearch.cgi', 'clean', ['taskid' => $taskId]);
    }

    /**
     * Gets the installed modules (torrent providers) from BTSearch
     *
     * @return \stdClass
     */
    public function getModule()
    {
        return $this->_request('BTSearch', 'DownloadStation/btsearch.cgi', 'getModule');
    }
}
