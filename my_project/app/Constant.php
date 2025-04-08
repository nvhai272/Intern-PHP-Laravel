<?php

namespace App\Constants;

define('MSG_WELCOME', 'WELCOME TO HOME PAGE');

// SUCCESS
define('CREATE_SUCCEED', 'Created successfully');
define('UPDATE_SUCCEED', 'Updated successfully');
define('DELETE_SUCCEED', 'Deleted successfully');
define('LOGIN_SUCCEED', 'Login successfully');
define('LOGOUT', 'User has successfully logged out');

// ERR
define('ERROR_CREATE_FAILED', 'Create failed');
define('ERROR_READ_FAILED', 'Retrieving data failed');
define('ERROR_UPDATE_FAILED', 'Update failed');
define('ERROR_DELETE_FAILED', 'Delete failed');
define('ERROR_NOT_FOUND', 'Data not found');
define('ERROR_DATABASE', 'Error database');
define('ERROR_SYSTEM', 'Error system, check again');
define('ERROR_ID', 'Invalid id');
define('ERR_VALIDATION', 'Error validation');
define('ERR_NOT_LOGIN', 'Account not logged in - Access denied');
define('ERR_LOGIN', 'Login failed');
define('ERR_INPUT_SEARCH', 'Input field to search');
define('ERROR_SEARCH','Error search');
define('ERR_TIMEOUT','Error timeout');
define('ERR_EXPORT_FILE','Error export file');
define('ERR_DATA_EXPORT','No has data to export file');


// VALUE
define('IS_DELETED', '1');
define('IS_NOT_DELETED', '0');

define('GENDER_MALE', '1');
define('GENDER_FEMALE', '2');

define('POSITION_MANAGER', '1');
define('POSITION_LEADER', '2');
define('POSITION_BSE', '3');
define('POSITION_DEV', '4');
define('POSITION_TESTER', '5');

define('WORK_FULLTIME', '1');
define('WORK_PART_TIME', '2');
define('WORK_TEST', '3');
define('WORK_INTERN', '4');

define('STATUS_WORKING', '1');
define('STATUS_RETIRED', '2');

class Constant
{
    // array to show value follow key
    public static $genders = [
        GENDER_MALE => 'Man',
        GENDER_FEMALE => 'Woman',
    ];

    public static $positions = [
        POSITION_MANAGER => 'Manager',
        POSITION_LEADER => 'Leader',
        POSITION_BSE => 'BSE',
        POSITION_DEV => 'Dev',
        POSITION_TESTER => 'Tester',
    ];

    public static $workTypes = [
        WORK_FULLTIME => 'Fulltime',
        WORK_PART_TIME => 'Part time',
        WORK_TEST => 'Test',
        WORK_INTERN => 'Intern',
    ];

    public static $status = [
        STATUS_WORKING => 'Working',
        STATUS_RETIRED => 'Retired',
    ];
}
