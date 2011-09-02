<?php
/*

Copyright (C) 2011 by Wondergraphs LLC

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

namespace WG;

require_once dirname(__FILE__) . '/Exceptions.php';
require_once dirname(__FILE__) . '/Model.php';

/**
 * This class wraps all API operations for the Wondergraphs REST API.
 */
class API {
    private static $HTTPGET = 0;
    private static $HTTPPOST = 1;

    private $version = '$Id$';

    private $organization;
    private $apiKey;
    private $urlRoot = 'https://api.wondergraphs.com/api';
    private $apiVersion = 'v1';

    private $client;

    /**
     * Create a new API instance.
     *
     * @param string $organization The name of the organization
     * @param string $apiKey The API key to use.
     */
    public function __construct($organization, $apiKey) {
        $this->organization = $organization;
        $this->apiKey = $apiKey;

        $this->client = curl_init();
        curl_setopt_array($this->client, array(
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_USERAGENT      => 'Wondergraphs-PHP-API (' . $this->version . ')',
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->organization . ':' . $this->apiKey
        ));
    }

    /**
     * Free curl after use.
     */
    function __destruct() {
        curl_close($this->client);
    }

    /**
     * Set the URL of the API server to use.
     *
     * This is something you will probably never need to do.
     *
     * @param string $root
     */
    public function setUrlRoot($root) {
        $this->urlRoot = $root;
    }

    /* -----------------------------------------------------------
                          API methods below 
       ----------------------------------------------------------- */

    /**
     * Get a list of all users in the current organization.
     *
     * @return array An array of User objects.
     */
    public function getUsers() {
        $users = $this->doGet('users');
        $users = $this->boxList($users, 'WG\User');
        return $users;
    }

    /**
     * Get a specific user.
     *
     * @param string $id The unique ID of the user to retrieve.
     * @return User A User object.
     */
    public function getUser($id) {
        $user = $this->doGet(array('users', $id));
        $user = $this->box($user, 'WG\User');
        return $user;
    }

    /**
     * Retrieves all reports of a user.
     *
     * @param string $id The unique ID of the user whose reports you'd like to retrieve.
     * @return array An array of Report objects.
     */
    public function getReportsForUser($id) {
        $user = $this->doGet(array('users', $id, 'reports'));
        $user = $this->boxList($user, 'WG\Report');
        return $user;
    }

    /**
     * Create a new user.
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param string $password
     * @param string $type Optional user type. Defaults to <code>analyst</code>.
     * @return User The newly created user object.
     */
    public function createUser($email, $firstname, $lastname, $password, $type = 'analyst') {
        $params = array(
            'email'     => $email,
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'password'  => $password,
            'type'      => $type
        );
        $user = $this->doPost('users', $params);
        $user = $this->box($user, 'WG\User');
        return $user;
    }

    /**
     * Retrieves a list of all reports in the organization.
     *
     * @return array An array of Report objects.
     */
    public function getReports() {
        $reports = $this->doGet(array('reports'));
        $reports = $this->boxList($reports, 'WG\Report');
        return $reports;
    }

    /**
     * Get a specific report.
     *
     * @param string $id The unique ID of the report to retrieve.
     * @return Report A Report object.
     */
    public function getReport($id) {
        $report = $this->doGet(array('reports', $id));
        $report = $this->box($report, 'WG\Report');
        return $report;
    }

    /**
     * Creates a new report for a specific user.
     *
     * @param string $name The name of the report.
     * @param string $dataset The ID of the dataset on which this report will be based.
     * @param string $owner The ID of the user that will own this report.
     * @return Report The newly created report is returned.
     */
    public function createReport($name, $dataset, $owner) {
        $params = array(
            'name' => $name,
            'datasetId' => $dataset,
            'owner' => $owner
        );
        $report = $this->doPost('reports', $params);
        $report = $this->box($report, 'WG\Report');
        return $report;
    }

    /**
     * Change the name of a report.
     *
     * Warning: This operation is not available yet.
     */
    public function updateReportName($id, $name) {
        throw new \Exception('Not implemented yet!');
    }

    /**
     * Change the dataset of a report.
     *
     * Warning: This operation is not available yet.
     */
    public function updateReportData($id, $datasetId) {
        throw new \Exception('Not implemented yet!');
    }

    /**
     * Retrieve the update status of a report.
     *
     * A new update is triggered when the dataset behind a report is changed.
     *
     * @param string $id The ID of the report to retrieve the update status from.
     * @return OperationStatus A status object.
     */
    public function getUpdateStatus($id) {
        $status = $this->doGet(array('reports', $id, 'updateStatus'));
        $status = $this->box($status, 'WG\OperationStatus');
        return $status;
    }

