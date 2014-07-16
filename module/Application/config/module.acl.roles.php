<?php
return array(
    'acl' => array(
        'roles' => array(
            'Guest' => null,
            'Jnr. Data Operator' => 'Guest',
            'Snr. Data Operator' => 'Jnr. Data Operator',
            'Assistant Member Billings' => 'Snr. Data Operator',
            'Member Billings Manager' => 'Assistant Member Billings',
            'Assistant Accounts' => 'Snr. Data Operator',
            'Accounts Manager' => 'Assistant Accounts',
            'Snr. Manager' => 'Member Billings Manager,Accounts Manager',
            'Administrator' => 'Snr. Manager',
            'SuperAdministrator' => 'Administrator'
        ),
        'resources' => array(
            'allow' => array(
                'Application\Controller\Index' => array(
                    'all' => 'Jnr. Data Operator',
                ),
                //Start Administration-Module
                'Administration\Controller\Administration' => array(
                    'login' => 'Guest',
                    'all' => 'Jnr. Data Operator',
                ),
                'Administration\Controller\Company' => array(
                    'populateState' => 'Administrator',
                    'populateCity' => 'Administrator',
                    'all' => 'SuperAdministrator',
                ),
                'Administration\Controller\Branch' => array(
                    'all' => 'Administrator',
                ),
                'Administration\Controller\Role' => array(
                    'all' => 'SuperAdministrator',
                ),
                'Administration\Controller\User' => array(
                    'all' => 'Administrator',
                ),
                'Administration\Controller\Resource' => array(
                    'all' => 'SuperAdministrator',
                ),
                'Administration\Controller\Allocation' => array(
                    'all' => 'SuperAdministrator',
                ),
                //End Administration-Module
                //**************************************
                //Start Account-Module
                'Account\Controller\Account' => array(
                    'index' => 'Assistant Accounts',
                    'all' => 'Accounts Manager',
                ),
                'Account\Controller\Voucher' => array(
                    'index' => 'Jnr. Data Operator',
                    'add' => 'Snr. Data Operator',
                    'generateVocuherNo' => 'Snr. Data Operator',
                    'getExchangeRate' => 'Snr. Data Operator',
                    'populateAccountSelectBoxes' => 'Snr. Data Operator',
                    'edit' => 'Assistant Accounts',
//                    'delete' => 'Accounts Manager',
//                    'printPreview' => 'Accounts Manager',
//                    'pdfReport' => 'Accounts Manager',
                    'all' => 'Accounts Manager',
                ),
                'Account\Controller\FinancialYear' => array(
                    'all' => 'Accounts Manager',
                ),
                'Account\Controller\OpeningBalance' => array(
                    'all' => 'Accounts Manager',
                ),
                //End Account-Module [Allow]
                //**************************************
                //Start MemberBilling-Module [Allow]
                'MemberBilling\Controller\Plot' => array(
                    'index' => 'Assistant Member Billings',
                    'add' => 'Assistant Member Billings',
                    'all' => 'Member Billings Manager',
                ),
                'MemberBilling\Controller\Member' => array(
                    'index' => 'Assistant Member Billings',
                    'add' => 'Assistant Member Billings',
                    'all' => 'Member Billings Manager',
                ),
                //End MemberBilling-Module [Allow]
                
                //**************************************
                //Start Reporting-Module [Allow]
                'Reporting\Controller\Reporting' => array(
                    'index' => 'Snr. Data Operator',
                    'filterVoucherReport' => 'Snr. Data Operator',
                    'showPdfVoucherReport' => 'Snr. Data Operator',
                    'all' => 'Administrator',
                ),
                //End Reporting-Module [Allow]
                
                //**************************************
                // Start Website-Module [Allow]
                  'Website\Controller\Website' => array(
                    'all' => 'Guest',
                ),
                //End Website-Module [Allow]
                //**************************************
                
                //**************************************
                // Start Xhtml-Module [Allow]
                  'Xhtml\Controller\Xhtml' => array(
                    'all' => 'Guest',
                ),
                //End Xhtml-Module [Allow]
                //**************************************
                
            ),
            'deny' => array(
                //Start Account-Module [Deny]
                'Account\Controller\Account' => array(
                    'all' => 'Assistant Member Billings',
                ),
                'Account\Controller\Voucher' => array(
                    'all' => 'Assistant Member Billings',
                ),
                'Account\Controller\FinancialYear' => array(
                    'all' => 'Assistant Member Billings',
                ),
                'Account\Controller\OpeningBalance' => array(
                    'all' => 'Assistant Member Billings',
                ),
                //End Account-Module [Deny]
                //**************************************
                //Start Other-Module [Deny]
            ),
        )
    )
);
