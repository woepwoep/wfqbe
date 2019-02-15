<?php

$GLOBALS['TCA']['tx_wfqbe_domain_model_credentials'] = array(
    "ctrl" => Array(
        'title' => 'LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'type' => 'type',
        "sortby" => "sorting",
        "delete" => "deleted",
        "iconfile" => 'EXT:wfqbe/Resources/Public/Icons/icon_tx_wfqbe_domain_model_credentials.gif',
    ),
    "interface" => array(
        "showRecordFieldList" => "title,host,dbms,username,passw,conn_type,setdbinit,dbname"
    ),
    "columns" => Array(
        "title" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.title",
            "config" => Array(
                "type" => "input",
                "size" => "30",
            )
        ),
        'type' => Array(
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.type",
            'config' => Array(
                'type' => 'select',
                'items' => Array(
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.type.I.0', 'standard'),
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.type.I.1', 'uri'),
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.type.I.2', 'localconf'),
                ),
                "renderType" => "selectSingle",
            )
        ),
        "host" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.host",
            "config" => Array(
                "type" => "input",
                "size" => "30",
                "eval" => "required",
            )
        ),
        "dbms" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.0", "mysqli"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.1", "postgres"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.2", "mssql"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.3", "oci8"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.4", "access"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbms.I.5", "sybase"),
                ),
                "size" => 1,
                "maxitems" => 1,
                "renderType" => "selectSingle",

            )
        ),
        "username" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.username",
            "config" => Array(
                "type" => "input",
                "size" => "30",
                "eval" => "required",
            )
        ),
        "passw" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.passw",
            "config" => Array(
                "type" => "input",
                "size" => "30",
                "eval" => "password",
            )
        ),
        "conn_type" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.conn_type",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.conn_type.I.0", "Connetc"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.conn_type.I.1", "PConnect"),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.conn_type.I.2", "NConnect"),
                ),
                "size" => 1,
                "maxitems" => 1,
                "renderType" => "selectSingle",
            )
        ),
        "setdbinit" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.setdbinit",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
            )
        ),
        "dbname" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.dbname",
            "config" => Array(
                "type" => "input",
                "size" => "30",
                "eval" => "",
            )
        ),
        "connection_uri" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.connection_uri",
            "config" => Array(
                "type" => "input",
                "size" => "80",
            )
        ),
        "connection_localconf" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tx_wfqbe_domain_model_credentials.connection_localconf",
            "config" => Array(
                "type" => "select",
                "items" => array(array('', '')),
                "size" => 1,
                "maxitems" => 1,
                "itemsProcFunc" => "tx_wfqbe_tca_credentials_connection_localconf_preprocessing->main",
                "renderType" => "selectSingle",
            )
        ),
    ),
    "types" => array(
        "0" => array("showitem" => "title, type, host, dbms, username, passw, dbname, conn_type, setdbinit"),
        "standard" => array("showitem" => "title, type, host, dbms, username, passw, dbname, conn_type, setdbinit"),
        "uri" => array("showitem" => "title, type, connection_uri, setdbinit"),
        "localconf" => array("showitem" => "title, type, connection_localconf, setdbinit"),
    ),
    "palettes" => array(
        "1" => array("showitem" => "")
    )
);
