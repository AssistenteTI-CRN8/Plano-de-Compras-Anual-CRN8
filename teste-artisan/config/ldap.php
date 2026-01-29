<?php

return [
    'hosts' => [env('LDAP_HOSTS', '10.23.1.220')],
    'port' => env('LDAP_PORT', 389),
    'base_dn' => env('LDAP_BASE_DN', 'dc=crn8,dc=net'),
    'username' => env('LDAP_USERNAME', 'cn=admin,dc=crn8,dc=net'),
    'password' => env('LDAP_PASSWORD', ''),
    'timeout' => env('LDAP_TIMEOUT', 5),
    'use_ssl' => env('LDAP_SSL', false),
    'use_tls' => env('LDAP_TLS', false),
    'version' => 3,
    'follow_referrals' => false,
    
    // Atributos do usuário LDAP
    'user_attributes' => [
        'username' => 'uid',
        'email' => 'mail',
        'name' => 'cn',
        'groups' => 'memberOf',
    ],
    
    // Mapeamento de grupos LDAP para roles
    'role_mapping' => [
        'cn=superadmin,ou=groups,dc=example,dc=com' => 'superadmin',
        'cn=admin,ou=groups,dc=example,dc=com' => 'admin',
        'cn=users,ou=groups,dc=example,dc=com' => 'user',
    ],

];