    /**
     * Retrieves a list of all datasets in the organization.
     *
     * @return array An array of Dataset objects.
     */
    public function getDatasets() {
        $datasets = $this->doGet(array('datasets'));
        $datasets = $this->boxList($datasets, 'WG\Dataset');
        return $datasets;
    }

    /**
     * Creates a new dataset for a specific user.
     *
     * Although the metadata of the dataset is returned, it is not yet imported.
     * The status of the import can be checken using getImportStatus().
     *
     * Files should be in valid CSV format.
     *
     * @param string $name The name of the dataset.
     * @param string $owner The ID of the users that will own this dataset.
     * @param string $file The filename of the file to be uploaded, or the contents of the file.
     * @param boolean $isFileName When true, the $file argument will be treated as a filename, otherwise it will be treated as the content of the CSV file itself.
     * @return Dataset A newly created dataset object.
     */
    public function createDataset($name, $owner, $filename, $isFileName = true) {
        $file = $isFileName ? '@' . realpath($filename) : $filename;

        if ($file == '@') {
            throw new \Exception('File not found');
        }

        $params = array(
            'name' => $name,
            'owner' => $owner,
            'file' => $file
        );
        $dataset = $this->doPost('datasets', $params);
        $dataset = $this->box($dataset, 'WG\Dataset');
        return $dataset;
    }

    /**
     * Retrieve a specific dataset.
     *
     * @param string $id The dataset ID.
     * @return Dataset The requested dataset.
     */
    public function getDataset($id) {
        $dataset = $this->doGet(array('datasets', $id));
        $dataset = $this->box($dataset, 'WG\Dataset');
        return $dataset;
    }

    /**
     * Retrieve the import status of a specific dataset.
     *
     * @param string $id The ID of the dataset to retrieve the import status for.
     * @return OperationStatus A status object.
     */
    public function getImportStatus($id) {
        $status = $this->doGet(array('datasets', $id, 'importStatus'));
        $status = $this->box($status, 'WG\OperationStatus');
        return $status;
    }

    /* -----------------------------------------------------------
                        Utility methods below 
       ----------------------------------------------------------- */

    /**
     * Execute a GET request
     */
    private function doGet($method) {
        return $this->doRequest(API::$HTTPGET, $method);
    }

    /**
     * Execute a POST request
     */
    private function doPost($method, array $params) {
        return $this->doRequest(API::$HTTPPOST, $method, $params);
    }

    /**
     * Send an API request
     */
    private function doRequest($methodType, $method, array $params = array()) {
        $url = $this->buildUrl($method);

        if ($methodType == API::$HTTPGET) {
            curl_setopt($this->client, CURLOPT_HTTPGET, TRUE);
        } else if ($methodType == API::$HTTPPOST) {
            curl_setopt($this->client, CURLOPT_POST, TRUE);
            curl_setopt($this->client, CURLOPT_POSTFIELDS, $params);
        } else {
            throw new \Exception('Unknown $methodType');
        }
        curl_setopt($this->client, CURLOPT_URL, $url);

        $result = curl_exec($this->client);
        if ($result === false) {
            throw new RequestException('Unexpected request error: ' . curl_error($this->client));
        }
        $response = json_decode($result);

        $info = curl_getinfo($this->client);
        if ($info['http_code'] != 200) {
            switch ($info['http_code']) {
            case 401:
                throw new UnauthorizedException();
            case 404:
                throw new NotFoundException();
            default:
                throw new RequestException('Unexpected response code: ' . $info['http_code']);
            }
        }

        return $response;
    }

    /**
     * Builds a request URL
     */
    private function buildUrl($method) {
        $url = $this->urlRoot;
        if ($url[strlen($url)-1] != '/') {
            $url .= '/';
        }
        $url .= $this->apiVersion;
        if (is_array($method)) {
            $method = implode('/', $method);
        }
        if ($method[0] != '/') {
            $url .= '/';
        }
        return $url . $method;
    }

    /**
     * Convert a list of stdClass objects into typed objects.
     *
     * @param array $list A list of stdClass objects.
     * @param string $type The full type name of the desired type.
     * @return array An array of objects from the supplied type.
     */
    private function boxList(array $list, $type) {
        $result = array();
        foreach ($list as $key => $val) {
            $result[$key] = $this->box($val, $type);
        }
        return $result;
    }

    /**
     * Convert a stdClass object into a typed object.
     *
     * @param object $orig A stdClass object.
     * @param string $type The full type name of the desired type.
     * @return object An object from the supplied type.
     */
    private function box(\stdClass $orig, $type) {
        $obj = new $type();
        foreach ($orig as $key => $val) {
            $obj->$key = $val;
        }
        return $obj;
    }
}
