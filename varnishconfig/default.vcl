C{
#include <stdlib.h>
#include <stdio.h>
#include <time.h>
#include <pthread.h>
static pthread_mutex_t lrand_mutex = PTHREAD_MUTEX_INITIALIZER;
void generate_uuid(char* buf) {
pthread_mutex_lock(&lrand_mutex);
long a = lrand48();
long b = lrand48();
long c = lrand48();
long d = lrand48();
pthread_mutex_unlock(&lrand_mutex);
sprintf(buf, "frontend=%08lx%04lx%04lx%04lx%04lx%08lx",
a,
b & 0xffff,
(b & ((long)0x0fff0000) >> 16) | 0x4000,
(c & 0x0fff) | 0x8000,
(c & (long)0xffff0000) >> 16,
d
);
return;
}
}C
import std;

backend default {
.host = "127.0.0.1";
.port = "8080";
.connect_timeout = 10s;
.first_byte_timeout = 300s;
.between_bytes_timeout = 300s;
}

backend admin {
.host = "127.0.0.1";
.port = "8080";
.connect_timeout = 10s;
.first_byte_timeout = 21600s;
.between_bytes_timeout = 21600s;
}

acl crawler_acl {
"127.0.0.1";
}

acl debug_acl {}

sub generate_session {
if (req.url ~ ".*[&?]SID=([^&]+).*") {
set req.http.X-Varnish-Faked-Session = regsub(
req.url, ".*[&?]SID=([^&]+).*", "frontend=\1");
} else {
C{
char uuid_buf [50];
generate_uuid(uuid_buf);
VRT_SetHdr(sp, HDR_REQ,
"\030X-Varnish-Faked-Session:",
uuid_buf,
vrt_magic_string_end
);
}C
}
if (req.http.Cookie) {
std.collect(req.http.Cookie);
set req.http.Cookie = req.http.X-Varnish-Faked-Session +
"; " + req.http.Cookie;
} else {
set req.http.Cookie = req.http.X-Varnish-Faked-Session;
}
}
sub generate_session_expires {
C{
time_t now = time(NULL);
struct tm now_tm = *gmtime(&now);
now_tm.tm_sec += 54000;
mktime(&now_tm);
char date_buf [50];
strftime(date_buf, sizeof(date_buf)-1, "%a, %d-%b-%Y %H:%M:%S %Z", &now_tm);
VRT_SetHdr(sp, HDR_RESP,
"\031X-Varnish-Cookie-Expires:",
date_buf,
vrt_magic_string_end
);
}C
}
sub vcl_recv {

if (req.restarts == 0) {
if (req.http.X-Forwarded-For) {
set req.http.X-Forwarded-For =
req.http.X-Forwarded-For + ", " + client.ip;
} else {
set req.http.X-Forwarded-For = client.ip;
}
}
if(false) {
set req.http.X-Varnish-Origin-Url = req.url;
}
if (req.http.Accept-Encoding) {
if (req.http.Accept-Encoding ~ "gzip") {
set req.http.Accept-Encoding = "gzip";
} else if (req.http.Accept-Encoding ~ "deflate") {
set req.http.Accept-Encoding = "deflate";
} else {
unset req.http.Accept-Encoding;
}
}
if (req.http.User-Agent ~ "iP(?:hone|ad|od)|BlackBerry|Palm|Googlebot-Mobile|Mobile|mobile|mobi|Windows Mobile|Safari Mobile|Android|Opera (?:Mini|Mobi)") {
set req.http.X-Normalized-User-Agent = "mobile";
} else if (req.http.User-Agent ~ "MSIE") {
set req.http.X-Normalized-User-Agent = "msie";
} else if (req.http.User-Agent ~ "Firefox") {
set req.http.X-Normalized-User-Agent = "firefox";
} else if (req.http.User-Agent ~ "Chrome") {
set req.http.X-Normalized-User-Agent = "chrome";
} else if (req.http.User-Agent ~ "Safari") {
set req.http.X-Normalized-User-Agent = "safari";
} else if (req.http.User-Agent ~ "Opera") {
set req.http.X-Normalized-User-Agent = "opera";
} else {
set req.http.X-Normalized-User-Agent = "other";
}

if (req.http.Authorization ||
req.request !~ "^(GET|HEAD|OPTIONS)$" ||
req.http.Cookie ~ "ustme" ||
req.url ~ "^/projectmedia" ||
req.url ~ "^/upload") {
    return (pipe);
}

if (req.url ~ "^/signup" ||
    req.url ~ "^/auth" ||
    req.url ~ "^/updatestats"||
    req.url ~ "^/ajax" ||
    req.url ~ "^/login" ||
    req.url ~ "^/logout" ||
    req.url ~ "^/payment" ||
    req.url ~ "^/payfort" ||
    req.url ~ "^/feedback" ||
    req.url ~ "^/notification" ||
    req.url ~ "^/forgotpassword" ||
    req.url ~ "^/resetpassword") {
    set req.backend = admin;
    return (pass);
}


set req.url = regsuball(req.url, "([^:])//+", "\1/");
if (req.url ~ "^(/ae\-en/|/sa\-en/|/ae\-ar/|/sa\-ar/|/skin/|/js/|/)(?:(?:index|litespeed)\.php/)?") {
set req.http.X-Turpentine-Secret-Handshake = "1";
if (req.http.Cookie !~ "frontend=" && !req.http.X-Varnish-Esi-Method) {
if (client.ip ~ crawler_acl ||
req.http.User-Agent ~ "^(?:ApacheBench/.*|.*Googlebot.*|JoeDog/.*Siege.*|magespeedtest\.com|Nexcessnet_Turpentine/.*)$") {
set req.http.Cookie = "frontend=crawler-session";
} else {
call generate_session;
}
}
if (true &&
req.url ~ ".*\.(?:css|js|jpe?g|png|gif|ico|swf)(?=\?|&|$)") {
unset req.http.Cookie;
unset req.http.X-Varnish-Faked-Session;
set req.http.X-Varnish-Static = 1;
return (lookup);
}

if (true && req.url ~ "[?&](utm_source|utm_medium|utm_campaign|utm_content|utm_term|gclid|cx|ie|cof|siteurl)=") {
set req.url = regsuball(req.url, "(?:(\?)?|&)(?:utm_source|utm_medium|utm_campaign|utm_content|utm_term|gclid|cx|ie|cof|siteurl)=[^&]+", "\1");
set req.url = regsuball(req.url, "(?:(\?)&|\?$)", "\1");
}
if(false) {
set req.http.X-Varnish-Cache-Url = req.url;
set req.url = req.http.X-Varnish-Origin-Url;
unset req.http.X-Varnish-Origin-Url;
}
return (lookup);
}
}
sub vcl_pipe {
unset bereq.http.X-Turpentine-Secret-Handshake;
set bereq.http.Connection = "close";
}
sub vcl_hash {
if (true && req.http.X-Varnish-Static) {
hash_data(req.url);
if (req.http.Accept-Encoding) {
hash_data(req.http.Accept-Encoding);
}
return (hash);
}
if(false && req.http.X-Varnish-Cache-Url) {
hash_data(req.http.X-Varnish-Cache-Url);
} else {
hash_data(req.url);
}
if (req.http.Host) {
hash_data(req.http.Host);
} else {
hash_data(server.ip);
}
hash_data(req.http.Ssl-Offloaded);
if (req.http.X-Normalized-User-Agent) {
hash_data(req.http.X-Normalized-User-Agent);
}
if (req.http.Accept-Encoding) {
hash_data(req.http.Accept-Encoding);
}
if (req.http.X-Varnish-Store || req.http.X-Varnish-Currency) {
hash_data("s=" + req.http.X-Varnish-Store + "&c=" + req.http.X-Varnish-Currency);
}
if (req.http.X-Varnish-Esi-Access == "private" &&
req.http.Cookie ~ "frontend=") {
hash_data(regsub(req.http.Cookie, "^.*?frontend=([^;]*);*.*$", "\1"));
hash_data(client.ip);
hash_data(req.http.Via);
hash_data(req.http.X-Forwarded-For);
}
if (req.http.X-Varnish-Esi-Access == "customer_group" &&
req.http.Cookie ~ "customer_group=") {
hash_data(regsub(req.http.Cookie, "^.*?customer_group=([^;]*);*.*$", "\1"));
}
return (hash);
}
sub vcl_hit {
}
sub vcl_fetch {
set req.grace = 15s;
set beresp.http.X-Varnish-Host = req.http.host;
set beresp.http.X-Varnish-URL = req.url;
if (req.url ~ "^(/ae\-en/|/sa\-en/|/ae\-ar/|/sa\-ar/|/skin/|/js/|/)(?:(?:index|litespeed)\.php/)?") {
unset beresp.http.Vary;
set beresp.do_gzip = true;
if (beresp.status != 200 && beresp.status != 404) {
set beresp.ttl = 15s;
return (hit_for_pass);
} else {
if (beresp.http.Set-Cookie) {
set beresp.http.X-Varnish-Set-Cookie = beresp.http.Set-Cookie;
unset beresp.http.Set-Cookie;
}
unset beresp.http.Cache-Control;
unset beresp.http.Expires;
unset beresp.http.Pragma;
unset beresp.http.Cache;
unset beresp.http.Age;

    set beresp.do_esi = true;

if (beresp.http.X-Turpentine-Cache == "0") {
set beresp.ttl = 15s;
return (hit_for_pass);
} else {
if (true &&
bereq.url ~ ".*\.(?:css|js|jpe?g|png|gif|ico|swf)(?=\?|&|$)") {
set beresp.ttl = 28800s;
set beresp.http.Cache-Control = "max-age=28800";
} elseif (req.http.X-Varnish-Esi-Method) {
if (req.http.X-Varnish-Esi-Access == "private" &&
req.http.Cookie ~ "frontend=") {
set beresp.http.X-Varnish-Session = regsub(req.http.Cookie,
"^.*?frontend=([^;]*);*.*$", "\1");
}
if (req.http.X-Varnish-Esi-Method == "ajax" &&
req.http.X-Varnish-Esi-Access == "public") {
set beresp.http.Cache-Control = "max-age=" + regsub(
req.url, ".*/ttl/(\d+)/.*", "\1");
}
set beresp.ttl = std.duration(
regsub(
req.url, ".*/ttl/(\d+)/.*", "\1s"),
300s);
if (beresp.ttl == 0s) {
set beresp.ttl = 15s;
return (hit_for_pass);
}
} else {
set beresp.ttl = 3600s;
}
}
}
return (deliver);
}
}
sub vcl_deliver {
if (req.http.X-Varnish-Faked-Session) {
call generate_session_expires;
set resp.http.Set-Cookie = req.http.X-Varnish-Faked-Session +
"; expires=" + resp.http.X-Varnish-Cookie-Expires + "; path=/";
if (req.http.Host) {
if (req.http.User-Agent ~ "^(?:ApacheBench/.*|.*Googlebot.*|JoeDog/.*Siege.*|magespeedtest\.com|Nexcessnet_Turpentine/.*)$") {
set resp.http.Set-Cookie = resp.http.Set-Cookie +
"; domain=" + regsub(req.http.Host, ":\d+$", "");
} else {
if(req.http.Host ~ "[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)") {
set resp.http.Set-Cookie = resp.http.Set-Cookie +
"; domain=www.tasmeemme.com";
} else {
set resp.http.Set-Cookie = resp.http.Set-Cookie +
"; domain=" + regsub(req.http.Host, ":\d+$", "");
}
}
}
set resp.http.Set-Cookie = resp.http.Set-Cookie + "; httponly";
unset resp.http.X-Varnish-Cookie-Expires;
}
if (req.http.X-Varnish-Esi-Method == "ajax" && req.http.X-Varnish-Esi-Access == "private") {
set resp.http.Cache-Control = "no-cache";
}
if (true || client.ip ~ debug_acl) {
set resp.http.X-Varnish-Hits = obj.hits;
set resp.http.X-Varnish-Esi-Method = req.http.X-Varnish-Esi-Method;
set resp.http.X-Varnish-Esi-Access = req.http.X-Varnish-Esi-Access;
set resp.http.X-Varnish-Currency = req.http.X-Varnish-Currency;
set resp.http.X-Varnish-Store = req.http.X-Varnish-Store;
} else {
unset resp.http.X-Varnish;
unset resp.http.Via;
unset resp.http.X-Powered-By;
unset resp.http.Server;
unset resp.http.X-Turpentine-Cache;
unset resp.http.X-Turpentine-Esi;
unset resp.http.X-Turpentine-Flush-Events;
unset resp.http.X-Turpentine-Block;
unset resp.http.X-Varnish-Session;
unset resp.http.X-Varnish-Host;
unset resp.http.X-Varnish-URL;
unset resp.http.X-Varnish-Set-Cookie;
}
}
