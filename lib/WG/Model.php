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

/**
 * Dataset resource.
 *
 * A dataset represents one data file that is imported.
 */
class Dataset {
    /**
     * A unique string identifier for the object.
     *
     * @var string
     */
    public $id;

    /**
     * A universally unique identifier. This is used to generate the URL that points to the source data.
     *
     * @var string
     */
    public $uuid;

    /**
     * The name of the dataset.
     *
     * @var string
     */
    public $name;

    /**
     * A timestamp indicating when this dataset was first created.
     *
     * @var string
     */
    public $creationDate;

    /**
     * The ID of the user that owns this dataset.
     *
     * @var string
     */
    public $owner;
}

/**
 * A user resource.
 */
class User {
    /**
     * A unique string identifier for the object.
     *
     * @var string
     */
    public $id;

    /**
     * The e-mail address of the user, which is used to log in.
     *
     * @var string
     */
    public $email;

    /**
     * First name of the user.
     *
     * @var string
     */
    public $firstname;

    /**
     * Last name of the user.
     *
     * @var string
     */
    public $lastname;

    /**
     * Timezone identifier.
     *
     * A full list can be found in the TZ column at <a href="http://en.wikipedia.org/wiki/List_of_tz_database_time_zones">Wikipedia</a>.
     * @var string
     */
    public $timeZoneId;

    /**
     * User type.
     *
     * This should be any of "administrator", "analyst" or "viewer".
     * @var string
     */
    public $type;

    /**
     * Whether or not this account is enabled.
     *
     * @var boolean
     */
    public $active;
}

/**
 * Report resource.
 */
class Report {
    /**
     * A unique string identifier for the object.
     *
     * @var string
     */
    public $id;

    /**
     * The name of the report.
     *
     * @var string
     */
    public $name;

    /**
     * The ID of the dataset on which this report is based.
     *
     * @var string
     */
    public $datasetId;

    /**
     * The ID of the user that created this report.
     *
     * @var string
     */
    public $owner;
}

/**
 * Operation status.
 */
class OperationStatus {
    /**
     * Date when the operation was requested.
     *
     * @var string
     */
    public $insertedOn;

    /**
     * Date when the operation was started.
     *
     * @var string
     */
    public $startedOn;

    /**
     * Date when the operation was finished.
     *
     * @var string
     */
    public $finishedOn;

    /**
     * Whether or not the operation has failed.
     *
     * @var boolean
     */
    public $hasFailed;

    /**
     * Progress indicator.
     *
     * @var int
     */
    public $progress;

    /**
     * Status message.
     *
     * @var string
     */
    public $message;
}
