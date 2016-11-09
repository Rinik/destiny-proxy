# destiny-proxy

Simple Bungie.net proxy to handle api requests.
You must have Bungie.net API Key to use this proxy.

## how to use proxy?

send request to `http://localhost/proxy/`

`http://localhost/proxy/?clan=`
clan name returns Clan id

`http://localhost/proxy/?members=`
clan id returns clan members (member id) in array

`http://localhost/proxy/?member=`
member id returns member json

`http://localhost/proxy/?full=`
clan name returns clan member jsons in array

`http://localhost/proxy/?crucible=`
user name (playstation display name) returns crucible score from destinytracker
