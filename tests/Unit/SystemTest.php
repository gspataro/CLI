<?php

use GSpataro\CLI\System;

uses()->group('core');

it('returns operating system name', function () {
    expect(System::getOs())->toBe(php_uname('s'));
});

it('returns operating system release', function () {
    expect(System::getOsRelease())->toBe(php_uname('r'));
});

it('returns operating system version', function () {
    expect(System::getOsVersion())->toBe(php_uname('v'));
});

it('returns machine hostname', function () {
    expect(System::getHostname())->toBe(php_uname('n'));
});

it('returns machine platform', function () {
    expect(System::getPlatform())->toBe(php_uname('m'));
});
