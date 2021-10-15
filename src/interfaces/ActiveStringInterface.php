<?php


namespace activeseven\interfaces;


interface ActiveStringInterface
{
    public function __construct(string $string);

    // VALIDATE
    public function isUrl(): bool;
    public function isEmail(): bool;
    public function isIp(): bool;
    public function isIpv6(): bool;
    public function isIpv4(): bool;
    public function isDomain(): bool;
}