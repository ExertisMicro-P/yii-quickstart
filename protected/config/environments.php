<?php
/**
 * Support different DBs on different platforms
 */


//die('SERVER_NAME='.$_SERVER['SERVER_NAME']);

class MPEnvironments {
    private $environments;

    public function __construct() {
        $this->defineEnvironments();
    }

        private function defineEnvironments() {
            $this->environments = array(
                // Dev


            'mb002095.micro-p.com'=>array(
                        'dbhost'=>'localhost',
                        'dbname'=>'yii-prizemanager',
                        'dbuser'=>'russellh',
                        'dbpassword'=>'sl58jySL%*JY',
                        'public'    => true,
                        //'yii'       => dirname(__FILE__).'/../../framework/yii.php'
                        'yii'       => dirname(__FILE__).'/../../../yii-1.1.14/framework/yii.php'
                    ),



);
        } // defineEnvironments


        /**
         * Tries to determine where we are running.
         * When running as a web application we can look at SERVER_NAME
         * When running as a console application we can look at COMPUTERNAME on MS Windows
         * or HOSTNAME on Linux
         *
         * @return string Web Application SERVER_NAME or equivalent
         * @throws Exception
         */
        private function getServerName() {

            $namemap = array(
                'MB002095'=>'local.htdocs.com',
                'intranet2.micro-p.com'=>'intranet2',

            );

            //echo 'SERVERNAME="'.$_SERVER['SERVER_NAME']."\"\r\n";
            //echo 'COMPUTERNAME="'.$_SERVER['COMPUTERNAME']."\"\r\n";
            //echo 'HOSTNAME="'.$_SERVER['HOSTNAME']."\"\r\n";

            if (empty($_SERVER['SERVER_NAME'])) {
                // probably running as a console application
                if (!empty($_SERVER['COMPUTERNAME'])) {
                    // probably running in Windows
                    if (!empty($namemap[$_SERVER['COMPUTERNAME']])) {
                        return $namemap[$_SERVER['COMPUTERNAME']];
                    } else
                        throw new Exception('Cannot map environment for (COMPUTERNAME) '.$_SERVER['COMPUTERNAME']);
                } else {
                    // probably running on Linux
                    if (!empty($_SERVER['HOSTNAME'])) {
                        if (!empty($namemap[$_SERVER['HOSTNAME']])) {
                            return $namemap[$_SERVER['HOSTNAME']];
                        } else
                            throw new Exception('Cannot map environment for (HOSTNAME) '.$_SERVER['HOSTNAME']);
                    } else {
                        // force it to a default setting
                        return 'intranet2';
                    }
                }
            } else
                return $_SERVER['SERVER_NAME'];
        } // getServerName



        public function getCurrentEnvironment() {

            $server = $this->getServerName();

            if (!empty($this->environments[$server]['sameas'])) {
                // Some servers share their configurations aso we can use the 'sameas' shorthand
                $sameas = $this->environments[$server]['sameas'];
                if (!empty($this->environments[$sameas]))
                        $curEnvironment = $this->environments[$sameas];
                else
                    throw new Exception($sameas.' envronment not found for server '.$server);
            } else {
                $curEnvironment = $this->environments[$server] ;
            }

            if (empty($curEnvironment))
                throw new Exception('No environment found for '.$server);

            return $curEnvironment;
        } // getCurrentEnvironment

} // class



$enviromentManager = new MPEnvironments();
$curEnvironment = $enviromentManager->getCurrentEnvironment();

define('DBHOST', $curEnvironment['dbhost']) ;
define('DBNAME', $curEnvironment['dbname']) ;
define('DBUSER', $curEnvironment['dbuser']) ;
define('DBPASS', $curEnvironment['dbpassword']) ;

$yii    = $curEnvironment['yii'] ;

// Controls debug.
// Public facing environments will have it turned off, unless Russell's PC is accessing it
define('IS_PUBLIC_FACING', $curEnvironment['public'] && ($_SERVER['REMOTE_ADDR'] != '172.27.14.150' && $_SERVER['REMOTE_ADDR'] != '172.27.15.36')) ;

