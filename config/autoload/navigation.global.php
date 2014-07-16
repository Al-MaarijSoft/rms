<?php

return array(
    //****************************************************
    //Start Navigation Config
    //****************************************************
    'navigation' => array(
        'default' => array(
            //********************Start-Application [Link# 1.0.0]
            'Application' => array(
                'label' => 'Home', //[Link# 1.0.0] withoutChild
                'route' => 'home',
            ),
            //********************End-Application
            //********************Start-Account-Module [Link# 2.0.0]
            'Account' => array(
                'label' => 'Accounts', //[Link# 2.0.0] With Child
                'id' => 'idAccounts',
                'uri' => '#',
//                'route' => 'account',
//                'controller' => 'account',
//                'action' => 'index',
                'resource' => 'Account\Controller\Account',
                'privilege' => 'index',
//                'order' => 1,
                'pages' => array(
                    //********************Start-Account-Page [Link# 2.1.0]
                    array(
                        'label' => 'Account Tools', //[Link# 2.1.0] With Child
                        'id' => 'idAccountTools',
                        'uri' => '#',
//                        'route' => 'account',
//                        'controller' => 'account',
//                        'action' => 'index',
                        'resource' => 'Account\Controller\Account',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Accounts', // [Link# 2.1.1] Without Child
                                'route' => 'account',
                                'params' => array('controller' => 'account', 'action' => 'index'),
                                'resource' => 'Account\Controller\Account',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Account', // [Link# 2.1.2] Without Child
                                'route' => 'account',
                                'params' => array('controller' => 'account', 'action' => 'add'),
                                'resource' => 'Account\Controller\Account',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-Account-Page [Link# 2.1.0]
                    //********************Start-Voucher-Page [Link# 2.2.0]
                    array(
                        'label' => 'Voucher Tools', // [Link# 2.2.0] With Child
                        'id' => 'IdVoucherTools',
                        'uri' => '#',
                        'resource' => 'Account\Controller\Voucher',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Vouchers', // [Link# 2.2.1] Without Child
                                'route' => 'voucher',
                                'params' => array('controller' => 'voucher', 'action' => 'index'),
                                'resource' => 'Account\Controller\Voucher',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Voucher', // [Link# 2.2.2] Without Child
                                'route' => 'voucher',
                                'params' => array('controller' => 'voucher', 'action' => 'add'),
                                'resource' => 'Account\Controller\Voucher',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-Voucher-Page [Link# 2.2.0]
                    //********************Start-FinancialYear-Page [Link# 2.3.0]
                    array(
                        'label' => 'Financial Year Tools', // [Link# 2.3.0] With Child
                        'id' => 'IdFYTools',
                        'uri' => '#',
                        'resource' => 'Account\Controller\FinancialYear',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Financial Years', // [Link# 2.3.1] Without Child
                                'route' => 'financial_year',
                                'params' => array('controller' => 'financial_year', 'action' => 'index'),
                                'resource' => 'Account\Controller\FinancialYear',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Financial Year', // [Link# 2.3.2] Without Child
                                'route' => 'financial_year',
                                'params' => array('controller' => 'financial_year', 'action' => 'add'),
                                'resource' => 'Account\Controller\FinancialYear',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-FinancialYear-Page [Link# 2.3.0]
                    //********************Start-OpeningBalance-Page [Link# 2.4.0]
                    array(
                        'label' => 'Opening Balance Tools', // [Link# 2.4.0] With Child
                        'id' => 'IdOpeningBalanceTools',
                        'uri' => '#',
                        'resource' => 'Account\Controller\OpeningBalance',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Opening Balance', // [Link# 2.4.1] Without Child
                                'route' => 'opening_balance',
                                'params' => array('controller' => 'opening_balance', 'action' => 'index'),
                                'resource' => 'Account\Controller\OpeningBalance',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Opening Balance', // [Link# 2.4.1] Without Child
                                'route' => 'opening_balance',
                                'params' => array('controller' => 'opening_balance', 'action' => 'add'),
                                'resource' => 'Account\Controller\OpeningBalance',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-OpeningBalance-Page [Link# 2.4.0]
                ),
            ), //********************End-Account-Module [Link# 2.0.0]
            //********************Start-MemberBilling-Module [Link# 3.0.0]
            'Member Billing' => array(
                'label' => 'Member Billing', // [Link# 3.0.0] with Child
                'id' => 'IdMemberBilling',
                'uri' => '#',
                'resource' => 'MemberBilling\Controller\Plot',
                'privilege' => 'index',
                'pages' => array(
                    //********************Start-Plot-Page [Link# 3.1.0]
                    array(
                        'label' => 'Plotting', // [Link# 3.1.0] With Child
                        'id' => 'idPlotting',
                        'uri' => '#',
                        'resource' => 'MemberBilling\Controller\Plot',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Plots', // [Link# 3.1.1] Without Child
                                'route' => 'plot',
                                'params' => array('controller' => 'plot', 'action' => 'index'),
                                'resource' => 'MemberBilling\Controller\Plot',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Plot', // [Link# 3.1.2] Without Child
                                'route' => 'plot',
                                'params' => array('controller' => 'plot', 'action' => 'add'),
                                'resource' => 'MemberBilling\Controller\Plot',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-Plot-Page [Link# 3.1.0]
                    //********************Start-Member-Page [Link# 3.2.0]
                    array(
                        'label' => 'Member Tools', // [Link# 3.2.0] With Child
                        'id' => 'idMemberTools',
                        'uri' => '#',
                        'resource' => 'MemberBilling\Controller\Member',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Members', // [Link# 3.2.1] Without Child
                                'route' => 'member',
                                'params' => array('controller' => 'member', 'action' => 'index'),
                                'resource' => 'MemberBilling\Controller\Member',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Member', // [Link# 3.2.2] Without Child
                                'route' => 'member',
                                'params' => array('controller' => 'member', 'action' => 'add'),
                                'resource' => 'MemberBilling\Controller\Member',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-Member-Page [Link# 3.2.0]
                ),
            ), //********************End-MemberBilling-Module [Link# 3.0.0]
            //********************Start-Reporting-Module [Link# 4.0.0]
            'Reporting' => array(
                'label' => 'Reporting', // [Link# 4.0.0] With Child
                'uri' => '#',
                'resource' => 'Reporting\Controller\Reporting',
                'privilege' => 'filterVoucherReport',
                'pages' => array(
                    array(
                        'label' => 'Voucher Report', // [Link# 4.1.0] Without Child
                        'route' => 'reporting',
                        'params' => array('controller' => 'reporting', 'action' => 'filterVoucherReport'),
                        'resource' => 'Reporting\Controller\Reporting',
                        'privilege' => 'filterVoucherReport',
                    ),
                    array(
                        'label' => 'Accounts Ledger Report', // [Link# 4.1.0] Without Child
                        'route' => 'reporting',
                        'params' => array('controller' => 'reporting', 'action' => 'filterAccountsLedgerReport'),
                        'resource' => 'Reporting\Controller\Reporting',
                        'privilege' => 'filterAccountsLedgerReport',
                    ),
                ),
            ),
            //********************End-Reporting-Module [Link# 4.0.0]
            //********************Start-Administration-Module [Link# 5.0.0]
            'Administration' => array(
                'label' => 'Administration', // [Link# 5.0.0] With Child
//                'route' => 'login',
                'uri' => '#',
                'resource' => 'Administration\Controller\Administration',
                'privilege' => 'index',
                'pages' => array(
                    //********************Start-CompanyTools-Page [Link# 5.1.0]
                    array(
                        'label' => 'Company Tools', // [Link# 5.1.0] With Child
                        'id' => 'idCompanyTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\Company',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Companies', // [Link# 5.1.1] Without Child
                                'route' => 'company',
                                'params' => array('controller' => 'company', 'action' => 'index'),
                                'resource' => 'Administration\Controller\Company',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Company', // [Link# 5.1.2] Without Child
                                'route' => 'company',
                                'params' => array('controller' => 'company', 'action' => 'add'),
                                'resource' => 'Administration\Controller\Company',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-CompanyTools-Page [Link# 5.1.0]
                    //********************Start-BranchTools-Page [Link# 5.2.0]
                    array(
                        'label' => 'Branch Tools', // [Link# 5.2.0] With Child
                        'id' => 'idBranchTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\Branch',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Branches', // [Link# 5.2.1] Without Child
                                'route' => 'branch',
                                'params' => array('controller' => 'branch', 'action' => 'index'),
                                'resource' => 'Administration\Controller\Branch',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Branch', // [Link# 5.2.2] Without Child
                                'route' => 'branch',
                                'params' => array('controller' => 'branch', 'action' => 'add'),
                                'resource' => 'Administration\Controller\Branch',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-BranchTools-Page [Link# 5.2.0]
                    //********************Start-CountryTools-Page [Link# 5.3.0]
                    array(
                        'label' => 'Country Tools', // [Link# 5.3.0] With Child
                        'id' => 'idCountryTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\Country',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Countries', // [Link# 5.3.1] Without Child
                                'route' => 'country',
                                'params' => array('controller' => 'country', 'action' => 'index'),
                                'resource' => 'Administration\Controller\Country',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Country', // [Link# 5.3.2] Without Child
                                'route' => 'country',
                                'params' => array('controller' => 'country', 'action' => 'add'),
                                'resource' => 'Administration\Controller\Country',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-CountryTools-Page [Link# 5.3.0]
                    //********************Start-RoleTools-Page [Link# 5.4.0]
                    array(
                        'label' => 'Role Tools', // [Link# 5.4.0] With Child
                        'id' => 'idRoleTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\Role',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Roles', // [Link# 5.4.1] Without Child
                                'route' => 'role',
                                'params' => array('controller' => 'role', 'action' => 'index'),
                                'resource' => 'Administration\Controller\Role',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Role', // [Link# 5.4.2] Without Child
                                'route' => 'role',
                                'params' => array('controller' => 'role', 'action' => 'add'),
                                'resource' => 'Administration\Controller\Role',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-RoleTools-Page [Link# 5.4.0]
                    //********************Start-UserTools-Page [Link# 5.5.0]
                    array(
                        'label' => 'User Tools', // [Link# 5.5.0] With Child
                        'id' => 'idUserTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\User',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Users', // [Link# 5.5.1] Without Child
                                'route' => 'user',
                                'params' => array('controller' => 'user', 'action' => 'index'),
                                'resource' => 'Administration\Controller\User',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New User', // [Link# 5.5.2] Without Child
                                'route' => 'user',
                                'params' => array('controller' => 'user', 'action' => 'add'),
                                'resource' => 'Administration\Controller\User',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-UserTools-Page [Link# 5.5.0]
                    //********************Start-ResourceTools-Page [Link# 5.6.0]
                    array(
                        'label' => 'Resource Tools', // [Link# 5.6.0] With Child
                        'id' => 'idResourceTools',
                        'uri' => '#',
                        'resource' => 'Administration\Controller\Resource',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Resources', // [Link# 5.6.1] Without Child
                                'route' => 'resource',
                                'params' => array('controller' => 'resource', 'action' => 'index'),
                                'resource' => 'Administration\Controller\Resource',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Resource', // [Link# 5.6.2] Without Child
                                'route' => 'resource',
                                'params' => array('controller' => 'resource', 'action' => 'add'),
                                'resource' => 'Administration\Controller\Resource',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-ResourceTools-Page [Link# 5.6.0]
                    //********************Start-AllocationTools-Page [Link# 5.7.0]
                    array(
                        'label' => 'Allocation Tools', // [Link# 5.7.0] With Child
                        'id' => 'idResourceTools',
                        'uri' => '#',
                        'allocation' => 'Administration\Controller\Allocation',
                        'privilege' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'List Of Allocations', // [Link# 5.7.1] Without Child
                                'route' => 'allocation',
                                'params' => array('controller' => 'allocation', 'action' => 'index'),
                                'allocation' => 'Administration\Controller\Allocation',
                                'privilege' => 'index',
                            ),
                            array(
                                'label' => 'Add New Allocation', // [Link# 5.7.2] Without Child
                                'route' => 'allocation',
                                'params' => array('controller' => 'allocation', 'action' => 'add'),
                                'allocation' => 'Administration\Controller\Allocation',
                                'privilege' => 'add',
                            ),
                        ),
                    ), //********************End-AllocationTools-Page [Link# 5.7.0]
                ),
            ),
        //********************End-Administration-Module [Link# 5.0.0]
        ),
    ),
    //****************************************************
    //End Navigation Config
    //****************************************************
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        ),
    ),
);
