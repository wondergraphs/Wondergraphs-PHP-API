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
