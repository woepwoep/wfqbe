<?php
$GLOBALS['TCA']['tx_wfqbe_domain_model_backend'] = array (
    "ctrl" => Array(
        'title' => 'LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        "default_sortby" => "ORDER BY sorting",
        "delete" => "deleted",
        "enablecolumns" => Array(
            "disabled" => "hidden",
        ),
        "dividers2tabs" => true,
        "iconfile" => "EXT:wfqbe/Resources/Public/Icons/icon_tx_wfqbe_domain_model_query.gif",
    ),
    "interface" => array (
        "showRecordFieldList" => "hidden,title,description,listq,detailsq,searchq,insertq,typoscript,recordsforpage"
    ),
    "columns" => Array(
        "hidden" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
            "config" => Array(
                "type" => "check",
                "default" => "0"
            )
        ),
        "title" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.title",
            "config" => Array(
                "type" => "input",
                "size" => "30",
            )
        ),
        "description" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.description",
            "config" => Array(
                "type" => "text",
                "cols" => "30",
                "rows" => "5",
            )
        ),
        "listq" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.listq",
            "config" => Array(
                "type" => "group",
                "internal_type" => "db",
                "allowed" => "tx_wfqbe_domain_model_query",
                "size" => 1,
                "minitems" => 0,
                "maxitems" => 1,
            )
        ),
        "detailsq" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.detailsq",
            "config" => Array(
                "type" => "group",
                "internal_type" => "db",
                "allowed" => "tx_wfqbe_domain_model_query",
                "size" => 5,
                "minitems" => 0,
                "maxitems" => 100,
            )
        ),
        "searchq" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.searchq",
            "config" => Array(
                "type" => "group",
                "internal_type" => "db",
                "allowed" => "tx_wfqbe_domain_model_query",
                "size" => 1,
                "minitems" => 0,
                "maxitems" => 1,
            )
        ),
        "insertq" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.insertq",
            "config" => Array(
                "type" => "group",
                "internal_type" => "db",
                "allowed" => "tx_wfqbe_domain_model_query",
                "size" => 1,
                "minitems" => 0,
                "maxitems" => 1,
            )
        ),
        "typoscript" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.typoscript",
            "config" => Array(
                "type" => "text",
                "cols" => "80",
                "rows" => "20",
            )
        ),
        "recordsforpage" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.recordsforpage",
            "config" => Array(
                "type" => "input",
                "size" => "5",
                "eval" => "int",
            )
        ),
        "searchq_position" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.searchq_position",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array("LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.searchq_position.I.0", "bottom"),
                    Array("LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.searchq_position.I.1", "top"),
                ),
                "size" => 1,
                "maxitems" => 1,
                "renderType" => "selectSingle",
            )
        ),
        "export_mode" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.export_mode",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array("LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.export_mode.I.0", ""),
                    Array("LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.export_mode.I.1", "csv"),
                    Array("LLL:EXT:lang/locallang_db.xml:tx_wfqbe_domain_model_backend.export_mode.I.2", "xls"),
                ),
                "size" => 1,
                "maxitems" => 1,
                "renderType" => "selectSingle",

            )
        ),
    ),
    "types" => array (
        "0" => array("showitem" => "hidden, --palette--;;1, title,description,--div--;Listing,listq, recordsforpage, export_mode,--div--;Details,detailsq,--div--;Search,searchq,searchq_position,--div--;Insert,insertq,--div--;Config,typoscript"),
    ),
    "palettes" => array (
    )
);
