<?php

const BOOT_MODE_CGI = 'CGI';
const BOOT_MODE_CLI = 'CLI';
const BOOT_MODE_TEST = "TEST";

const BOOT_ENV_DEV = 'DEV'; // 开发环境
const BOOT_ENV_COOP = 'COOP'; // 协作环境
const BOOT_ENV_SANDBOX = 'SANDBOX'; // 沙盒环境
const BOOT_ENV_LIVE = 'LIVE'; // 生产环境

const DEBUG_SLOT_NULL = 0;
const DEBUG_SLOT_CORE = 1;
const DEBUG_SLOT_NOTE = 2;
const DEBUG_SLOT_TEMP = 4;

const DEBUG_REPORT_OFF = 0;
const DEBUG_REPORT_STD = 1; // stdout
const DEBUG_REPORT_LOG = 2; // debug log
const DEBUG_REPORT_JSC = 4; // Javascript console

const SECONDS_MINUTE = 60;
const SECONDS_HOUR = 3600;
const SECONDS_DAY = 86400;
