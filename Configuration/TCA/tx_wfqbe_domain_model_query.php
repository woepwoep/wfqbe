<?php
$GLOBALS['TCA']['tx_wfqbe_domain_model_query'] = array(
    "ctrl" => Array(
        'title' => 'LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:tableName',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'type' => 'type',
        "default_sortby" => "ORDER BY title",
        "delete" => "deleted",
        "enablecolumns" => Array(
            "disabled" => "hidden",
            "fe_group" => "fe_group",
        ),
        "iconfile" => 'EXT:wfqbe/Resources/Public/Icons/icon_Query.gif',
    ),
    "interface" => Array(
        "showRecordFieldList" => "hidden,fe_group,type,title,description,query,search,insertq,connectionname,searchinquery"
    ),
    "columns" => Array(
        "hidden" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_general.xml:LGL.hidden",
            "config" => Array(
                "type" => "check",
                "default" => "0"
            )
        ),
        "fe_group" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/locallang_general.xml:LGL.fe_group",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array("", 0),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_general.xml:LGL.hide_at_login", -1),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_general.xml:LGL.any_login", -2),
                    Array("LLL:EXT:wfqbe/Resources/Private/Language/locallang_general.xml:LGL.usergroups", "--div--")
                ),
                "foreign_table" => "fe_groups",
                "renderType" => "selectSingle",
            )
        ),
        'type' => Array(
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.type",
            'config' => Array(
                'type' => 'select',
                'items' => Array(
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.type.I.0', 'select'),
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.type.I.1', 'insert'),
                    Array('LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.type.I.2', 'search'),
                ),
                "renderType" => "selectSingle",
                'default' => 'select',
                'authMode' => $GLOBALS['TYPO3_CONF_VARS']['BE']['explicitADmode'],
                'authMode_enforce' => 'strict',
            )
        ),
        "title" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.title",
            "config" => Array(
                "type" => "input",
                "size" => "30",
            )
        ),
        "description" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.description",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
            )
        ),
        "query" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.query",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
                "wizards" => Array(
                    "_PADDING" => 2,
                    "example" => Array(
                        "title" => "Select Wizard:",
                        "type" => "script",
                        "notNewRecords" => 1,
                        "icon" => 'EXT:wfqbe/Resources/Public/Icons/select_wizard_icon.gif',
                        'module' => array(
                            'name' => 'xMOD_tx_wfqbe_query_querywiz',
                        ),
                    ),
                ),
            )
        ),
        "search" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.search",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
                "wizards" => Array(
                    "_PADDING" => 2,
                    "example" => Array(
                        "title" => "Search Wizard:",
                        "type" => "script",
                        "notNewRecords" => 1,
                        "icon" => 'EXT:wfqbe/Resources/Private/Icons/wfqbe_query_search_wizard_icon.gif',
                        'module' => array(
                            'name' => 'xMOD_tx_wfqbe_query_searchwiz',
                        ),
                    ),
                ),
            )
        ),
        "insertq" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.insertq",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
                "wizards" => Array(
                    "_PADDING" => 2,
                    "example" => Array(
                        "title" => "Insert Wizard:",
                        "type" => "script",
                        "notNewRecords" => 1,
                        "icon" => 'EXT:wfqbe/Resources/Private/Icons/wfqbe_query_insert_wizard_icon.gif',
                        'module' => array(
                            'name' => 'xMOD_tx_wfqbe_query_insertwiz',
                        ),
                    ),
                ),
            )
        ),
        "connectionname" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.connectionname",
            "config" => Array(
                "type" => "input",
                "size" => "30",
            )
        ),
        "searchinquery" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:wfqbe/Resources/Private/Language/Query.xlf:Query.searchinquery",
            "config" => Array(
                "type" => "group",
                "internal_type" => "db",
                "allowed" => "Query",
                "size" => 1,
                "minitems" => 0,
                "maxitems" => 1,
            )
        ),
    ),
    "types" => array(
        "0" => array("showitem" => "hidden, --palette--;;1, type, title;;, description;;, connectionname, query"),
        "select" => array("showitem" => "hidden, --palette--;;1, type, title;;, description;;, connectionname, query"),
        "insert" => array("showitem" => "hidden, --palette--;;1, type, title;;, description;;, connectionname, insertq"),
        "search" => array("showitem" => "hidden, --palette--;;1, type, title;;, description;;, connectionname, searchinquery, search"),
    ),
    "palettes" => array(
        "1" => array("showitem" => "fe_group"),
    )
);
