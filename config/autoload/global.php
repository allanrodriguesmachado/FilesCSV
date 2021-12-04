<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return [
    /**
     * Web Service Endereço
     */
    'endereco' => [
        'class'
    ],

    /**
     * Connection LDAP
     */
    'auth' => [
        'class' => 'Laminas\Authentication\Adapter\Ldap',
        'config' => [
            'server1' => [
                'host'              => "ldap.forumsys.com",
                'username'          => "cn=read-only-admin,dc=example,dc=com",
                'password'          => 'password',
                'accountFilterFormat' => '(&(objectClass=inetOrgPerson)(uid=%s))',
                'bindRequiresDn'    => true,
                'baseDn'            => 'dc=example,dc=com',
            ],
        ],
    ],
    'session' => [
        'class' => 'Laminas\Session\Container',
        'config' => [
            'name' => 'portal'
        ],
    ],
];
